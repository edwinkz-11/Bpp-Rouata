<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

/**
 * Catat tambah, ubah, dan hapus data oleh pengelola ke tabel activity_logs.
 * Nilai kolom tidak disimpan, hanya nama kolom yang berubah, jadi kata sandi dan isi data tidak bocor ke log.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => ActivityLog::record('created', $model));

        static::updated(function ($model) {
            $columns = array_keys(array_diff_key($model->getChanges(), ['updated_at' => true, 'remember_token' => true]));

            if ($columns !== []) {
                ActivityLog::record('updated', $model, implode(', ', $columns));
            }
        });

        static::deleted(fn ($model) => ActivityLog::record('deleted', $model));
    }
}
