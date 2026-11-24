<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin | {{ $bpp->name }}</title>
    <link rel="icon" href="{{ $bpp->logo_url }}">
    <link href="{{ asset('vendor/poppins/poppins.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="login-body">
<div class="login-card">
    <div class="text-center mb-4">
        <img src="{{ $bpp->logo_url }}" alt="Logo" width="72" height="72" class="mb-2">
        <h1 class="h5 fw-bold mb-0">{{ $bpp->name }}</h1>
        <small class="text-muted">Masuk ke Dashboard Admin</small>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}" novalidate>
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" autofocus required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Kata sandi</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button class="btn btn-green w-100 py-2">Masuk <i class="bi bi-box-arrow-in-right ms-1"></i></button>
    </form>
    <div class="text-center mt-3"><a href="{{ route('home') }}" class="small text-decoration-none">← Kembali ke website</a></div>
</div>
</body>
</html>
