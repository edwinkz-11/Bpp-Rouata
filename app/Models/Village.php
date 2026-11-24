<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    use DeletesImages, LogsActivity;

    public array $imageColumns = ['image'];

    protected $fillable = ['name', 'description', 'latitude', 'longitude', 'image'];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function getImageUrlAttribute(): string
    {
        return ImageService::url($this->image);
    }
}
