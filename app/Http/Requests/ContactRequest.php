<?php

namespace App\Http\Requests;

use App\Models\ContactMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email:rfc', 'max:150', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/', 'required_without:email'],
            'type' => ['required', Rule::in(array_keys(ContactMessage::TYPES))],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            // Kolom jebakan (honeypot): manusia tidak melihatnya, bot biasanya mengisinya.
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'Isi email atau nomor telepon agar kami bisa menghubungi Anda.',
            'phone.required_without' => 'Isi email atau nomor telepon agar kami bisa menghubungi Anda.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda +, -, dan kurung.',
            'website.max' => 'Pesan tidak dapat dikirim.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama', 'email' => 'email', 'phone' => 'nomor telepon', 'type' => 'jenis pesan',
            'subject' => 'subjek', 'message' => 'isi pesan',
        ];
    }
}
