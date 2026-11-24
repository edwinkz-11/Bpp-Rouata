<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Activity extends Model
{
    use DeletesImages, LogsActivity;

    public array $imageColumns = ['cover_image'];

    protected $fillable = [
        'category_id', 'village_id', 'created_by', 'title', 'slug', 'description',
        'location_name', 'activity_date', 'cover_image', 'status',
    ];

    protected function casts(): array
    {
        return ['activity_date' => 'date'];
    }

    protected static function booted(): void
    {
        // Baris foto ikut terhapus oleh foreign key (cascade); filenya dihapus di sini.
        static::deleting(function (Activity $activity) {
            $service = app(ImageService::class);
            $activity->photos->each(fn (ActivityPhoto $p) => $service->delete($p->image_path));
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ActivityPhoto::class)->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Foto sampul; jika kosong pakai foto dokumentasi pertama; jika kosong pakai placeholder. */
    public function getCoverUrlAttribute(): string
    {
        $path = $this->cover_image ?: $this->photos->first()?->image_path;

        return ImageService::url($path);
    }

    public function getHasImageAttribute(): bool
    {
        return (bool) ($this->cover_image ?: $this->photos->first());
    }

    public static function makeSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::limit(Str::slug($title) ?: 'kegiatan', 180, '');
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
