<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = "countries";

    protected $fillable = ["name"];
    protected $hidden = [];

    public function jobs()
    {
        return $this->belongsToMany(Job::class , "jobs_countries");
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
