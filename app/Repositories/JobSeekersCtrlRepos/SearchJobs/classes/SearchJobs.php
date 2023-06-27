<?php
namespace App\Repositories\JobSeekersCtrlRepos\SearchJobs\Classes;

use App\Models\Job;
use Illuminate\Http\Request;

class SearchJobs
{
    static public function execute(Request $request)
    {
        $country = $request->input("country");
        $title = $request->input("title");
        $jobs = Job::whereHas('countries' , function($query) use($country){
            $query->where("name" , $country);
        })->where("title" , "LIKE" , "%".$title."%")->get();

        return empty(json_decode($jobs)) ? response()->json(['NoJobs'=> __("messages.NoJobs")]) : $jobs;
    }
}