<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Village;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $locations = Location::with('village')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('verification_status', $request->input('status')))
            ->latest('id')->paginate(15)->withQueryString();

        return view('admin.locations.index', compact('locations'));
    }

    public function create(Request $request): View
    {
        // Koordinat awal boleh dikirim lewat query string (?lat=..&lng=..).
        $location = new Location(['verification_status' => 'unverified']);

        if (is_numeric($request->input('lat')) && is_numeric($request->input('lng'))) {
            $location->latitude = (float) $request->input('lat');
            $location->longitude = (float) $request->input('lng');
        }

        return view('admin.locations.form', $this->formData($location));
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['is_published'] = $request->boolean('is_published');
        $data['created_by'] = $request->user()->id;

        if ($file = $request->file('image')) {
            $data['image'] = $this->images->store($file, 'lokasi');
        }

        Location::create($data);

        return redirect()->route('admin.locations.index')->with('success', 'Titik lokasi ditambahkan.');
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.form', $this->formData($location));
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['is_published'] = $request->boolean('is_published');
        $data['image'] = $this->images->replace($request->file('image'), $location->image, 'lokasi');

        $location->update($data);

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi diperbarui.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        Gate::authorize('delete-record', $location);
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi dihapus.');
    }

    private function formData(Location $location): array
    {
        $others = Location::query()
            ->when($location->exists, fn ($q) => $q->where('id', '!=', $location->id))
            ->get(['id', 'name', 'latitude', 'longitude'])
            ->map(fn ($l) => ['name' => $l->name, 'lat' => $l->latitude, 'lng' => $l->longitude]);

        return [
            'location' => $location,
            'villages' => Village::orderBy('name')->pluck('name', 'id'),
            'mapConfig' => Setting::mapView() + [
                'others' => $others,
                'boundary' => Setting::boundary(),
                'point' => ($location->latitude !== null && $location->longitude !== null)
                    ? [$location->latitude, $location->longitude] : null,
            ],
        ];
    }
}
