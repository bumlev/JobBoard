<?php
namespace App\Repositories\JobSeekersCtrlRepos\ApplyJob\Classes;

class IfNotDataOfPivotTable
{
    static function execute($profile , $job_id)
    {
        $job =$profile->jobs()->where('job_id' , $job_id)->first();
        return is_null($job);
    }
}