<?php

return [
    // Matikan dengan KEMENTAN_NEWS_ENABLED=false di .env
    'news_enabled' => env('KEMENTAN_NEWS_ENABLED', true),
    'base_url' => 'https://pertanian.go.id',
    // Halaman daftar berita (kategori "Berita") lalu beranda sebagai cadangan.
    'news_sources' => [
        'https://pertanian.go.id/index.php/news/all/2',
        'https://pertanian.go.id/',
    ],
    'cache_minutes' => (int) env('KEMENTAN_NEWS_CACHE_MINUTES', 180),
    'timeout' => 8,
];
