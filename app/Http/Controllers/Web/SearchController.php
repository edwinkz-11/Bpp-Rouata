<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Commodity;
use App\Models\Document;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    private const MIN_LENGTH = 3;

    public function index(Request $request): View
    {
        $q = trim(mb_substr((string) $request->input('q'), 0, 100));
        $results = ['posts' => collect(), 'activities' => collect(), 'documents' => collect(), 'commodities' => collect()];

        if (mb_strlen($q) >= self::MIN_LENGTH) {
            $like = '%' . addcslashes($q, '%_\\') . '%';

            $results = [
                'posts' => Post::published()->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('body', 'like', $like))
                    ->latest('published_at')->take(8)->get(),
                'activities' => Activity::published()->with('photos')->where(fn ($w) => $w->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)->orWhere('location_name', 'like', $like))
                    ->latest('activity_date')->take(8)->get(),
                'documents' => Document::published()->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('description', 'like', $like))
                    ->latest('id')->take(8)->get(),
                'commodities' => Commodity::where('status', 'active')->where(fn ($w) => $w->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like))->orderBy('name')->take(8)->get(),
            ];
        }

        return view('web.search', [
            'q' => $q,
            'tooShort' => $q !== '' && mb_strlen($q) < self::MIN_LENGTH,
            'results' => $results,
            'total' => collect($results)->sum(fn ($items) => $items->count()),
        ]);
    }
}
