<?php
namespace App\Repositories\Classes;

use App\Models\Job;
use App\Repositories\Interfaces\JobsInterface;

class AdminJobs implements JobsInterface
{
    public function allJobs()
    {
        $jobs = Job::all();
        return $jobs;      
    }
}