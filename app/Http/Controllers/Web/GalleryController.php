<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityPhoto;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $photos = ActivityPhoto::with('activity:id,title,slug,status')
            ->whereHas('activity', fn ($q) => $q->published())
            ->latest('id')->paginate(18);

        return view('web.gallery', compact('photos'));
    }
}
