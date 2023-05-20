<?php
namespace App\Repositories\Classes;

use App\Models\User;
use App\Repositories\Interfaces\JobsInterface;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class RecruiterJobs implements JobsInterface
{
   public function allJobs()
   {   
      $user = User::find(Sentinel::getUser()->id);
      $jobs = $user->publishedJobs;
      return $jobs;
   }
}