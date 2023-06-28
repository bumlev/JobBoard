<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

use App\Models\Job;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostJob
{
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
        {
            $errors = $data->errors();
            return $errors;
        }

        $job = Job::create($data);
        $job->skills()->attach($data["skills"]);
        $job->countries()->attach($data["countries"]);
        return $job;
    }

    //attributes for data Validation
    static private function attributes(Request $request):array
    {
        $currentUser = Sentinel::getUser();
        return [
            "title" => $request->input("title"),
            "content" => $request->input("content"),
            "skills" => array_map("intval" , $request->input("skills")),
            "countries" => array_map("intval" , $request->input("countries")),
            "user_id" => $currentUser->id
        ];
    }

    // Rules for data Validation
    static private function rules():array
    {
        return [
            "title" => "Required|Min:5",
            "content" => "Required",
            "skills.*" => "Required|numeric|not_in:0",
            "countries.*" => "Required|numeric|not_in:0"
        ];
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        $data = self::attributes($request);
        $data_rules = self::rules();

        $validator = Validator::make($data , $data_rules);
        return $validator->fails() ? $validator : $data;
    }
}
