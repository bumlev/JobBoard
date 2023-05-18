<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
