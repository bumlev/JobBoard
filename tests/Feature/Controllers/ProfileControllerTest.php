<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Profile;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function create_a_profile_already_exists()
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
    }

    /** @test */
    public function create_a_profile_with_empty_data()
    {
        $this->post("/authenticate" , ["email" => "levychris@gmail.com" , "password" => "levy_600"]);
        $data = [
            "education" => '',
            "level" => 4,
            "cv" => "",
            "cover_letter" => "LevyChris_cover_letter",
            "phone" => "+250788910234",
            "country_id" => '186',
            "skills" => ['10' , '11' , '8']    
        ];

        $request = new Request($data);
        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);
        $this->assertTrue(property_exists($profile , 'messages'));
    }

    /** @test */
    public function create_a_new_profile()
    {
        $data = (new UserFactory())->definition(); 
        $user = Sentinel::registerAndActivate($data);
        $this->post("/authenticate" , ["email" => $data['email'], "password" => $data['password']]);
        $data = [
            "education" => 'UK university',
            "level" => 4,
            "cv" => $data['first_name']."_cv",
            "cover_letter" => $data["last_name"]."_cover_letter",
            "phone" => "+250788910234",
            "country_id" => '186',
            "skills" => ['10' , '11' , '8']    
        ];

        $request = new Request($data);
        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);
        $this->assertInstanceOf(Profile::class , $profile);
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