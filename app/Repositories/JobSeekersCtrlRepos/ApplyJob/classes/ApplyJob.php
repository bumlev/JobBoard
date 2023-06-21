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

        if(empty(json_decode($profile)))
            return response()->json(['NoProfile' => __('messages.NoProfile')]);

        $ifNotDataPivotTable = self::ifNotDataOfPivotTable($profile->id , intval($job_id));

        if($ifNotDataPivotTable){

            $profile->jobs()->attach(intval($job_id) , ["apply" => Job::APPLY]);
            return $profile->jobs()->where('job_id' , intval($job_id))->first();
            
        }else{

            $profile->jobs()->updateExistingPivot(intval($job_id) , ["apply" => Job::APPLY]); 
            echo __('messages.AppliedJob');
            return $profile->jobs()->where('job_id' , intval($job_id))->first();
        }    
    }

    /// Check if there is a registration  of two data of pivot table
    static private function ifNotDataOfPivotTable($profile_id , $job_id)
    {
        $job = Job::with([
            'profiles' => function($query) use($profile_id , $job_id){
                $query->where('profile_id' , $profile_id)
                ->where('job_id' , $job_id);
            }
        ])->find($job_id);
        return $job->profiles->isEmpty();
    }
}