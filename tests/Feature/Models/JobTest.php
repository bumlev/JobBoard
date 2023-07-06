<?php
namespace Tests\Feature\Models;

use App\Models\Job;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testJobUserRelation()
    {
        $job = Job::factory()->create();
        $relation = $job->user();
        $this->assertInstanceOf(BelongsTo::class , $relation);
    }

    /** @test */
    public function testMatchProfiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->make()->toArray();
        $profile["user_id"] = $user->id;
        $profile = Profile::create($profile);
        $profile->skills()->attach([2]);
        $profiles = Profile::all();
        
        $job = Job::factory()->create();
        $job->skills()->attach([2]);
        $job->countries()->attach([186]);

        $profiles = $job->matchProfiles($profiles);
        $this->assertIsArray($profiles);
    }

    /** @test */
    public function testJobProfilesRelation()
    {
        $job = Job::factory()->create();
        $relation = $job->profiles();
        $this->assertInstanceOf(BelongsToMany::class , $relation);
    }
}