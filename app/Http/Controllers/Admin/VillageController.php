<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRequest;
use App\Models\Village;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VillageController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $villages = Village::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.villages.index', compact('villages'));
    }

    public function create(): View
    {
        return view('admin.villages.form', ['village' => new Village()]);
    }

    public function store(VillageRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($file = $request->file('image')) {
            $data['image'] = $this->images->store($file, 'desa');
        }

        Village::create($data);

        return redirect()->route('admin.villages.index')->with('success', 'Desa ditambahkan.');
    }

    public function edit(Village $village): View
    {
        return view('admin.villages.form', compact('village'));
    }

    public function update(VillageRequest $request, Village $village): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image'] = $this->images->replace($request->file('image'), $village->image, 'desa');

        $village->update($data);

        return redirect()->route('admin.villages.index')->with('success', 'Data desa diperbarui.');
    }

    public function destroy(Village $village): RedirectResponse
    {
        Gate::authorize('manage-structure');
        $village->delete();

        return redirect()->route('admin.villages.index')->with('success', 'Desa dihapus.');
    }
}
