<?php
namespace Tests\Feature\Models;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testSkillJobsRelation()
    {
        $skill = Skill::find(1);
        $relation = $skill->jobs();
        $this->assertInstanceOf(BelongsToMany::class , $relation);
    }

     /** @test */
     public function testSkillProfilesRelation()
     {
         $skill = Skill::find(1);
         $relation = $skill->profiles();
         $this->assertInstanceOf(BelongsToMany::class , $relation);
     }
}