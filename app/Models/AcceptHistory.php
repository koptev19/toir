<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcceptHistory extends Model
{
    use SoftDeletes;

    public const STAGE_NEW = 10;
    public const STAGE_DONE = 100;

    /**
     * @var array
     */
    protected $fillable = [
        'accept_id',
        'fio',
        'stage',
        'comment',
        'comment_done',
        'author_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accept()
    {
        return $this->belongsTo(Accept::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'accept_histories_files');
    }

}
