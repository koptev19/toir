<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use Impersonate;

    /**
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'lastname', 
        'email',
        'remember_token',
        'connected',
        'is_admin',
        'toir_session',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'connected' => 'boolean',
        'is_admin' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workshops()
    {
        return $this->belongsToMany(Workshop::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    
}
