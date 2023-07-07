<?php
namespace App\Repositories\JobSeekersCtrlRepos\ApplyJob\Classes;

use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ApplyJob
{

    static public function execute($job_id)
    {
        $user = User::find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(is_null($profile))
            return response()->json(['NoProfile' => __('messages.NoProfile')] , 404);

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile , intval($job_id));

        if($ifNotDataPivotTable){

            $profile->jobs()->attach(intval($job_id) , ["apply" => Job::APPLY]);
            $job = $profile->jobs()->where('job_id' , intval($job_id))->first();
            return response()->json(["appliedjob" => $job] , 201);

        }else{

            $profile->jobs()->updateExistingPivot(intval($job_id) , ["apply" => Job::APPLY]); 
            echo __('messages.AppliedJob');
            $job = $profile->jobs()->where('job_id' , intval($job_id))->first();
            return response()->json(["appliedjob" => $job] , 200);  
        }    
    }

    /// Check if there is a registration  of two data of pivot table
    static private function ifNotDataOfPivotTable($profile , $job_id)
    {
       return IfNotDataOfPivotTable::execute($profile , $job_id);
    }
}