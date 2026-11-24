<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityPhoto;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Setting;
use App\Services\KementanNewsService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(KementanNewsService $news): View
    {
        return view('web.home', [
            'district' => Profile::district(),
            'activities' => Activity::published()->with(['category', 'photos'])
                ->orderByDesc('activity_date')->orderByDesc('id')->take(3)->get(),
            'commodities' => Commodity::active()->latest('id')->get(),
            'photos' => ActivityPhoto::whereHas('activity', fn ($q) => $q->published())->latest('id')->take(6)->get(),
            'posts' => Post::published()->latest('published_at')->latest('id')->take(3)->get(),
            'news' => $news->latest(3),
            'mapConfig' => $this->miniMapConfig(),
            'hasLocations' => Location::visible()->exists(),
        ]);
    }

    private function miniMapConfig(): array
    {
        return Setting::mapView() + [
            'dataUrl' => route('map.data'),
            'mini' => true,
            'categories' => Location::CATEGORIES,
        ];
    }
}
