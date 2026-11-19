@extends('layouts.public')

@section('title', 'Potensi Pertanian')

@section('content')
@include('partials.page-header', ['title' => 'Potensi Pertanian', 'subtitle' => 'Komoditas pertanian, perkebunan, hortikultura, dan peternakan di Kecamatan Routa.'])

<section class="section">
    <div class="container">
        <div class="chip-row mb-4">
            <a href="{{ route('commodities.index') }}" class="chip {{ ! $activeCategory ? 'active' : '' }}">Semua</a>
            @foreach (\App\Models\Commodity::CATEGORIES as $cat)
                <a href="{{ route('commodities.index', ['kategori' => $cat]) }}" class="chip {{ $activeCategory === $cat ? 'active' : '' }}">
                    {{ $cat }} @if (($counts[$cat] ?? 0) > 0)<span class="count">{{ $counts[$cat] }}</span>@endif
                </a>
            @endforeach
        </div>

        @if ($commodities->isEmpty())
            <x-empty icon="bi-flower3" title="Belum ada data komoditas" text="Data potensi pertanian ditambahkan oleh petugas BPP melalui dashboard admin." />
        @else
            <div class="row g-4">
                @foreach ($commodities as $c)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card commodity-card h-100">
                            <img src="{{ $c->image_url }}" class="card-img-top" alt="{{ $c->name }}" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <span class="badge badge-soft align-self-start mb-2">{{ $c->category }}</span>
                                <h2 class="h5 fw-bold">{{ $c->name }}</h2>
                                <p class="small text-muted">{{ $c->description ? \Illuminate\Support\Str::limit($c->description, 130) : 'Deskripsi belum diisi.' }}</p>
                                <div class="small text-muted mb-3"><i class="bi bi-geo-alt-fill text-green"></i> {{ $c->location_text ?: 'Lokasi pengembangan belum diisi' }}</div>
                                <button type="button" class="btn btn-outline-green btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#commodityModal"
                                        data-name="{{ $c->name }}" data-category="{{ $c->category }}" data-image="{{ $c->image_url }}"
                                        data-description="{{ $c->description }}" data-location="{{ $c->location_text }}" data-extra="{{ $c->extra_info }}">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<div class="modal fade" id="commodityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold" id="cm-name"></h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
            <div class="modal-body">
                <img id="cm-image" class="img-fluid rounded-3 mb-3 w-100 cover-img" alt="">
                <span class="badge badge-soft mb-2" id="cm-category"></span>
                <p id="cm-description" class="text-muted"></p>
                <p class="mb-1"><i class="bi bi-geo-alt-fill text-green"></i> <strong>Lokasi pengembangan:</strong> <span id="cm-location"></span></p>
                <div id="cm-extra-wrap" class="mt-3"><strong>Informasi tambahan</strong><p id="cm-extra" class="text-muted mb-0"></p></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('commodityModal')?.addEventListener('show.bs.modal', function (e) {
        const d = e.relatedTarget.dataset;
        document.getElementById('cm-name').textContent = d.name;
        document.getElementById('cm-category').textContent = d.category;
        document.getElementById('cm-image').src = d.image;
        document.getElementById('cm-description').textContent = d.description || 'Deskripsi belum diisi.';
        document.getElementById('cm-location').textContent = d.location || 'belum diisi';
        document.getElementById('cm-extra').textContent = d.extra || '';
        document.getElementById('cm-extra-wrap').classList.toggle('d-none', !d.extra);
    });
</script>
@endpush
