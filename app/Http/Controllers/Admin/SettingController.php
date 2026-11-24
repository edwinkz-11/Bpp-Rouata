<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const KEYS = [
        'slogan', 'footer_text', 'ppid_intro', 'instagram_url', 'facebook_url', 'youtube_url', 'tiktok_url',
        'map_center_lat', 'map_center_lng', 'map_zoom', 'boundary_geojson',
    ];

    public function __construct(private ImageService $images)
    {
    }

    public function edit(): View
    {
        return view('admin.settings.edit', ['values' => Setting::allKeyed()]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        foreach (self::KEYS as $key) {
            Setting::put($key, $request->input($key) !== null ? trim((string) $request->input($key)) : null);
        }

        if ($file = $request->file('hero_image')) {
            $old = Setting::get('hero_image');
            Setting::put('hero_image', $this->images->store($file, 'pengaturan'));
            $this->images->delete($old);
        }

        if ($file = $request->file('struktur_image')) {
            $old = Setting::get('struktur_image');
            Setting::put('struktur_image', $this->images->store($file, 'pengaturan'));
            $this->images->delete($old);
        }

        if ($video = $request->file('video_tim')) {
            $old = Setting::get('video_tim');
            Setting::put('video_tim', $video->storeAs('video', Str::uuid().'.'.$video->guessExtension(), 'public'));
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan website disimpan.');
    }
}
