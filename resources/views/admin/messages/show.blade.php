@extends('admin.layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="card-box">
    <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $message->subject }}</h2>
            <div class="small text-muted">{{ \App\Models\ContactMessage::TYPES[$message->type] ?? $message->type }} &middot; {{ $message->created_at->translatedFormat('l, d F Y H:i') }}</div>
        </div>
        <x-status-badge :status="$message->status" />
    </div>
    <dl class="row mb-3">
        <dt class="col-sm-3">Nama</dt><dd class="col-sm-9">{{ $message->name }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">@if ($message->email)<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>@else - @endif</dd>
        <dt class="col-sm-3">Telepon</dt><dd class="col-sm-9">{{ $message->phone ?: '-' }}</dd>
    </dl>
    <div class="p-3 bg-light rounded-3 mb-4" style="white-space: pre-line">{{ $message->message }}</div>

    <form method="POST" action="{{ route('admin.messages.update', $message) }}" class="d-flex flex-wrap gap-2 align-items-center">
        @csrf @method('PUT')
        <label for="status" class="fw-semibold">Ubah status:</label>
        <select id="status" name="status" class="form-select w-auto">@foreach (\App\Models\ContactMessage::STATUSES as $k => $l)<option value="{{ $k }}" @selected($message->status === $k)>{{ $l }}</option>@endforeach</select>
        <button class="btn btn-green">Simpan</button>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-light">Kembali</a>
    </form>
</div>
@endsection
