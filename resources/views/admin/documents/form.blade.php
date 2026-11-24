@extends('admin.layouts.app')

@section('title', $document->exists ? 'Edit Dokumen' : 'Tambah Dokumen')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box" action="{{ $document->exists ? route('admin.documents.update', $document) : route('admin.documents.store') }}">
    @csrf @if ($document->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="title" label="Judul Dokumen" :value="$document->title" :required="true" />
        <x-field name="category" type="select" label="Kategori" :options="array_combine(\App\Models\Document::CATEGORIES, \App\Models\Document::CATEGORIES)" :value="$document->category" :required="true" col="6" help="Tiga kategori Informasi (Berkala, Setiap Saat, Serta Merta) tampil di halaman PPID." />
        <x-field name="year" type="number" label="Tahun" :value="$document->year" col="6" />
        <x-field name="description" type="textarea" rows="3" label="Deskripsi singkat" :value="$document->description" />
        <x-field name="file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" :label="$document->exists ? 'Ganti file (kosongkan jika tidak diganti)' : 'File Dokumen'" :required="! $document->exists" col="6" help="PDF, Word, Excel, PowerPoint, atau gambar. Maks. 10 MB." />
        @if ($document->exists)
            <div class="col-md-6"><div class="small text-muted mb-1">File saat ini</div><strong>{{ strtoupper($document->file_ext) }}, {{ $document->size_label }}</strong>, {{ number_format($document->downloads, 0, ',', '.') }} unduhan. Mengganti file mengatur ulang hitungan unduhan.</div>
        @endif
        <x-field name="is_published" type="checkbox" label="Tampilkan di website" :value="$document->is_published" />
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.documents.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
