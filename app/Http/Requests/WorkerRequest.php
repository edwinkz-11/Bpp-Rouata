<?php

namespace App\Http\Requests;

use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'position' => ['nullable', 'string', 'max:150'],
            'working_area' => ['nullable', 'string', 'max:200'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
            'photo' => ImageService::RULES,
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama', 'position' => 'jabatan', 'working_area' => 'wilayah binaan',
            'bio' => 'keterangan', 'sort_order' => 'urutan', 'photo' => 'foto',
        ];
    }
}
