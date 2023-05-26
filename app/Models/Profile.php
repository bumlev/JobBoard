<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";
    protected $fillable = ["education" , "degree_id" , "cv" , "cover_letter" , "phone" , "user_id" , "country_id"];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class , "profile_skills");
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class , "applied_jobs")->withPivot(["save" , "apply"]);
    }
}
