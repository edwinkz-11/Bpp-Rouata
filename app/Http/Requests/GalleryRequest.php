<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_id' => ['required', 'exists:activities,id'],
            'photos' => ['required', 'array', 'min:1', 'max:30'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['activity_id' => 'kegiatan', 'photos' => 'foto', 'photos.*' => 'foto', 'caption' => 'keterangan'];
    }
}
