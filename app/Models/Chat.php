<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $thread_id
 *
 */
class Chat extends Model
{
    use HasFactory;
    use ChatHelperTrait;

    protected $guarded = [];


}
