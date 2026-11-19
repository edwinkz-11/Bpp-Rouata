<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q'));

        $activities = Activity::published()->with(['category', 'photos'])
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->integer('kategori')))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('location_name', 'like', $like));
            })
            ->orderByDesc('activity_date')->orderByDesc('id')
            ->paginate(9)->withQueryString();

        return view('web.activities.index', [
            'activities' => $activities,
            'categories' => ActivityCategory::orderBy('name')->get(),
            'search' => $search,
            'activeCategory' => $request->integer('kategori') ?: null,
        ]);
    }

    public function show(Activity $activity): View
    {
        abort_unless($activity->status === 'published', 404);

        $activity->load(['category', 'photos', 'village']);

        $related = Activity::published()->with('photos')
            ->where('id', '!=', $activity->id)
            ->when($activity->category_id, fn ($q) => $q->where('category_id', $activity->category_id))
            ->latest('activity_date')->take(3)->get();

        return view('web.activities.show', compact('activity', 'related'));
    }
}
