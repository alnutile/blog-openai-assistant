<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $intro
 * @property string $thread_id
 */
class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chapters() : HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}
