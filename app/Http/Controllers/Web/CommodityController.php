<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommodityController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->input('kategori');
        $category = array_key_exists((string) $category, Commodity::CATEGORIES) ? $category : null;

        $commodities = Commodity::active()->with('village')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('category')->orderBy('name')->get();

        return view('web.commodities', [
            'commodities' => $commodities,
            'activeCategory' => $category,
            'counts' => Commodity::active()->selectRaw('category, count(*) as c')->groupBy('category')->pluck('c', 'category'),
        ]);
    }
}
