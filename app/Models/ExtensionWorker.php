<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

class ExtensionWorker extends Model
{
    use DeletesImages, LogsActivity;

    public array $imageColumns = ['photo'];

    protected $fillable = ['name', 'position', 'working_area', 'bio', 'photo', 'sort_order'];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? ImageService::url($this->photo) : null;
    }
}
