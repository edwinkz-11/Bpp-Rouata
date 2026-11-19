<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Activity;
use App\Models\ActivityPhoto;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Kelola Galeri: unggah banyak foto, ubah keterangan, hapus, dikelompokkan per kegiatan. */
class GalleryController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $photos = ActivityPhoto::with('activity:id,title')
            ->when($request->filled('activity'), fn ($q) => $q->where('activity_id', $request->integer('activity')))
            ->latest('id')->paginate(24)->withQueryString();

        return view('admin.gallery.index', [
            'photos' => $photos,
            'activities' => Activity::orderByDesc('activity_date')->orderByDesc('id')->pluck('title', 'id'),
        ]);
    }

    public function store(GalleryRequest $request): RedirectResponse
    {
        $activity = Activity::findOrFail($request->integer('activity_id'));

        foreach ($request->file('photos') as $file) {
            $activity->photos()->create([
                'image_path' => $this->images->store($file, 'galeri'),
                'caption' => $request->input('caption'),
            ]);
        }

        return redirect()->route('admin.gallery.index', ['activity' => $activity->id])
            ->with('success', count($request->file('photos')) . ' foto berhasil diunggah.');
    }

    public function update(Request $request, ActivityPhoto $photo): RedirectResponse
    {
        $data = $request->validate(['caption' => ['nullable', 'string', 'max:255']], [], ['caption' => 'keterangan']);
        $photo->update($data);

        return back()->with('success', 'Keterangan foto disimpan.');
    }

    public function destroy(ActivityPhoto $photo): RedirectResponse
    {
        $photo->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
