<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Village;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $activities = Activity::with('category')->withCount('photos')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest('activity_date')->latest('id')
            ->paginate(12)->withQueryString();

        return view('admin.activities.index', [
            'activities' => $activities,
            'categories' => ActivityCategory::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function create(): View
    {
        return view('admin.activities.form', $this->formData(new Activity(['status' => 'draft'])));
    }

    public function store(ActivityRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['cover_image', 'photos', 'remove_photos']);
        $data['slug'] = Activity::makeSlug($data['title']);
        $data['created_by'] = $request->user()->id;

        if ($file = $request->file('cover_image')) {
            $data['cover_image'] = $this->images->store($file, 'kegiatan');
        }

        $activity = Activity::create($data);
        $this->addPhotos($activity, $request->file('photos', []));

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan ditambahkan.');
    }

    public function edit(Activity $activity): View
    {
        return view('admin.activities.form', $this->formData($activity->load('photos')));
    }

    public function update(ActivityRequest $request, Activity $activity): RedirectResponse
    {
        $data = $request->safe()->except(['cover_image', 'photos', 'remove_photos']);

        if ($data['title'] !== $activity->title) {
            $data['slug'] = Activity::makeSlug($data['title'], $activity->id);
        }

        $data['cover_image'] = $this->images->replace($request->file('cover_image'), $activity->cover_image, 'kegiatan');
        $activity->update($data);

        // Hapus foto dokumentasi yang dicentang (hanya milik kegiatan ini).
        $activity->photos()->whereIn('id', $request->input('remove_photos', []))->get()
            ->each(fn ($photo) => $photo->delete());

        $this->addPhotos($activity, $request->file('photos', []));

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        Gate::authorize('delete-record', $activity);
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Kegiatan dan seluruh fotonya dihapus.');
    }

    private function addPhotos(Activity $activity, array $files): void
    {
        foreach ($files as $file) {
            $activity->photos()->create(['image_path' => $this->images->store($file, 'galeri')]);
        }
    }

    private function formData(Activity $activity): array
    {
        return [
            'activity' => $activity,
            'categories' => ActivityCategory::orderBy('name')->pluck('name', 'id'),
            'villages' => Village::orderBy('name')->pluck('name', 'id'),
        ];
    }
}
