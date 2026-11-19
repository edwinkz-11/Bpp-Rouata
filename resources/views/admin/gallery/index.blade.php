@extends('admin.layouts.app')

@section('title', 'Galeri Dokumentasi')

@section('content')
<div class="card-box mb-3">
    <h2 class="h6 fw-bold mb-3"><i class="bi bi-cloud-arrow-up text-green"></i> Unggah Foto (banyak sekaligus)</h2>
    @if ($activities->isEmpty())
        <p class="text-muted mb-0">Buat dulu sebuah kegiatan di menu <a href="{{ route('admin.activities.create') }}">Kegiatan Pertanian</a>, lalu foto bisa diunggah ke kegiatan tersebut.</p>
    @else
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <x-field name="activity_id" type="select" label="Masukkan ke kegiatan" :options="$activities" :value="request('activity')" :required="true" col="6" />
            <x-field name="caption" label="Keterangan (opsional, sama untuk semua)" col="6" />
            <div class="col-12">
                <input type="file" name="photos[]" multiple required accept="image/jpeg,image/png,image/webp" class="form-control @if ($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" data-multi-preview="#multi-preview">
                @foreach ($errors->get('photos.*') as $msgs)@foreach ($msgs as $m)<div class="invalid-feedback d-block">{{ $m }}</div>@endforeach @endforeach
                @error('photos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div class="form-text">JPG, JPEG, PNG, atau WEBP. Maks 4 MB per foto, 30 foto sekali unggah.</div>
                <div id="multi-preview" class="preview-grid"></div>
            </div>
        </div>
        <button class="btn btn-green mt-3"><i class="bi bi-upload me-1"></i> Unggah</button>
    </form>
    @endif
</div>

<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="activity" class="form-select w-auto" style="max-width:360px"><option value="">Semua kegiatan</option>@foreach ($activities as $id => $title)<option value="{{ $id }}" @selected(request('activity') == $id)>{{ \Illuminate\Support\Str::limit($title, 60) }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Tampilkan</button>
        </form>
        <span class="text-muted small">{{ $photos->total() }} foto</span>
    </div>

    @if ($photos->isEmpty())
        <p class="text-muted mb-0">Belum ada foto.</p>
    @else
        <div class="row g-3">
            @foreach ($photos as $p)
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div class="photo-card">
                        <img src="{{ $p->url }}" alt="" loading="lazy">
                        <div class="p-2">
                            <div class="small text-muted mb-1 text-truncate"><i class="bi bi-calendar-event"></i> {{ $p->activity?->title }}</div>
                            <form method="POST" action="{{ route('admin.gallery.update', $p) }}" class="input-group input-group-sm">
                                @csrf @method('PATCH')
                                <input type="text" name="caption" value="{{ $p->caption }}" maxlength="255" class="form-control" placeholder="Keterangan foto">
                                <button class="btn btn-outline-green" title="Simpan keterangan"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $p) }}" data-confirm="Hapus foto ini?" class="mt-1">@csrf @method('DELETE')<button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i> Hapus foto</button></form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $photos->links() }}</div>
    @endif
</div>
@endsection
