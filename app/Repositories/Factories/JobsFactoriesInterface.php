<?php
namespace App\Repositories\Factories;

use App\Repositories\Classes\AdminJobs;
use App\Repositories\Classes\RecruiterJobs;
use App\Repositories\Interfaces\JobsInterface;
//use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class JobsFactoriesInterface
{
    public function make():JobsInterface
    {
        $user = \Cartalyst\Sentinel\Laravel\Facades\Sentinel::getUser();
        if($user)
        {
            if($user->inRole('Admin'))
            {
                return new AdminJobs();
            }elseif($user->inRole('Recruiter')){
                return new RecruiterJobs();
            }
        }
       
    }
}