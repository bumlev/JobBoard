<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";
    protected $fillable = ["education" , "level_education_id" , "cv" , "cover_letter" , "phone" , "user_id" , "country_id"];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function slills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
