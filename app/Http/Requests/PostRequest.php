<?php

namespace App\Http\Requests;

use App\Services\ImageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:50000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ImageService::RULES,
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul', 'excerpt' => 'ringkasan', 'body' => 'isi berita', 'status' => 'status',
            'published_at' => 'tanggal terbit', 'cover_image' => 'foto sampul',
        ];
    }
}
