<?php

namespace App\Models;

use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

class ExtensionWorker extends Model
{
    use DeletesImages;

    public array $imageColumns = ['photo'];

    protected $fillable = ['name', 'position', 'working_area', 'bio', 'photo', 'sort_order'];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? ImageService::url($this->photo) : null;
    }
}
