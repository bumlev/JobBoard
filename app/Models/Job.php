<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table="jobs";
    protected $fillable = [
        "title" , "content" , "user_id"
    ];

    protected $hidden = [];
    const SAVE = 1;


    public function users()
    {
        return $this->belongsToMany(User::class , "applied_jobs")->withPivot(["save"]);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

}
