<?php

namespace App\Models;

use App\Models\Concerns\DeletesImages;
use App\Models\Concerns\LogsActivity;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/** Berita atau artikel yang ditulis sendiri oleh BPP. */
class Post extends Model
{
    use DeletesImages, LogsActivity;

    public array $imageColumns = ['cover_image'];

    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'cover_image', 'status', 'published_at', 'created_by'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Hanya yang berstatus terbit dan tanggalnya sudah tiba (mendukung jadwal terbit). */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getCoverUrlAttribute(): string
    {
        return ImageService::url($this->cover_image);
    }

    public function getSummaryAttribute(): string
    {
        return $this->excerpt ?: Str::limit(strip_tags((string) $this->body), 160);
    }

    public static function makeSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::limit(Str::slug($title) ?: 'berita', 180, '');
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
