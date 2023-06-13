<?php

namespace Tests\Feature\Controllers\MockTests;

use App\Http\Controllers\JobSeekersController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class JobsControllerMockTest extends TestCase
{
 /** @test */
    public function search_jobs_Mock()
    {
        $request = new Request([
            "country" => "Rwanda",
            "title" => "Frontend"
        ]);
        $jobsControllerMock = Mockery::mock(JobSeekersController::class);
        $jobsControllerMock->shouldReceive("searchJobs")
                            ->with($request)
                            ->andReturn(Collection::class);
        $response = $jobsControllerMock->searchJobs($request);
        $this->assertEquals($response , Collection::class);
        Mockery::close();
    }
}