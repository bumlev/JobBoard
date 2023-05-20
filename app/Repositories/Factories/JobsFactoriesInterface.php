<?php
namespace App\Repositories\Factories;

use App\Repositories\Classes\AdminJobs;
use App\Repositories\Classes\RecruiterJobs;
use App\Repositories\Interfaces\JobsInterface;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class JobsFactoriesInterface
{
    public function make():JobsInterface
    {
        if(Sentinel::check())
        {
            if(Sentinel::getUser()->inRole('Admin'))
            {
                return new AdminJobs();
            }elseif(Sentinel::getUser()->inRole('Recruiter')){
                return new RecruiterJobs();
            }
        }
       
    }
}