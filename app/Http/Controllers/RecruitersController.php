<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Profile;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class RecruitersController extends Controller
{
    
    public function __construct()
    {
        $this->middleware("sentinel");
        $this->middleware("allpermissions:jobs.index" , ["only" => "index"]);
        $this->middleware("allpermissions:jobs.postJob" , ["only" => "postJob"]);
    }

    // Display all avalaible jobs
    public function index()
    {
        $jobs = Job::with("skills" , "countries")->get();
        return $jobs;
    }

    //post a job openings
    public function  postJob(Request $request)
    {
        $dataValidator = self::ValidateData($request);
        if(gettype($dataValidator) == "object")
        {
            $errors = $dataValidator->errors();
            return $errors;
        }

        $skills = $dataValidator["skills"];
        $countries = $dataValidator["countries"];
        $keystoRemove = ["skills" , "countries"];
        $dataValidator = array_diff_key($dataValidator , array_flip($keystoRemove));

        $job = Job::create($dataValidator);
        $job->skills()->attach($skills);
        $job->countries()->attach($countries);
        return $job;
    }

    // find the right Candidates for a jobs
    public function findRightCandidates($id)
    {
        $job = Job::find($id);
        $profiles = Profile::with("country")->get();
        $matchProfiles = $job->matchProfiles($profiles);
        return $matchProfiles;
    }

    // Search a Candidate Profile
    public function searchProfile(Request $request)
    {
        $name = $request->input("name");
        $profiles = Profile::with("user")
                    ->whereHas("user" , function($query) use($name){
                        $query->where("first_name", "LIKE" , '%'.$name."%")
                        ->orWhere("last_name", "LIKE" , '%'.$name."%");
                    })->get();

        return $profiles;
    }

    // Get a profile
    public function getProfile($id)
    {
        $profile = Profile::with("user")->find($id);
        return $profile;
    }

    
    // Validate data
    static private function ValidateData($request)
    {
        $currentUser = Sentinel::getUser();
        $data = [
            "title" => $request->input("title"),
            "content" => $request->input("content"),
            "skills" => array_map("intval" , $request->input("skills")),
            "countries" => array_map("intval" , $request->input("countries")),
            "user_id" => $currentUser->id
        ];

        $data_rules = [
            "title" => "Required|Min:5",
            "content" => "Required",
            "skills.*" => "Required|not_in:0",
            "countries.*" => "Required|not_in:0"
        ];

        return Validator::make($data , $data_rules)->fails() ? Validator::make($data , $data_rules):$data;
    }
}
