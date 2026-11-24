<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\View\View;

class PpidController extends Controller
{
    public function index(): View
    {
        $groups = collect(Document::PPID_CATEGORIES)->mapWithKeys(fn ($category) => [
            $category => Document::published()->where('category', $category)->orderByDesc('year')->orderByDesc('id')->get(),
        ]);

        return view('web.ppid', compact('groups'));
    }
}
