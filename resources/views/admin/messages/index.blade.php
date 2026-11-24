@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="status" class="form-select w-auto" aria-label="Status"><option value="">Semua status</option>@foreach (\App\Models\ContactMessage::STATUSES as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach</select>
            <select name="type" class="form-select w-auto" aria-label="Jenis"><option value="">Semua jenis</option>@foreach (\App\Models\ContactMessage::TYPES as $k => $l)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $l }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Tanggal</th><th>Pengirim</th><th>Jenis</th><th>Subjek</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($messages as $m)
                <tr class="{{ $m->status === 'new' ? 'fw-semibold' : '' }}">
                    <td class="small text-nowrap">{{ $m->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $m->name }}</td>
                    <td class="small">{{ \App\Models\ContactMessage::TYPES[$m->type] ?? $m->type }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($m->subject, 60) }}</td>
                    <td><x-status-badge :status="$m->status" /></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.messages.show', $m) }}" class="btn btn-sm btn-outline-green" aria-label="Buka pesan {{ $m->subject }}"><i class="bi bi-eye"></i></a>
                        @can('manage-users')
                        <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" class="d-inline" data-confirm="Hapus pesan dari {{ $m->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" aria-label="Hapus pesan"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada pesan masuk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links() }}
</div>
@endsection
