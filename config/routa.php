<?php

return [
    // Kata kunci pencarian Google Maps. Ganti di .env dengan alamat/nama kantor BPP
    // (atau koordinat "lat,lng") setelah dipastikan, mis. ROUTA_MAPS_QUERY="BPP Routa, Konawe".
    'maps_query' => env('ROUTA_MAPS_QUERY', 'Kecamatan Routa, Kabupaten Konawe, Sulawesi Tenggara'),

    // Titik asal untuk tombol rute Google Maps.
    'origins' => [
        ['name' => 'Unaaha', 'note' => 'Ibu kota Kabupaten Konawe', 'query' => 'Unaaha, Konawe, Sulawesi Tenggara'],
        ['name' => 'Kendari', 'note' => 'Ibu kota Provinsi Sulawesi Tenggara', 'query' => 'Kendari, Sulawesi Tenggara'],
        ['name' => 'Kolaka', 'note' => 'Kota pesisir barat Sulawesi Tenggara', 'query' => 'Kolaka, Sulawesi Tenggara'],
    ],
];
