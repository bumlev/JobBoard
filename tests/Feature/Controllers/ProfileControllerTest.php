<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Profile;
use Cartalyst\Support\Collection;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function create_a_profile()
    {   
        $this->post("/authenticate" , ["email" => "levychris@gmail.com" , "password" => "levy_600"]);
        $data = [
            "education" => 'UK University',
            "level" => 4,
            "cv" => "LevyChris_Cv",
            "cover_letter" => "LevyChris_cover_letter",
            "phone" => "+250788910234",
            "country_id" => '186',
            "skills" => ['10' , '11' , '8']    
        ];

        $request = new Request($data);
        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);

        if(property_exists($profile , 'messages'))
            $this->assertEquals($profile->getFormat() , ":message");
        else{
            $this->assertInstanceOf(Profile::class , $profile);
            $this->assertEquals($profile->education , $data["education"]);
        }  
    }

    /** @test */
    public function search_a_profile()
    {
        $data = ["name" => "levsas"];
        $request = new Request($data);
        $recruitersController = new RecruitersController();
        $profiles = $recruitersController->searchProfile($request);
        $profiles = $profiles->pluck("user")->toArray();
        
        $count = count(array_filter($profiles , function($profile) use($data){
            return str_contains($profile["first_name"] , $data["name"]) ||  str_contains($profile["last_name"] , $data["name"]);
        }));

        if($count > 0)
            $this->assertNotEmpty($profiles);
        else{
            $this->assertEmpty($profiles);
        }
    }

    /** @test */
    public function get_a_profile()
    {
        $recruitersController =  new RecruitersController();
        $profile = $recruitersController->getProfile(0);

        if(!empty($profile))
            $this->assertEquals($profile->id , 2);
        else
            $this->assertNull($profile);
    }
}