<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as SentinelRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Role extends SentinelRole
{
    use HasFactory , Notifiable;

    protected $table = "roles";
    protected $fillable = ["slug" , "name" , "permissions"];
    protected $hidden = [];

    const IS_SET_ADMIN = 1;
}
