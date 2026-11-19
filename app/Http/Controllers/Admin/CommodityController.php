<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommodityRequest;
use App\Models\Commodity;
use App\Models\Village;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommodityController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $commodities = Commodity::with('village')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->orderBy('category')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.commodities.index', compact('commodities'));
    }

    public function create(): View
    {
        return view('admin.commodities.form', $this->formData(new Commodity(['status' => 'active'])));
    }

    public function store(CommodityRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($file = $request->file('image')) {
            $data['image'] = $this->images->store($file, 'komoditas');
        }

        Commodity::create($data);

        return redirect()->route('admin.commodities.index')->with('success', 'Komoditas ditambahkan.');
    }

    public function edit(Commodity $commodity): View
    {
        return view('admin.commodities.form', $this->formData($commodity));
    }

    public function update(CommodityRequest $request, Commodity $commodity): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image'] = $this->images->replace($request->file('image'), $commodity->image, 'komoditas');

        $commodity->update($data);

        return redirect()->route('admin.commodities.index')->with('success', 'Komoditas diperbarui.');
    }

    public function destroy(Commodity $commodity): RedirectResponse
    {
        $commodity->delete();

        return redirect()->route('admin.commodities.index')->with('success', 'Komoditas dihapus.');
    }

    private function formData(Commodity $commodity): array
    {
        return [
            'commodity' => $commodity,
            'villages' => Village::orderBy('name')->pluck('name', 'id'),
        ];
    }
}
