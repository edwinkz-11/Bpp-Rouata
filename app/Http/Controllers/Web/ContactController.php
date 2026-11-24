<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('web.contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        ContactMessage::create($request->safe()->except('website') + ['status' => 'new']);

        return redirect()->route('contact')->with('sent', true);
    }
}
