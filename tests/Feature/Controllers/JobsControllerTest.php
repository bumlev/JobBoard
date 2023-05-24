<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class JobsControllerTest extends TestCase
{
    /** @test */
    public function searchJobs()
    {
        $request = new Request([
            "country" => "Rwanda",
            "title" => "Frontend"
        ]);

        $jobSeekersController = new JobSeekersController();
        $jobs = $jobSeekersController->searchJobs($request);
    
        $this->assertEquals($jobs , empty(json_decode($jobs)) ? "No jobs found" : $jobs);
    }

    /** @test */
    public function postJob()
    {
        $this->post("/authenticate" , ["email" => "levychris@gmail.com" , "password" => "levy_600"]);

        $data = [
            "title" => "FullStack Developer",
            "content" => "We want a FullStack Developer",
            "skills" => ["12" , '13' , '7' , '8'],
            "countries" => ["186" , "40"]
        ];

        $request = new Request($data);
        $recruitersController =  new RecruitersController();
        $job = $recruitersController->postJob($request);

        if(property_exists($job , 'messages'))
            $this->assertEquals($job->getFormat() , ":messages");
        else{
            $this->assertInstanceOf(Job::class , $job);;
        }
    }

    /** @test */
    public function allJobs(){

        $recruitersController = new RecruitersController();
        $jobs = $recruitersController->index();
        $this->assertInstanceOf(Collection::class , $jobs);
    }
}