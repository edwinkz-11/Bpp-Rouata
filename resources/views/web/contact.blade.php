@extends('layouts.public')

@section('title', 'Kontak dan Pengaduan')
@section('description', 'Kirim pertanyaan, saran, pengaduan, atau permohonan informasi kepada BPP Routa.')

@section('content')
@include('partials.page-header', ['title' => 'Kontak dan Pengaduan', 'subtitle' => 'Sampaikan pertanyaan, saran, pengaduan, atau permohonan informasi.'])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                @if (session('sent'))
                    <div class="alert alert-success" role="status"><i class="bi bi-check-circle-fill me-1"></i> Pesan Anda sudah kami terima. Petugas akan menindaklanjuti lewat kontak yang Anda cantumkan.</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="info-card" novalidate>
                    @csrf
                    {{-- Kolom jebakan untuk bot: disembunyikan dari pengguna dan pembaca layar. --}}
                    <div class="hp-field" aria-hidden="true">
                        <label for="website">Jangan diisi</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" maxlength="100" required autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-semibold">Jenis pesan <span class="text-danger">*</span></label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                @foreach (\App\Models\ContactMessage::TYPES as $key => $label)
                                    <option value="{{ $key }}" @selected(old('type', 'pertanyaan') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" maxlength="150" autocomplete="email" aria-describedby="kontak-help">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Nomor telepon atau WhatsApp</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" maxlength="30" autocomplete="tel" aria-describedby="kontak-help">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12"><div id="kontak-help" class="form-text mt-0">Isi salah satu, email atau nomor telepon, agar kami bisa menghubungi Anda.</div></div>
                        <div class="col-12">
                            <label for="subject" class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" maxlength="150" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label fw-semibold">Isi pesan <span class="text-danger">*</span></label>
                            <textarea id="message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" maxlength="3000" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @error('website')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="small text-muted mt-3 mb-0">Data yang Anda kirim hanya dipakai untuk menindaklanjuti pesan. Lihat <a href="{{ route('privacy') }}">Kebijakan Privasi</a>.</p>
                    <button class="btn btn-green mt-3"><i class="bi bi-send me-1"></i> Kirim Pesan</button>
                </form>
            </div>

            <aside class="col-lg-5" aria-label="Kontak kantor">
                <div class="info-card mb-3">
                    <h2 class="h5 fw-bold mb-3">Kontak Kantor</h2>
                    <ul class="list-unstyled mb-0 footer-contact text-muted">
                        <li><i class="bi bi-geo-alt-fill"></i> <span>{{ $bpp->address ?: 'Alamat belum diisi' }}</span></li>
                        <li><i class="bi bi-telephone-fill"></i> <span>{{ $bpp->phone ?: 'Telepon belum diisi' }}</span></li>
                        <li><i class="bi bi-envelope-fill"></i> <span>{{ $bpp->email ?: 'Email belum diisi' }}</span></li>
                    </ul>
                </div>
                <div class="info-card">
                    <h2 class="h6 fw-bold mb-2">Kanal pengaduan nasional</h2>
                    <p class="small text-muted">Pengaduan pelayanan publik juga dapat disampaikan lewat SP4N-LAPOR!, kanal pengaduan pemerintah.</p>
                    <a href="https://lapor.go.id" target="_blank" rel="noopener" class="btn btn-outline-green btn-sm">Buka lapor.go.id <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
