<?php
namespace App\Repositories\RecruitersCtrlRepos\AllJobs\classes;

use App\Models\Job;

class AllJobs 
{
    static function execute()
    {
        $jobs = Job::with("skills" , "countries")->get();
       return !empty($jobs) ?  response()->json(["jobs" => $jobs] , 200) : 
       response()->json(['NoJobs' => __("messages.NoJobs")] , 404);
    }
}
