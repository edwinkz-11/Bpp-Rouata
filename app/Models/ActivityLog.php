<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'user_name', 'action', 'subject_type', 'subject_id',
        'subject_label', 'changes', 'ip_address',
    ];

    public const ACTIONS = ['created' => 'Menambah', 'updated' => 'Mengubah', 'deleted' => 'Menghapus'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Catat satu aksi pengelola. Tidak mencatat apa pun jika tidak ada pengguna yang login (seeder, CLI, pengunjung). */
    public static function record(string $action, Model $model, ?string $changes = null): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $action,
            'subject_type' => class_basename($model),
            'subject_id' => $model->getKey(),
            'subject_label' => mb_substr((string) ($model->title ?? $model->name ?? $model->key ?? ('#' . $model->getKey())), 0, 200),
            'changes' => $changes !== null ? mb_substr($changes, 0, 500) : null,
            'ip_address' => request()?->ip(),
        ]);
    }
}
