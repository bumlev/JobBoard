<?php
namespace App\Repositories\JobSeekersCtrlRepos\SaveJob\Classes;

use App\Models\Job;
use App\Models\User;
use App\Repositories\JobSeekersCtrlRepos\ApplyJob\Classes\IfNotDataOfPivotTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SaveJob
{
    static public function execute($job_id)
    {
        $user = User::with("profile")->find(Sentinel::getUser()->id);
        $profile = $user->profile;

        if(is_null($profile))
            return response()->json(['NoProfile' => __('messages.NoProfile')] , 404);

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile , $job_id);

        if($ifNotDataPivotTable){

            $profile->jobs()->attach($job_id, ["save" => Job::SAVE]);
            $job = $profile->jobs()->where('job_id' , $job_id)->first();
            return response()->json(["savejob" => $job] , 201);

        }else{
            return response()->json(["savedData"=> __("messages.savedData")] , 409);
        }
    }
    
    /// Check if there is a registration  of two data of pivot table
    static private function ifNotDataOfPivotTable($profile , $job_id)
    {
        return IfNotDataOfPivotTable::execute($profile , $job_id);
    }
}