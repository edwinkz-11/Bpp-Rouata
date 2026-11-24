<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\KementanNewsService;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(KementanNewsService $news): View
    {
        return view('web.news', [
            'posts' => Post::published()->latest('published_at')->latest('id')->paginate(6),
            'news' => $news->latest(18),
            'enabled' => $news->enabled(),
        ]);
    }
}
