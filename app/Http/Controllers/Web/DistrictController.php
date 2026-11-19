<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\Profile;
use App\Models\Village;
use Illuminate\View\View;

class DistrictController extends Controller
{
    public function show(): View
    {
        return view('web.district', [
            'district' => Profile::district(),
            'villages' => Village::orderBy('name')->get(),
            'commodities' => Commodity::active()->latest('id')->take(6)->get(),
        ]);
    }
}
