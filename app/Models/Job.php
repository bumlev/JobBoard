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
        return $this->belongsToMany(Skill::class , "job_skill");
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
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
