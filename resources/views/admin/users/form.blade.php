@extends('admin.layouts.app')

@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<form method="POST" class="card-box" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" autocomplete="off">
    @csrf @if ($user->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="name" label="Nama" :value="$user->name" :required="true" col="6" />
        <x-field name="email" type="email" label="Email" :value="$user->email" :required="true" col="6" />
        <x-field name="role" type="select" label="Peran" :options="\App\Models\User::ROLES" :value="$user->role" :required="true" col="6" />
        <div class="col-md-6"></div>
        <x-field name="password" type="password" :label="$user->exists ? 'Kata sandi baru (kosongkan jika tidak diganti)' : 'Kata sandi'" :required="! $user->exists" col="6" help="Minimal 8 karakter." />
        <div class="col-md-6">
            <label for="f-password_confirmation" class="form-label fw-semibold">Ulangi kata sandi</label>
            <input type="password" id="f-password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
