<?php

namespace App\Http\Requests;

use App\Models\Commodity;
use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommodityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in(array_keys(Commodity::CATEGORIES))],
            'village_id' => ['nullable', 'exists:villages,id'],
            'location_note' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'extra_info' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'image' => ImageService::RULES,
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama komoditas', 'category' => 'kategori', 'village_id' => 'desa',
            'location_note' => 'lokasi pengembangan', 'description' => 'deskripsi',
            'extra_info' => 'informasi tambahan', 'image' => 'foto',
        ];
    }
}
