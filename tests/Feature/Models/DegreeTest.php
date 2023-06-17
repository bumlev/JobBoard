<?php
namespace Tests\Feature\Models;

use App\Models\Degree;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DegreeTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function testProfilesRelation()
    {
        $degree = Degree::find(2);
        $user = User::factory()->create();
        $dataProfile = Profile::factory()->make()->toArray();
        $dataProfile["degree_id"] = $degree->id;
        $dataProfile["user_id"] = $user->id;

        Profile::create($dataProfile);
        $relation = $degree->profiles();
        $this->assertInstanceOf(HasMany::class , $relation);
    }

    
}