<?php

namespace App\Http\Requests;

use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category_id' => ['nullable', 'exists:activity_categories,id'],
            'village_id' => ['nullable', 'exists:villages,id'],
            'location_name' => ['nullable', 'string', 'max:200'],
            'activity_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'cover_image' => ImageService::RULES,
            'photos' => ['nullable', 'array', 'max:20'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_photos' => ['nullable', 'array'],
            'remove_photos.*' => ['integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul kegiatan', 'category_id' => 'kategori', 'village_id' => 'desa',
            'location_name' => 'lokasi', 'activity_date' => 'tanggal', 'description' => 'deskripsi',
            'cover_image' => 'foto sampul', 'photos' => 'foto dokumentasi', 'photos.*' => 'foto dokumentasi',
        ];
    }
}
