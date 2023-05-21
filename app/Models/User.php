<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends EloquentUser
{
    use HasFactory, Notifiable , Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = "users";
    
    protected $fillable = [
        'first_name' , 'last_name', 'email', 'password' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function publishedJobs()
    {
        return $this->hasMany(Job::class);
    }

    /*public function appliedJobs()
    {
        return $this->belongsToMany(Job::class , "applied_jobs")->withPivot(["save"]);
    }*/

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
