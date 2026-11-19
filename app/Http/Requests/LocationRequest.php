<?php

namespace App\Http\Requests;

use App\Models\Location;
use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(array_keys(Location::CATEGORIES))],
            'village_id' => ['nullable', 'exists:villages,id'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'extra_info' => ['nullable', 'string', 'max:5000'],
            'verification_status' => ['required', Rule::in(array_keys(Location::STATUSES))],
            'is_published' => ['nullable', 'boolean'],
            'image' => ImageService::RULES,
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lokasi', 'category' => 'kategori', 'village_id' => 'desa',
            'latitude' => 'latitude', 'longitude' => 'longitude', 'description' => 'deskripsi',
            'extra_info' => 'informasi tambahan', 'verification_status' => 'status verifikasi', 'image' => 'foto',
        ];
    }
}
