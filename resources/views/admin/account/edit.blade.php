@extends('admin.layouts.app')

@section('title', 'Akun Saya')

@section('content')
<form method="POST" action="{{ route('admin.account.update') }}" class="card-box" style="max-width: 720px" autocomplete="off">
    @csrf @method('PUT')
    <div class="row g-3">
        <x-field name="name" label="Nama" :value="$user->name" :required="true" col="6" />
        <x-field name="email" type="email" label="Email" :value="$user->email" :required="true" col="6" />
        <div class="col-12"><hr class="my-1"><div class="small text-muted">Ganti kata sandi (kosongkan jika tidak ingin mengganti)</div></div>
        <x-field name="current_password" type="password" label="Kata sandi saat ini" />
        <x-field name="password" type="password" label="Kata sandi baru" col="6" help="Minimal 8 karakter." />
        <div class="col-md-6">
            <label for="f-password_confirmation" class="form-label fw-semibold">Ulangi kata sandi baru</label>
            <input type="password" id="f-password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>
    </div>
    <div class="mt-4"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button></div>
</form>
@endsection
