<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Mengelola dua profil: BPP (type=bpp) dan Kecamatan Routa (type=kecamatan). */
class ProfileController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function edit(string $type): View
    {
        return view('admin.profile.edit', [
            'type' => $type,
            'profile' => Profile::forType($type),
        ]);
    }

    public function update(ProfileRequest $request, string $type): RedirectResponse
    {
        $profile = Profile::forType($type);
        $data = $request->safe()->only(Profile::FIELDS[$type]);

        $profile->fill($data);
        $profile->type = $type;
        $profile->cover_image = $this->images->replace($request->file('cover_image'), $profile->cover_image, 'profil');

        if ($type === Profile::TYPE_BPP) {
            $profile->logo = $this->images->replace($request->file('logo'), $profile->logo, 'profil');
        }

        $profile->save();

        return redirect()->route('admin.profile.edit', $type)->with('success', 'Profil berhasil disimpan.');
    }
}
