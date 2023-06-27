<?php
namespace App\Repositories\JobSeekersCtrlRepos\SaveJob\Classes;

use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SaveJob
{
    static public function execute($job_id)
    {
        $user = User::with("profile")->find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(empty(json_decode($profile)))
            return response()->json(['NoProfile' => __('messages.NoProfile')]);

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile , intval($job_id));

        if($ifNotDataPivotTable){
            $profile->jobs()->attach(intval($job_id), ["save" => Job::SAVE]);
            return $profile->jobs()->where('job_id' , intval($job_id))->first();
        }else{
            return response()->json(["savedData"=> __("messages.savedData")]);
        }
    }
    
    /// Check if there is a registration  of two data of pivot table
    static private function ifNotDataOfPivotTable($profile , $job_id)
    {
        $job =$profile->jobs()->where('job_id' , $job_id)->first();
        return is_null($job);
    }
}