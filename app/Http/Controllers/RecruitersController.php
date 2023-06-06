<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\Profile;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecruitersController extends Controller
{  
    public function __construct()
    {
        $this->middleware("setlocale");
        $this->middleware("sentinel");
        $this->middleware("allpermissions:jobs.index" , ["only" => "index"]);
        $this->middleware("allpermissions:jobs.postJob" , ["only" => "postJob"]);
        $this->middleware("allpermissions:jobs.rightCandidates" , ["only" => "findRightCandidates"]);
    }

    // Display all avalaible jobs
    public function index()
    {
       $jobs = Job::all();
       return $jobs;
    }

    //post a job openings
    public function  postJob(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }

        $skills = $data["skills"];
        $countries = $data["countries"];
        $keystoRemove = ["skills" , "countries"];
        $data = array_diff_key($data , array_flip($keystoRemove));

        $job = Job::create($data);
        $job->skills()->attach($skills);
        $job->countries()->attach($countries);
        return $job;
    }

    // Display all postedJobs
    public function postedJobs()
    {
        $currentUser_id = Sentinel::getUser()->id;
        $user = User::find($currentUser_id);
        $postedJobs = $user->publishedJobs;
        return $postedJobs;
    }

    // find the right Candidates for a jobs
    public function findRightCandidates($id)
    {
        $job = Job::find($id);
        $profiles = Profile::all();
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
        $profile = Profile::with("user" , "skills")->find($id);
        return $profile;
    }

    /// chat with a candidate
    public function chatWithCandidate(Request $request)
    {
        $currentUser = Sentinel::getUser();
        $data = self::ValidateDataChat($request);
        
        if(gettype($data) == "object")
            return $data->errors();

        $data["sender_id"] = $currentUser->id;
        $content = $data["content"];
        unset($data["content"]);

        $conversation = self::createChat($data);

        $data = [
            "user_id" => $currentUser->id,
            "conversation_id" => $conversation->id,
            "content" => $content
        ];
        
        $message = Message::create($data);
        return Message::with("user" , "conversation")->where('conversation_id' , $conversation->id)->get();

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
            "skills.*" => "Required|numeric|not_in:0",
            "countries.*" => "Required|numeric|not_in:0"
        ];
        $validator = Validator::make($data , $data_rules);
        return $validator->fails() ? $validator :$data;
    }

    // Create a conversation
    static private function createChat($data)
    {
        $conversation = Conversation::where("sender_id" , $data["sender_id"])
                                    ->where("receiver_id" , $data["receiver_id"])->first();
        if(empty($conversation))
        {
            $conversation = Conversation::create($data);
            return $conversation;
        }
        else
        {
            return Conversation::with("messages")->find($conversation->id);
        }
    }

    // Validate data chat 
    static private function ValidateDataChat($request)
    {
        $data = [
            "receiver_id" => intval($request->input("receiver")),
            "content" => $request->input("content")
        ];
        
        $data_rules = [
            "receiver_id" => "Required|numeric|not_in:0",
            "content" => "Required"
        ];
        $dataValidator = Validator::make($data , $data_rules); 
        return $dataValidator->fails() ? $dataValidator : $data;
    }
}
