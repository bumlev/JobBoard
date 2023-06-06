<?php
namespace App\Repositories\Factories;

use App\Repositories\Classes\AdminJobs;
/*use App\Repositories\Classes\RecruiterJobs;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class JobsFactoriesInterface
{
    public function make()
    {
        $user = Sentinel::getUser();
        $roles = $user->roles->pluck("slug")->toArray();
        
        if(in_array('Admin' , $roles))
        {
            return AdminJobs::class;
        }elseif(in_array('Recruiter' , $roles)){
            return RecruiterJobs::class;
        }  
    }
}*/