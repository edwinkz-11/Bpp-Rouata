<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(Document::CATEGORIES)],
            'year' => ['nullable', 'integer', 'between:2000,' . (date('Y') + 1)],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_published' => ['required', 'boolean'],
            // Jenis file dicek dari isi file, bukan hanya dari nama. Maks. 10 MB.
            'file' => [$isCreate ? 'required' : 'nullable', 'file', 'mimes:' . implode(',', Document::EXTENSIONS), 'max:10240'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul dokumen', 'category' => 'kategori', 'year' => 'tahun',
            'description' => 'deskripsi', 'is_published' => 'status', 'file' => 'file dokumen',
        ];
    }
}
