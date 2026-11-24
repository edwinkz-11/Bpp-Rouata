<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Hitung kunjungan halaman publik per hari. Tidak menyimpan IP, cookie, maupun data pribadi. */
class TrackPageView
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|bing|facebookexternalhit|preview|curl|wget|python|monitor|uptime/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldCount($request, $response)) {
            try {
                PageView::hit($this->normalize($request));
            } catch (\Throwable $e) {
                report($e); // statistik tidak boleh menjatuhkan halaman
            }
        }

        return $response;
    }

    private function shouldCount(Request $request, Response $response): bool
    {
        return $request->isMethod('GET')
            && $response->getStatusCode() === 200
            && ! $request->is('admin', 'admin/*', 'up', 'sitemap.xml', 'robots.txt', 'peta-wilayah/data', 'dokumen/*/unduh')
            && ! $request->expectsJson()
            && ! preg_match(self::BOT_PATTERN, (string) $request->userAgent());
    }

    private function normalize(Request $request): string
    {
        return '/' . ltrim(mb_substr($request->path(), 0, 180), '/');
    }
}
