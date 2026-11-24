<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_keamanan_terpasang(): void
    {
        $response = $this->get(route('home'));

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
        $this->assertStringContainsString("frame-ancestors 'self'", $response->headers->get('Content-Security-Policy'));
        $this->assertStringContainsString("object-src 'none'", $response->headers->get('Content-Security-Policy'));
    }

    public function test_csp_mengizinkan_embed_google_maps(): void
    {
        $this->assertStringContainsString('https://www.google.com', $this->get(route('home'))->headers->get('Content-Security-Policy'));
    }

    public function test_hsts_hanya_dikirim_lewat_https(): void
    {
        $this->get(route('home'))->assertHeaderMissing('Strict-Transport-Security');
        $this->get('https://localhost/')->assertHeader('Strict-Transport-Security');
    }

    public function test_csp_bisa_dimatikan_lewat_config(): void
    {
        config(['security.csp_enabled' => false]);

        $this->get(route('home'))->assertHeaderMissing('Content-Security-Policy');
    }
}
