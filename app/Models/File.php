<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\File
 */
class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'filename',
        'original_name',
        'mime',
    ];
}