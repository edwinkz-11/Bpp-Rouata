<?php

namespace App\Http\Requests;

use App\Models\Profile;
use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isBpp = $this->route('type') === Profile::TYPE_BPP;

        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:10000'],
            'history' => ['nullable', 'string', 'max:10000'],
            'cover_image' => ImageService::RULES,
        ];

        if ($isBpp) {
            $rules += [
                'institution_name' => ['nullable', 'string', 'max:200'],
                'vision' => ['nullable', 'string', 'max:5000'],
                'mission' => ['nullable', 'string', 'max:10000'],
                'tasks_functions' => ['nullable', 'string', 'max:10000'],
                'address' => ['nullable', 'string', 'max:1000'],
                'phone' => ['nullable', 'string', 'max:30'],
                'email' => ['nullable', 'email', 'max:150'],
                'logo' => ImageService::RULES,
            ];
        } else {
            $rules += [
                'geography' => ['nullable', 'string', 'max:10000'],
                'environment' => ['nullable', 'string', 'max:10000'],
                'society' => ['nullable', 'string', 'max:10000'],
                'agriculture_potential' => ['nullable', 'string', 'max:10000'],
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama', 'institution_name' => 'nama instansi', 'description' => 'deskripsi',
            'history' => 'sejarah', 'vision' => 'visi', 'mission' => 'misi', 'tasks_functions' => 'tugas dan fungsi',
            'address' => 'alamat', 'phone' => 'telepon', 'email' => 'email', 'cover_image' => 'foto utama',
            'logo' => 'logo', 'geography' => 'letak geografis', 'environment' => 'kondisi lingkungan',
            'society' => 'kondisi masyarakat', 'agriculture_potential' => 'potensi pertanian',
        ];
    }
}
