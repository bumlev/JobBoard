<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Profile;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobSeekersController extends Controller
{
    public function __construct()
    {
        $this->middleware("setlocale");
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
      
        $hasNotProfile = self::HasNotProfile($currentUser->id);
     
        if($hasNotProfile){
            $profile  = $currentUser->profile()->create($data);
            $profile->skills()->attach($skills);
            return $profile;
        }else{
            echo __("messages.ProfileExists");
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

        return empty(json_decode($jobs)) ? response()->json(['NoJobs'=> __("messages.NoJobs")]) : $jobs;
    }

    //Apply a job
    public function applyJob($id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json(['NoProfile' => __('messages.NoProfile')]);

        $isValidateData = self::ifExistsDataOfPivotTable($profile->id , intval($id));

        if($isValidateData){
            $profile->jobs()->attach(intval($id) , ["apply" => Job::APPLY]);
            return $profile->jobs()->where('job_id' , intval($id))->first();
        }else{
            $profile->jobs()->updateExistingPivot(intval($id) , ["apply" => Job::APPLY]); 
            echo __('messages.AppliedJob');
            return $profile->jobs()->where('job_id' , intval($id))->first();   
        }     
    }

    // Display all jobs you applied jobs
    public function appliedJobs(){
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json(['NoProfile' => __('messages.NoProfile')]);
            
        $appliedJobs = $profile->jobs()->where("profile_id", $profile->id)
                                        ->where('apply' , Job::APPLY)->get();
        return $appliedJobs;
    }

    // Save a Job
    public function saveJob($id)
    {
        $user = User::with("profile")->find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json(['NoProfile' => __('messages.NoProfile')]);

        $isValidateData = self::ifExistsDataOfPivotTable($profile->id , intval($id));

        if($isValidateData){
            $profile->jobs()->attach(intval($id), ["save" => Job::SAVE]);
            return $profile->jobs()->where('job_id' , intval($id))->first();
        }else{
            return response()->json(["savedData"=> __("messages.savedData")]);
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

    /// Check if there is a registration  of two data of pivot table
    static private function ifExistsDataOfPivotTable($profile_id , $job_id)
    {
        $job = Job::with([
            'profiles' => function($query) use($profile_id , $job_id){
                $query->where('profile_id' , $profile_id)
                ->where('job_id' , $job_id);
            }
        ])->find($job_id);
        return empty(json_decode($job->profiles));
    }

    // check if a user a profile
    static private function HasNotProfile($user_id)
    {
        $profile = Profile::where("user_id" , $user_id)->first();
        return empty(json_decode($profile));
    }
}