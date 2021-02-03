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

    /**
     * @return Collection
     */
    public function getAvailableWorkshopsAttribute()
    {
        $workshops = collect([]);

        if($this->connected) {
            if($this->is_admin) {
                $workshops = Workshop::all();
            } else {
                $workshops = $this->workshops;
            }
        }

        return $workshops;
    }

    /**
     * @return Collection
     */
    public function getAvailableDepartmentsAttribute()
    {
        $departments = collect([]);

        if($this->connected) {
            if($this->is_admin) {
                $departments = Department::all();
            } else {
                $departments = $this->departments;
            }
        }

        return $departments;
    }

    /**
     * @return Collection
     */
    public function getFullnameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    
}
