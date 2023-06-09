<?php
namespace Tests\Feature\Controllers\Tests;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Job;
use App\Models\Profile;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class JobsControllerTest extends TestCase
{
  use RefreshDatabase;

    /** @test */
    public function searchJobs()
    {
        $request = new Request([
            "country" => "Rwanda",
            "title" => "Frontend"
        ]);

        $jobSeekersController = new JobSeekersController();
        $jobs = $jobSeekersController->searchJobs($request);
        $jobs = $this->assertTrue(property_exists($jobs , 'data'));
    }

    /** @test */
    public function searchJobs_empty_data()
    {
        $request = new Request([
            "country" => "Rwanda",
        ]);

        $jobSeekersController = new JobSeekersController();
        $jobs = $jobSeekersController->searchJobs($request);
        $jobs = $jobs->getOriginalContent()["errorValidator"];
        $this->assertEquals($jobs->getFormat() , ":message");
    }

    /** @test */
    public function postJob()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => "levy_600"]);
        $data = [
            "title" => "FullStack Developer",
            "content" => "We want a FullStack Developer",
            "skills" => ["12" , '13' , '7' , '8'],
            "countries" => ["186" , "40"]
        ];

        $request = new Request($data);
        $recruitersController =  new RecruitersController();
        $job = $recruitersController->postJob($request);
        $this->assertTrue(property_exists($job , "data"));   
    }

    /** @test */
    public function post_job_with_empty_data()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => "levy_600"]);

        $data = [
            "title" => "",
            "content" => "",
            "skills" => ["12" , '13' , '7' , '8'],
            "countries" => ["186" , "40"]
        ];

        $request = new Request($data);
        $recruitersController =  new RecruitersController();
        $job = $recruitersController->postJob($request);
        $job = $job->getOriginalContent()["errorsValidation"];
        $this->assertEquals($job->getFormat() , ":message");
    }

    /** @test */
    public function allJobs(){

        $recruitersController = new RecruitersController();
        $jobs = $recruitersController->index();
        $this->assertTrue(property_exists($jobs , "data") );
    }

    /** @test */
    public function apply_job()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $dataProfile = Profile::factory()->make()->toArray(); 
        $currentUser = User::find($user->id);
        $currentUser->profile()->create($dataProfile);

        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob($job->id);
        
        $response = $response->getData()->appliedjob;
        $this->assertEquals($response->pivot->apply , Job::APPLY);

    }

    /** @test */
    public function apply_job_applied_jobs()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $dataProfile = Profile::factory()->make()->toArray(); 
        $currentUser = User::find($user->id);
        $currentUser->profile()->create($dataProfile);

        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob($job->id);
        $response = $jobSeekersController->applyJob($job->id);
        $response = $response->getData()->appliedjob;
        $this->assertEquals($response->pivot->apply , Job::APPLY);

    }
    
    /** @test */
    public function apply_job_without_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->applyJob(4);
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function applied_jobs_without_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->appliedJobs();
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function applied_jobs_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $dataProfile = Profile::factory()->make()->toArray(); 
        $currentUser = User::find($user->id);
        $currentUser->profile()->create($dataProfile);

        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->appliedJobs();
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function save_job_without_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob(3);
        $this->assertTrue(property_exists($response , 'data'));
    }

    /** @test */
    public function save_job_with_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $dataProfile = Profile::factory()->make()->toArray(); 
        $currentUser = User::find($user->id);
        $currentUser->profile()->create($dataProfile);

        $job = Job::factory()->create();
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob($job->id);
        
        $response = json_decode($response->getContent())->savejob;
        $this->assertEquals($response->pivot->save , Job::SAVE);
    }

    /** @test */
    public function save_job_saved_job_or_applied_job()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $dataProfile = Profile::factory()->make()->toArray(); 
        $currentUser = User::find($user->id);
        $currentUser->profile()->create($dataProfile);
        $job = Job::factory()->create();

        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $jobSeekersController = new JobSeekersController();
        $response = $jobSeekersController->saveJob($job->id);
        $response = $jobSeekersController->saveJob($job->id);
        $response = $response->getData();
        $this->assertEquals($response->savedData , "You already saved or applied that job");
    }
}