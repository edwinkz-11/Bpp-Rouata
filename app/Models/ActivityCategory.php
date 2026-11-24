<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityCategory extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description'];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'category_id');
    }
}
