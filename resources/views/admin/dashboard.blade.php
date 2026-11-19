@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    @foreach ($stats as $s)
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ route($s['route']) }}" class="stat-card">
                <i class="bi {{ $s['icon'] }}"></i>
                <div class="stat-num">{{ number_format($s['value'], 0, ',', '.') }}</div>
                <div class="stat-label">{{ $s['label'] }}</div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold mb-0">Kegiatan Terbaru</h2>
                <a href="{{ route('admin.activities.create') }}" class="btn btn-green btn-sm"><i class="bi bi-plus-lg"></i> Tambah Kegiatan</a>
            </div>
            @if ($latest->isEmpty())
                <p class="text-muted mb-0">Belum ada kegiatan. Klik “Tambah Kegiatan” untuk mulai mendokumentasikan.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach ($latest as $a)
                            <tr>
                                <td><a href="{{ route('admin.activities.edit', $a) }}" class="fw-semibold text-decoration-none">{{ \Illuminate\Support\Str::limit($a->title, 48) }}</a></td>
                                <td>{{ $a->category?->name ?? '-' }}</td>
                                <td class="text-nowrap">{{ $a->activity_date?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td><x-status-badge :status="$a->status" /></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-box">
            <h2 class="h6 fw-bold mb-3">Status Pemetaan</h2>
            @foreach ($mapping as $label => $counts)
                @php $total = $counts->sum(); @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between small fw-semibold"><span>{{ $label }}</span><span>{{ $total }} data</span></div>
                    @if ($total === 0)
                        <div class="small text-muted">Belum ada data.</div>
                    @else
                        <div class="progress mt-1" style="height: 10px">
                            <div class="progress-bar bg-success" style="width: {{ ($counts['verified'] ?? 0) / $total * 100 }}%"></div>
                            <div class="progress-bar bg-warning" style="width: {{ ($counts['unverified'] ?? 0) / $total * 100 }}%"></div>
                            <div class="progress-bar bg-secondary" style="width: {{ ($counts['draft'] ?? 0) / $total * 100 }}%"></div>
                        </div>
                        <div class="small text-muted mt-1">
                            @foreach ($statuses as $k => $name)
                                {{ $name }}: <strong>{{ $counts[$k] ?? 0 }}</strong>@if (! $loop->last) · @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="d-grid gap-2">
                <a href="{{ route('admin.locations.create') }}" class="btn btn-outline-green btn-sm"><i class="bi bi-geo-alt"></i> Tambah Titik Lokasi</a>
                <a href="{{ route('admin.routes.create') }}" class="btn btn-outline-green btn-sm"><i class="bi bi-signpost-split"></i> Tambah Jalur</a>
            </div>
        </div>
    </div>
</div>
@endsection
