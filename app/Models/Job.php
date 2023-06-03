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
    const APPLY = 1;

    public function profiles()
    {
        return $this->belongsToMany(Profile::class , "applied_jobs")->withPivot(["save" , "apply"]);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class , "jobs_skills");
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class , "jobs_countries");
    }

    public function matchProfiles($profiles)
    {
        $countries = $this->countries->pluck("name")->toArray();
        $skills = $this->skills->pluck("id")->toArray();
        $matchProfiles = [];

        foreach($profiles as $profile)
        {
            $sklls = $profile->skills->pluck("id")->toArray();
            $difference = array_diff($skills, $sklls);
            $isPartOf = in_array($profile->country->name , $countries);

            if(empty($difference) && $isPartOf)
                array_push($matchProfiles , $profile);
        }
        return $matchProfiles;
    }

}
