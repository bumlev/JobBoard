<?php
namespace Tests\Feature\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testJobsRelation()
    {
        $country = Country::find(1);
        $relation = $country->jobs();
        $this->assertInstanceOf(BelongsToMany::class , $relation);
    }

    /** @test */
    public function testCountryProfilesRelation()
    {
        $country = Country::find(1);
        $relation = $country->profiles();
        $this->assertInstanceOf(HasMany::class , $relation);
    }
}