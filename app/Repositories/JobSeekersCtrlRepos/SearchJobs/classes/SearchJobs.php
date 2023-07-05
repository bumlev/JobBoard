<?php
namespace App\Repositories\JobSeekersCtrlRepos\SearchJobs\Classes;

use App\Models\Job;
use App\Repositories\HandleError\ErrorsNotMatchKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchJobs
{
    static public function execute(Request $request)
    {
        // Validate data 
        $data = ["country"=> $request->input("country") , "title" => $request->input("title")];
        $data_rules = ["country"=> "Required" , "title" => "Required"];
        
        $validator = Validator::make($data , $data_rules)
        ->after(function($validator) use($request , $data){
            //Add errors message if keys of request don't match to keys of defined attributes
            ErrorsNotMatchKeys::execute($request , $data , $validator);
        });

        if($validator->fails())
            return $validator->errors();

        // Search jobs by using country and title
        $jobs = Job::whereHas('countries' , function($query) use($data){
            $query->where("name" , $data["country"]);
        })->where("title" , "LIKE" , "%".$data["title"]."%")->get();

        return empty(json_decode($jobs)) ? response()->json(['NoJobs'=> __("messages.NoJobs")]) : $jobs;
    }
}