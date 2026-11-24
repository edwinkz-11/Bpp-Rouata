<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TrackPageView;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [SecurityHeaders::class, TrackPageView::class]);

        // Di belakang reverse proxy (Nginx, Cloudflare, load balancer) IP asli pengunjung ada di header X-Forwarded-For.
        // Isi TRUSTED_PROXIES di .env (mis. "*" atau daftar IP) agar rate limiting dan log memakai IP yang benar.
        if ($proxies = env('TRUSTED_PROXIES')) {
            $middleware->trustProxies(at: $proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
