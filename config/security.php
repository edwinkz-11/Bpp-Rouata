<?php

return [
    // Matikan dengan CSP_ENABLED=false di .env jika kebijakan ini mengganggu embed baru yang ditambahkan.
    'csp_enabled' => env('CSP_ENABLED', true),

    // HSTS hanya dikirim lewat HTTPS. Aktifkan hanya jika seluruh situs memang sudah HTTPS.
    'hsts_enabled' => env('HSTS_ENABLED', true),
];
