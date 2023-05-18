<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobSeekersController extends Controller
{
    public function __construct()
    {
        $this->middleware("sentinel");
        $this->middleware("allpermissions:jobs.createProfile" , ["only" => "createProfile"]);
        $this->middleware("allpermissions:jobs.saveAppliedJob" , ["only" => "saveAppliedJob"]);
    }

    // Create a profile
    public function createProfile(Request $request)
    {
        $currentUser = Sentinel::getUser();
        $currentUser = User::find($currentUser->id);
        $dataValidator  = self::ValidateData($request);

        if(gettype($dataValidator) == "object")
        {
            $errors = $dataValidator->errors();
            return $errors;
        } 

        $skills = $dataValidator["skills"];
        unset($dataValidator["skills"]);

        try {
            $profile  = $currentUser->profile()->create($dataValidator);
            $profile->skills()->attach($skills);
            return $profile;
        } catch (QueryException $e) {
            echo "This is your profile : ";
            return $currentUser->profile;
        }
       
    }


    // Search jobs
    public function searchJobs()
    {
        $skills = [2 ,6 , 17];
        $jobs = Job::with("skills")->get();

        foreach($jobs as $job)
        {
            echo  $job->title.PHP_EOL;
            $sklls = $job->skills->pluck("id")->toArray();
            $difference = array_diff($skills, $sklls);
            if(empty($difference))
                echo "Jobs that match to your profile".PHP_EOL;
            else
                echo "Jobs that doesn't match to your profile".PHP_EOL;
            echo PHP_EOL;
        }
        return;
    }

    //Apply a job
    public function applyJob($id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $job = Job::find($id);

        try {
            $user->appliedJobs()->attach($job->id);
            return $user->appliedJobs;
        } catch (QueryException $e) {
            return response()->json("You applied that Job");
        }     
    }

    // Display all jobs you applied jobs
    public function appliedJobs(){
        $user = User::find(Sentinel::getUser()->id);
        $appliedJobs = $user->appliedJobs;
        return $appliedJobs;
    }

    // Save a Job
    public function saveAppliedJob($id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $user->appliedJobs()->updateExistingPivot($id, ["save" => Job::SAVE]);
        return $user->appliedJobs;
    }

    // Validate data
    static private function ValidateData($request)
    {
        $data = [
            "education" => $request->input("education"),
            "level_education_id" => intval($request->input("level")),
            "cv" => $request->input("cv"),
            "cover_letter" => $request->input("cover_letter"),
            "phone" => $request->input("phone"),
            "user_id" => intval($request->input("user_id")),
            "country_id" => intval($request->input("country_id")),
            "skills" => array_map("intval" , $request->input("skills"))     
        ];

        $data_rules = [
            "education" => "Required|min:6",
            "level_education_id" => "Required",
            "cv" => "Required",
            "cover_letter" => "Required",
            "phone" => "Required",
            "user_id" => "Required",
            "country_id" => "Required",
            "skills.*" => "Required|not_in:0"
        ];

        return Validator::make($data , $data_rules)->fails() ? Validator::make($data , $data_rules) : $data;
    }
}
