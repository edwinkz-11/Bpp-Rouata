<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') | BPP Pertanian Routa</title>
    <link href="{{ asset('vendor/poppins/poppins.css') }}" rel="stylesheet">
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: 'Poppins', system-ui, sans-serif; background: #F8FAF0; color: #1f2937; text-align: center; padding: 1rem; }
        .box { max-width: 460px; }
        .code { font-size: 5rem; font-weight: 700; color: #15803D; line-height: 1; }
        h1 { font-size: 1.3rem; margin: .6rem 0; color: #14532D; }
        p { color: #64748B; }
        a { display: inline-block; margin-top: 1rem; background: #15803D; color: #fff; padding: .6rem 1.4rem; border-radius: 10px; text-decoration: none; font-weight: 500; }
        a:hover { background: #14532D; }
    </style>
</head>
<body>
<div class="box">
    <div class="code">@yield('code')</div>
    <h1>@yield('title')</h1>
    <p>@yield('message')</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</div>
</body>
</html>
