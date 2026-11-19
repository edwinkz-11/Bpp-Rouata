@props(['status'])
@php
    $map = [
        'verified' => ['success', 'Terverifikasi'],
        'unverified' => ['warning text-dark', 'Belum Terverifikasi'],
        'draft' => ['secondary', 'Draft'],
        'published' => ['success', 'Terbit'],
        'active' => ['success', 'Aktif'],
        'inactive' => ['secondary', 'Nonaktif'],
        'admin' => ['danger', 'Admin'],
        'editor' => ['info text-dark', 'Editor'],
    ];
    [$class, $label] = $map[$status] ?? ['secondary', $status];
@endphp
<span class="badge text-bg-{{ $class }}">{{ $label }}</span>
