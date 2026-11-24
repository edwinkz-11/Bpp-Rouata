@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="card-box">
    <p class="text-muted small">Catatan siapa menambah, mengubah, atau menghapus data. Yang disimpan hanya nama kolom yang berubah, bukan isinya.</p>
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="action" class="form-select w-auto" aria-label="Aksi"><option value="">Semua aksi</option>@foreach (\App\Models\ActivityLog::ACTIONS as $k => $l)<option value="{{ $k }}" @selected(request('action') === $k)>{{ $l }}</option>@endforeach</select>
            <select name="type" class="form-select w-auto" aria-label="Jenis data"><option value="">Semua data</option>@foreach ($types as $t)<option @selected(request('type') === $t)>{{ $t }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead><tr><th>Waktu</th><th>Petugas</th><th>Aksi</th><th>Data</th><th>Kolom berubah</th><th>IP</th></tr></thead>
            <tbody>
            @forelse ($logs as $l)
                <tr>
                    <td class="small text-nowrap">{{ $l->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $l->user_name ?: '-' }}</td>
                    <td>{{ \App\Models\ActivityLog::ACTIONS[$l->action] ?? $l->action }}</td>
                    <td><span class="badge text-bg-light border">{{ $l->subject_type }}</span> {{ \Illuminate\Support\Str::limit($l->subject_label, 50) }}</td>
                    <td class="small text-muted">{{ $l->changes ?: '-' }}</td>
                    <td class="small text-muted">{{ $l->ip_address ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada aktivitas tercatat.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
