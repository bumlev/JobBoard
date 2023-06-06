<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
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
        $currentUser_id = Sentinel::getUser()->id;
        $currentUser = User::find($currentUser_id);
        $data  = self::ValidateData($request);

        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        } 
        $skills = $data["skills"];
        unset($data["skills"]);

        $data["cv"] = self::getUrlFile($data['cv']);
        $data["cover_letter"] = self::getUrlFile($data['cover_letter']);

        $profile  = $currentUser->profile()->create($data);
        $profile->skills()->attach($skills);
        return $profile;
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

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile->id , intval($id));

        if($ifNotDataPivotTable){
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

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile->id , intval($id));

        if($ifNotDataPivotTable){
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
        $file = $request->file();
        $fileName = $currentUser->first_name.Carbon::now()->format("YmdHisv");

        $data = [
            "education" => $request->input("education"),
            "degree_id" => intval($request->input("level")),
            "cv" => [
                "file"=>$file["cv"], 
                "name"=> $fileName."_CV".".".$file["cv"]->getClientOriginalExtension()
            ],
            "cover_letter" => [
                "file" =>$file["cover_letter"], 
                "name"=>$fileName."_CL".".".$file["cover_letter"]->getClientOriginalExtension()
            ],
            "phone" => $request->input("phone"),
            "user_id" => $currentUser->id,
            "country_id" => intval($request->input("country_id")),
            "skills" => array_map("intval" , $request->input("skills"))     
        ];

        $data_rules = [
            "education" => "Required|min:6",
            "degree_id" => "Required|numeric|not_in:0",
            "cv.file" => 'required|mimes:jpeg,png,pdf,docx|max:2048',
            "cv.name" => 'required',
            "cover_letter.file" => 'required|mimes:jpeg,png,pdf,docx|max:2048',
            "cover_letter.name" => 'required',
            "phone" => "Required",
            "user_id" => "Required|unique:profiles,user_id",
            "country_id" => "Required|numeric|not_in:0",
            "skills.*" => "Required|numeric|not_in:0"
        ];

        $data_customise = [
            "user_id.unique" => __("Messages.ProfileExists")
        ];
        $validator  = Validator::make($data , $data_rules , $data_customise);
        return $validator->fails() ? $validator : $data;
    }

    /// Check if there is a registration  of two data of pivot table
    static private function ifNotDataOfPivotTable($profile_id , $job_id)
    {
        $job = Job::with([
            'profiles' => function($query) use($profile_id , $job_id){
                $query->where('profile_id' , $profile_id)
                ->where('job_id' , $job_id);
            }
        ])->find($job_id);
        return $job->profiles->isEmpty();
    }

    // Get the Url of the file
    static private function getUrlFile($file)
    { 
        $path = $file["file"]->storeAs('public/images' , $file["name"]);
        $fileUrl = asset("storage/app/".$path); 
        return $fileUrl;

    }
}