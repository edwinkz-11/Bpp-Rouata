<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Document extends Model
{
    use LogsActivity;

    /** Disk privat (storage/app/private): file hanya bisa diambil lewat route unduh sehingga terhitung. */
    public const DISK = 'local';

    public const CATEGORIES = [
        'Informasi Berkala',
        'Informasi Setiap Saat',
        'Informasi Serta Merta',
        'Laporan Kinerja',
        'Programa Penyuluhan',
        'Peraturan dan SK',
        'Formulir',
        'Lainnya',
    ];

    /** Kategori yang ditampilkan di halaman PPID. */
    public const PPID_CATEGORIES = ['Informasi Berkala', 'Informasi Setiap Saat', 'Informasi Serta Merta'];

    public const EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];

    protected $fillable = [
        'title', 'category', 'year', 'description', 'file_path', 'file_ext',
        'file_size', 'downloads', 'is_published', 'created_by',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::deleting(function (Document $document) {
            Storage::disk(self::DISK)->delete($document->file_path);
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getSizeLabelAttribute(): string
    {
        $bytes = (int) $this->file_size;

        return match (true) {
            $bytes >= 1048576 => number_format($bytes / 1048576, 1, ',', '.') . ' MB',
            $bytes >= 1024 => number_format($bytes / 1024, 0, ',', '.') . ' KB',
            default => $bytes . ' B',
        };
    }

    public function getDownloadNameAttribute(): string
    {
        return (Str::slug($this->title) ?: 'dokumen') . '.' . $this->file_ext;
    }
}
