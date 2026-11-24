<?php

namespace App\Http\Requests;

use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slogan' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'ppid_intro' => ['nullable', 'string', 'max:5000'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'hero_image' => ImageService::RULES,
            'struktur_image' => ImageService::RULES,
            'video_tim' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200'],
            'map_center_lat' => ['nullable', 'numeric', 'between:-90,90', 'required_with:map_center_lng'],
            'map_center_lng' => ['nullable', 'numeric', 'between:-180,180', 'required_with:map_center_lat'],
            'map_zoom' => ['nullable', 'integer', 'between:3,18'],
            'boundary_geojson' => [
                'nullable', 'string', 'max:2000000',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $data = json_decode((string) $value, true);

                    if (! is_array($data) || ! isset($data['type'])) {
                        $fail('Batas wilayah harus berupa GeoJSON yang valid.');
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'slogan' => 'slogan', 'footer_text' => 'teks footer', 'ppid_intro' => 'penjelasan PPID', 'instagram_url' => 'tautan Instagram',
            'facebook_url' => 'tautan Facebook', 'youtube_url' => 'tautan YouTube', 'tiktok_url' => 'tautan TikTok',
            'hero_image' => 'foto hero', 'struktur_image' => 'gambar struktur', 'video_tim' => 'video', 'map_center_lat' => 'latitude pusat peta',
            'map_center_lng' => 'longitude pusat peta', 'map_zoom' => 'zoom peta',
        ];
    }
}
