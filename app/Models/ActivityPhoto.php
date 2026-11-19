<?php

namespace App\Models;

use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPhoto extends Model
{
    use DeletesImages;

    public array $imageColumns = ['image_path'];

    protected $fillable = ['activity_id', 'image_path', 'caption'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function getUrlAttribute(): string
    {
        return ImageService::url($this->image_path);
    }
}
