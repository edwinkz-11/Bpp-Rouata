<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\KementanNewsService;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(KementanNewsService $news): View
    {
        return view('web.news', ['news' => $news->latest(18), 'enabled' => $news->enabled()]);
    }
}
