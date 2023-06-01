<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Support\Str;

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
        
        if(property_exists($jobs , 'data'))
        {
            $jobs = $jobs->getData()->NoJobs;
            $this->assertEquals($jobs , "Jobs not found ...");  
        }else{
            $this->assertTrue(Str::contains($jobs , $request->input('title')));  
        }
      
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
        $this->assertInstanceOf(Job::class , $job);;
        
    }

    /** @test */
    public function post_job_with_empty_data()
    {
        $this->post("/authenticate" , ["email" => "levychris@gmail.com" , "password" => "levy_600"]);

        $data = [
            "title" => "",
            "content" => "",
            "skills" => ["12" , '13' , '7' , '8'],
            "countries" => ["186" , "40"]
        ];

        $request = new Request($data);
        $recruitersController =  new RecruitersController();
        $job = $recruitersController->postJob($request);
        $this->assertEquals($job->getFormat() , ":message");
    }

    /** @test */
    public function allJobs(){

        $recruitersController = new RecruitersController();
        $jobs = $recruitersController->index();
        $this->assertInstanceOf(Collection::class , $jobs);
    }

    /** @test */
    public function apply_saved_job()
    {
        $this->post("/authenticate" , ["email" => "aristote@gmail.com" , "password" => "aristote_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob(2);
        $this->assertNotEmpty($response->profiles);
    }

    /** @test */
    public function apply_job()
    {
        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => "aristote@gmail.com" , "password" => "aristote_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob($job->id);
        $this->assertNotEmpty($response->profiles);

    }

    /** @test */
    public function apply_job_without_job()
    {
        $this->post("/authenticate" , ["email" => "bumwelevy@yahoo.in" , "password" => "levy_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob(4);
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function applied_jobs_without_profile()
    {
        $this->post("/authenticate" , ["email" => "bumwelevy@yahoo.in" , "password" => "levy_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->appliedJobs();
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function applied_jobs_profile()
    {
        $this->post("/authenticate" , ["email" => "aristote@gmail.com" , "password" => "aristote_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->appliedJobs();
        $this->assertInstanceOf(Collection::class , $response);
    }

    /** @test */
    public function save_job_without_profile()
    {
        $this->post("/authenticate" , ["email" => "bumwelevy@yahoo.in" , "password" => "levy_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob(3);
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function save_job_with_profile()
    {
        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => "aristote@gmail.com" , "password" => "aristote_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob($job->id);
        $this->assertEquals($response->pivot->save , Job::SAVE);
    }

    /** @test */
    public function save_job_saved_job_or_applied_job()
    {
        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => "aristote@gmail.com" , "password" => "aristote_600"]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob(2);
        $response = $response->getData();
        $this->assertEquals($response->savedData , "You already saved or applied that job");
    }
}