<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = "skills";
    protected $fillable = ["name"];
    protected $hidden = [];

    public function jobs()
    {
        return $this->belongsToMany(Job::class , "jobs_skills");
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class , "profile_skills");
    }
}
