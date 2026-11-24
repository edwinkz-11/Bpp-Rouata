<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = ['date', 'path', 'views'];

    public static function hit(string $path): void
    {
        $date = now()->toDateString();

        $updated = static::where('date', $date)->where('path', $path)->increment('views');

        if (! $updated) {
            try {
                static::create(['date' => $date, 'path' => $path, 'views' => 1]);
            } catch (\Throwable) {
                // Dua permintaan bersamaan membuat baris yang sama: cukup naikkan hitungannya.
                static::where('date', $date)->where('path', $path)->increment('views');
            }
        }
    }

    /** @return array{today:int,week:int,month:int,total:int} */
    public static function summary(): array
    {
        $sum = fn ($q) => (int) $q->sum('views');

        return [
            'today' => $sum(static::where('date', now()->toDateString())),
            'week' => $sum(static::where('date', '>=', now()->subDays(6)->toDateString())),
            'month' => $sum(static::where('date', '>=', now()->subDays(29)->toDateString())),
            'total' => $sum(static::query()),
        ];
    }

    /** Halaman terpopuler 30 hari terakhir. */
    public static function top(int $limit = 5)
    {
        return static::where('date', '>=', now()->subDays(29)->toDateString())
            ->select('path', DB::raw('SUM(views) as total'))
            ->groupBy('path')->orderByDesc('total')->limit($limit)->get();
    }
}
