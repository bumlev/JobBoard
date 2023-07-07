<?php
namespace App\Repositories\JobSeekersCtrlRepos\SearchJobs\Classes;

use App\Models\Job;
use Illuminate\Http\Request;

class SearchJobs
{
    static public function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
            return response()->json(["errorValidator" => $data->errors()] , 422);

        // Search jobs by using country and title
        $jobs = Job::whereHas('countries' , function($query) use($data){
            $query->where("name" , $data["country"]);
        })->where("title" , "LIKE" , "%".$data["title"]."%")->get();

        return $jobs->isEmpty() ? response()->json(['NoJobs'=> __("messages.NoJobs")] , 404) : 
        response()->json(["jobs" => $jobs] , 200);
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        return ValidatorData::execute($request);
    }
}