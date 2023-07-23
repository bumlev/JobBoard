<?php
namespace Tests\Feature\Models;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testCountryRelation()
    {
        $user = User::factory()->create();
        $dataProfile = Profile::factory()->make()->toArray();
        $dataProfile["user_id"] = $user->id;

        $profile= Profile::create($dataProfile);
        $relation = $profile->country();
        $this->assertInstanceOf(BelongsTo::class , $relation);
    }

     /** @test */
     public function testUserRelation()
     {
         $user = User::factory()->create();
         $dataProfile = Profile::factory()->make()->toArray();
         $dataProfile["user_id"] = $user->id;
 
         $profile= Profile::create($dataProfile);
         $relation = $profile->user();
         $this->assertInstanceOf(BelongsTo::class , $relation);
     }

     /** @test */
     public function testDegreeRelation()
     {
         $user = User::factory()->create();
         $dataProfile = Profile::factory()->make()->toArray();
         $dataProfile["user_id"] = $user->id;
 
         $profile= Profile::create($dataProfile);
         $relation = $profile->degree();
         $this->assertInstanceOf(BelongsTo::class , $relation);
     }
}