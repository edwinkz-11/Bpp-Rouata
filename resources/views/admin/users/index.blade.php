@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="card-box">
    <div class="toolbar"><span class="text-muted small">Admin: akses penuh. Editor: mengelola konten, tidak bisa mengelola akun.</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Pengguna</a></div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @foreach ($users as $u)
                <tr>
                    <td class="fw-semibold">{{ $u->name }} @if ($u->id === auth()->id())<span class="badge text-bg-light">Anda</span>@endif</td>
                    <td>{{ $u->email }}</td>
                    <td><x-status-badge :status="$u->role" /></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @if ($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" data-confirm="Hapus akun {{ $u->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
