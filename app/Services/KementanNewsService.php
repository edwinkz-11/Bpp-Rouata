<?php

namespace App\Services;

use Carbon\Carbon;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Mengambil DAFTAR berita (judul, tanggal, tautan) dari situs resmi Kementerian Pertanian.
 * Hanya judul + tautan yang ditampilkan; isi berita dibaca di pertanian.go.id (sumber).
 * Hasil di-cache. Jika situs sumber gagal diakses, tampil cache terakhir atau tombol ke situs Kementan.
 */
class KementanNewsService
{
    private const MONTHS = [
        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
        'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
    ];

    public function enabled(): bool
    {
        return (bool) config('kementan.news_enabled');
    }

    /** @return array<int, array{id:int,title:string,url:string,image:string,date:?Carbon}> */
    public function latest(int $limit = 6): array
    {
        if (! $this->enabled()) {
            return [];
        }

        $items = Cache::remember('kementan_news', now()->addMinutes(config('kementan.cache_minutes')), function () {
            $fresh = $this->fetch();

            if ($fresh) {
                Cache::forever('kementan_news_last', $fresh);

                return $fresh;
            }

            return Cache::get('kementan_news_last', []);
        });

        // Jangan simpan hasil kosong lama-lama: coba lagi di permintaan berikutnya.
        if (! $items) {
            Cache::forget('kementan_news');
        }

        return array_slice($items, 0, $limit);
    }

    private function fetch(): array
    {
        foreach (config('kementan.news_sources') as $url) {
            try {
                $res = Http::timeout(config('kementan.timeout'))
                    ->withUserAgent('Mozilla/5.0 (compatible; BPP-Routa-Website/1.0)')
                    ->retry(1, 300, throw: false)
                    ->get($url);

                if ($res->successful()) {
                    $items = $this->parse($res->body());
                    if (count($items) >= 3) {
                        return $items;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Berita Kementan gagal diambil: '.$e->getMessage(), ['url' => $url]);
            }
        }

        return [];
    }

    public function parse(string $html): array
    {
        $prev = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8"?>'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $xp = new DOMXPath($dom);
        $links = $xp->query('//a[contains(@href,"/news/view/")]');
        $items = [];

        foreach ($links as $a) {
            /** @var DOMElement $a */
            if (! preg_match('#/news/view/(\d+)#', $a->getAttribute('href'), $m)) {
                continue;
            }

            $id = (int) $m[1];
            $title = $this->clean($a->textContent);
            $title = trim(preg_replace('/^gambar\s*/iu', '', $title));
            // Buang tanggal yang ikut terbawa di awal/akhir teks tautan.
            $date = $this->findDate($title);
            $title = trim(preg_replace($this->dateRegex(), '', $title));

            if (mb_strlen($title) < 15) {
                continue;
            }

            $date ??= $this->dateNear($a);

            if (! isset($items[$id]) || mb_strlen($title) > mb_strlen($items[$id]['title'])) {
                $items[$id] = [
                    'id' => $id,
                    'title' => $title,
                    'url' => rtrim(config('kementan.base_url'), '/').'/index.php/news/view/'.$id,
                    'image' => rtrim(config('kementan.base_url'), '/').'/img/news/img_big/'.$id,
                    'date' => $date ?? ($items[$id]['date'] ?? null),
                ];
            }
        }

        krsort($items); // id lebih besar = lebih baru

        return array_values($items);
    }

    private function dateNear(DOMElement $a): ?Carbon
    {
        $node = $a->parentNode;

        for ($i = 0; $i < 3 && $node instanceof DOMElement; $i++, $node = $node->parentNode) {
            $ids = [];
            foreach ((new DOMXPath($node->ownerDocument))->query('.//a[contains(@href,"/news/view/")]', $node) as $l) {
                if (preg_match('#/news/view/(\d+)#', $l->getAttribute('href'), $mm)) {
                    $ids[$mm[1]] = true;
                }
            }
            if (count($ids) > 1) {
                break; // wadah terlalu besar: berisi banyak berita
            }
            if ($d = $this->findDate($node->textContent)) {
                return $d;
            }
        }

        return null;
    }

    private function dateRegex(): string
    {
        return '/(?<!\d)\d{1,2}\s*(?:januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+\d{4}(?!\d)|(?<!\d)\d{1,2}-\d{1,2}-\d{4}(?!\d)/iu';
    }

    private function findDate(string $text): ?Carbon
    {
        if (! preg_match($this->dateRegex(), $text, $m)) {
            return null;
        }

        try {
            if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $m[0], $p)) {
                return Carbon::create((int) $p[3], (int) $p[2], (int) $p[1]);
            }
            preg_match('/(\d{1,2})\s*([a-z]+)\s+(\d{4})/iu', $m[0], $q);
            [$d, $mon, $y] = [$q[1], mb_strtolower($q[2]), $q[3]];

            return Carbon::create((int) $y, self::MONTHS[$mon], (int) $d);
        } catch (\Throwable) {
            return null;
        }
    }

    private function clean(string $s): string
    {
        return trim(preg_replace('/\s+/u', ' ', $s));
    }
}
