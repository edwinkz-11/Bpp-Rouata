<?php

return [
    'required' => ':attribute wajib diisi.',
    'required_with' => ':attribute wajib diisi bila :values diisi.',
    'string' => ':attribute harus berupa teks.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'url' => ':attribute harus berupa tautan yang valid (diawali https://).',
    'numeric' => ':attribute harus berupa angka.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'boolean' => ':attribute tidak valid.',
    'date' => ':attribute bukan tanggal yang valid.',
    'array' => ':attribute tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'in' => ':attribute yang dipilih tidak valid.',
    'unique' => ':attribute sudah digunakan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus berformat: :values.',
    'uploaded' => ':attribute gagal diunggah (kemungkinan melebihi batas ukuran server).',
    'between' => [
        'numeric' => ':attribute harus di antara :min dan :max.',
    ],
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
        'array' => ':attribute minimal :min item.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string' => ':attribute maksimal :max karakter.',
        'file' => 'Ukuran :attribute maksimal :max KB.',
        'array' => ':attribute maksimal :max item.',
    ],
    'size' => [
        'array' => ':attribute harus berisi :size item.',
    ],
    'password' => [
        'letters' => ':attribute harus mengandung huruf.',
        'mixed' => ':attribute harus mengandung huruf besar dan kecil.',
        'numbers' => ':attribute harus mengandung angka.',
        'symbols' => ':attribute harus mengandung simbol.',
    ],
    'attributes' => [],
];
