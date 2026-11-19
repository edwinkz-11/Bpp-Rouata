<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\MapRoute;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        return view('web.map', [
            'mapConfig' => Setting::mapView() + [
                'dataUrl' => route('map.data'),
                'mini' => false,
                'categories' => Location::CATEGORIES,
            ],
        ]);
    }

    /** Data peta publik: hanya yang diterbitkan dan bukan draft. */
    public function data(): JsonResponse
    {
        $locations = Location::visible()->with('village:id,name')->get()->map(fn (Location $l) => [
            'id' => $l->id,
            'name' => $l->name,
            'category' => $l->category,
            'category_label' => $l->category_label,
            'description' => $l->description,
            'extra_info' => $l->extra_info,
            'village' => $l->village?->name,
            'lat' => $l->latitude,
            'lng' => $l->longitude,
            'image' => $l->image ? $l->image_url : null,
            'verified' => $l->verification_status === 'verified',
        ]);

        $routes = MapRoute::visible()->get()->map(fn (MapRoute $r) => [
            'id' => $r->id,
            'name' => $r->name,
            'description' => $r->description,
            'points' => $r->coordinates,
            'verified' => $r->verification_status === 'verified',
        ]);

        return response()->json([
            'locations' => $locations,
            'routes' => $routes,
            'boundary' => Setting::boundary(),
        ]);
    }
}
