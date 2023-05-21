<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\JobSeekersController;
use Illuminate\Http\Request;
use stdClass;
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
}