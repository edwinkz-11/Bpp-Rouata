<?php

namespace App\Models\Concerns;

use App\Services\ImageService;

/**
 * Hapus file foto dari storage saat record dihapus.
 * Model mendefinisikan: public array $imageColumns = ['image'];
 */
trait DeletesImages
{
    public static function bootDeletesImages(): void
    {
        static::deleting(function ($model) {
            $service = app(ImageService::class);

            foreach ($model->imageColumns ?? [] as $column) {
                $service->delete($model->{$column});
            }
        });
    }
}
