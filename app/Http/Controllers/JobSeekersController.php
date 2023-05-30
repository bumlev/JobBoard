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
        $this->middleware("allpermissions:jobs.applyJob" , ["only" => "applyJob"]);
        $this->middleware("allpermissions:jobs.appliedJobs" , ["only" => "appliedJobs"]);
        $this->middleware("allpermissions:jobs.saveJob" , ["only" => "saveJob"]);
    }

    // Create a profile
    public function createProfile(Request $request)
    {
        $currentUser = User::find(Sentinel::getUser()->id);
        $data  = self::ValidateData($request);

        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        } 

        $skills = $data["skills"];
        unset($data["skills"]);
      
        try {
            $profile  = $currentUser->profile()->create($data);
            $profile->skills()->attach($skills);
            return $profile;
        } catch (QueryException $e) {
            echo "Your profile is alredy exists : ";
            return $currentUser->profile;
        }   
    }

    /// Search jobs 
    public function searchJobs(Request $request)
    {
        $country = $request->input("country");
        $title = $request->input("title");
        $jobs = Job::whereHas('countries' , function($query) use($country){
            $query->where("name" , $country);
        })->where("title" , "LIKE" , "%".$title."%")->get();

        return empty(json_decode($jobs)) ? "No jobs found ..." : $jobs;
    }

    //Apply a job
    public function applyJob($id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json("First create your profile");

        try {

            $profile->jobs()->attach(intval($id) , ["apply" => Job::SAVE]);
            return Job::with([
                'profiles'=> function($query) use($profile){
                    $query->where('profile_id' , $profile->id);
                }
            ])->find($id); 

        } catch (QueryException $e) {

            $profile->jobs()->updateExistingPivot(intval($id) , ["apply" => Job::APPLY]); 
            echo "you applied the job : ";
            return Job::with([
                'profiles'=> function($query) use($profile){
                    $query->where('profile_id' , $profile->id);
                }
            ])->find($id);   
        }     
    }

    // Display all jobs you applied jobs
    public function appliedJobs(){
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json("First create your profile");

        $appliedJobs = Job::whereHas('profiles' , function($query) use($profile){
            $query->where('profile_id' , $profile->id)
                ->where('apply' , Job::APPLY);
        })->get();
        return $appliedJobs;
    }

    // Save a Job
    public function saveJob($id)
    {
        $user = User::with("profile")->find(Sentinel::getUser()->id);
        $profile = $user->profile;
        if(empty(json_decode($profile)))
            return response()->json("First create your profile");
        
        try {
            $profile->jobs()->attach(intval($id), ["save" => Job::SAVE]);
            return $profile->jobs;
        } catch (QueryException $e) {
           return response()->json("You already saved or applied that job");
        }
    }

    // Validate data
    static private function ValidateData($request)
    {
        $currentUser = Sentinel::getUser();
        $data = [
            "education" => $request->input("education"),
            "degree_id" => intval($request->input("level")),
            "cv" => $request->input("cv"),
            "cover_letter" => $request->input("cover_letter"),
            "phone" => $request->input("phone"),
            "user_id" => $currentUser->id,
            "country_id" => intval($request->input("country_id")),
            "skills" => array_map("intval" , $request->input("skills"))     
        ];

        $data_rules = [
            "education" => "Required|min:6",
            "degree_id" => "Required|not_in:0",
            "cv" => "Required",
            "cover_letter" => "Required",
            "phone" => "Required",
            "user_id" => "Required",
            "country_id" => "Required|not_in:0",
            "skills.*" => "Required|not_in:0"
        ];
        return Validator::make($data , $data_rules)->fails() ? Validator::make($data , $data_rules) : $data;
    }
}
