@extends('layouts.public')

@section('title', 'PPID')
@section('description', 'Layanan informasi publik BPP Pertanian Routa: informasi berkala, informasi setiap saat, dan informasi serta merta.')

@section('content')
@include('partials.page-header', ['title' => 'PPID', 'subtitle' => 'Pejabat Pengelola Informasi dan Dokumentasi: layanan keterbukaan informasi publik.'])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="info-card mb-4">
                    <h2 class="h5 fw-bold">Tentang Layanan Informasi Publik</h2>
                    @if (! empty($site['ppid_intro']))
                        <div class="text-muted prose">{!! nl2br(e($site['ppid_intro'])) !!}</div>
                    @else
                        <p class="placeholder-note mb-0">Penjelasan layanan informasi publik belum diisi petugas. Admin dapat mengisinya di Pengaturan Website.</p>
                    @endif
                </div>

                @foreach ($groups as $category => $documents)
                    <h2 class="h5 fw-bold mb-3 mt-4">{{ $category }}</h2>
                    @if ($documents->isEmpty())
                        <p class="text-muted small">Belum ada dokumen pada kategori ini.</p>
                    @else
                        <div class="list-group shadow-soft mb-2">
                            @foreach ($documents as $d)
                                <a href="{{ route('documents.download', $d) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                    <i class="bi bi-file-earmark-text fs-4 text-green" aria-hidden="true"></i>
                                    <span class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ $d->title }}</span>
                                        <small class="text-muted">{{ $d->year ?: 'Tanpa tahun' }} &middot; {{ strtoupper($d->file_ext) }}, {{ $d->size_label }}</small>
                                    </span>
                                    <i class="bi bi-download" aria-hidden="true"></i><span class="visually-hidden">Unduh</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>

            <aside class="col-lg-4" aria-label="Permohonan informasi">
                <div class="info-card">
                    <h2 class="h5 fw-bold mb-3">Ajukan Permohonan</h2>
                    <p class="small text-muted">Informasi yang Anda cari belum ada di daftar? Kirim permohonan informasi atau pengaduan lewat formulir kontak.</p>
                    <a href="{{ route('contact') }}" class="btn btn-green w-100 mb-2"><i class="bi bi-envelope me-1"></i> Kirim Permohonan</a>
                    <a href="https://lapor.go.id" target="_blank" rel="noopener" class="btn btn-outline-green w-100">SP4N-LAPOR! <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                </div>
                <div class="info-card mt-3">
                    <h2 class="h6 fw-bold mb-2">Kontak Kantor</h2>
                    <ul class="list-unstyled small mb-0 footer-contact text-muted">
                        <li><i class="bi bi-geo-alt-fill"></i> <span>{{ $bpp->address ?: 'Alamat belum diisi' }}</span></li>
                        <li><i class="bi bi-telephone-fill"></i> <span>{{ $bpp->phone ?: 'Telepon belum diisi' }}</span></li>
                        <li><i class="bi bi-envelope-fill"></i> <span>{{ $bpp->email ?: 'Email belum diisi' }}</span></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
