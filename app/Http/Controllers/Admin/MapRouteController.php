<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MapRouteRequest;
use App\Models\Location;
use App\Models\MapRoute;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MapRouteController extends Controller
{
    public function index(Request $request): View
    {
        $routes = MapRoute::with(['startLocation:id,name', 'endLocation:id,name'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('verification_status', $request->input('status')))
            ->latest('id')->paginate(15)->withQueryString();

        return view('admin.routes.index', compact('routes'));
    }

    public function create(): View
    {
        return view('admin.routes.form', $this->formData(new MapRoute([
            'verification_status' => 'unverified',
            'coordinates' => [],
        ])));
    }

    public function store(MapRouteRequest $request): RedirectResponse
    {
        $data = $this->payload($request);
        $data['created_by'] = $request->user()->id;

        MapRoute::create($data);

        return redirect()->route('admin.routes.index')->with('success', 'Jalur ditambahkan.');
    }

    public function edit(MapRoute $mapRoute): View
    {
        return view('admin.routes.form', $this->formData($mapRoute));
    }

    public function update(MapRouteRequest $request, MapRoute $mapRoute): RedirectResponse
    {
        $mapRoute->update($this->payload($request));

        return redirect()->route('admin.routes.index')->with('success', 'Jalur diperbarui.');
    }

    public function destroy(MapRoute $mapRoute): RedirectResponse
    {
        Gate::authorize('delete-record', $mapRoute);
        $mapRoute->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Jalur dihapus.');
    }

    private function payload(MapRouteRequest $request): array
    {
        $data = $request->safe()->except('coordinates');
        $data['is_published'] = $request->boolean('is_published');
        // Simpan sebagai angka desimal [[lat, lng], ...]
        $data['coordinates'] = array_map(
            fn ($p) => [round((float) $p[0], 7), round((float) $p[1], 7)],
            $request->validated('coordinates')
        );

        return $data;
    }

    private function formData(MapRoute $route): array
    {
        $locations = Location::orderBy('name')->get(['id', 'name', 'latitude', 'longitude']);

        return [
            'route' => $route,
            'locationOptions' => $locations->pluck('name', 'id'),
            'mapConfig' => Setting::mapView() + [
                'others' => $locations->map(fn ($l) => ['name' => $l->name, 'lat' => $l->latitude, 'lng' => $l->longitude]),
                'boundary' => Setting::boundary(),
                'verified' => $route->verification_status === 'verified',
            ],
        ];
    }
}
