<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExtensionWorker;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('web.about', [
            'workers' => ExtensionWorker::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}
