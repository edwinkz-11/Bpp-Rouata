<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Post;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = "User-agent: *\nDisallow: /admin\nDisallow: /login\nDisallow: /cari\n\nSitemap: " . url('/sitemap.xml') . "\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $urls = collect([
            [route('home'), null, '1.0'],
            [route('district'), null, '0.7'],
            [route('activities.index'), null, '0.8'],
            [route('commodities.index'), null, '0.7'],
            [route('news'), null, '0.8'],
            [route('documents.index'), null, '0.7'],
            [route('ppid'), null, '0.7'],
            [route('gallery'), null, '0.5'],
            [route('map'), null, '0.5'],
            [route('about'), null, '0.6'],
            [route('contact'), null, '0.5'],
            [route('privacy'), null, '0.3'],
        ]);

        Activity::published()->latest('id')->take(2000)->get(['slug', 'updated_at'])
            ->each(fn ($a) => $urls->push([route('activities.show', $a->slug), $a->updated_at, '0.6']));

        Post::published()->latest('id')->take(2000)->get(['slug', 'updated_at'])
            ->each(fn ($p) => $urls->push([route('posts.show', $p->slug), $p->updated_at, '0.7']));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as [$loc, $updated, $priority]) {
            $xml .= '  <url><loc>' . e($loc) . '</loc>'
                . ($updated ? '<lastmod>' . $updated->toAtomString() . '</lastmod>' : '')
                . '<priority>' . $priority . '</priority></url>' . "\n";
        }

        return response($xml . '</urlset>' . "\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
