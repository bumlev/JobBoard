<?php
namespace Tests\Feature\Controllers\Tests;

use App\Http\Controllers\JobSeekersController;
use App\Http\Controllers\RecruitersController;
use App\Models\Profile;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
   use RefreshDatabase;

   /** @test */
    public function create_a_profile_already_exists()
    {     
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => $data["password"]]);

        $dataProfile = Profile::factory()->make()->toArray();
        $dataProfile["skills"] = ['10' , '11' , '8'];

        $request = Request::create("/createProfile" , "POST" , $dataProfile);
        $request->headers->set('Content-Type' , 'multipart/form-data');
        $request->files->add(['cv' => $dataProfile["cv"] , 'cover_letter' => $dataProfile["cover_letter"]]);

        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);
        $profile = $jobSeekersController->createProfile($request);

        if(property_exists($profile , 'messages'))
            $this->assertEquals($profile->getFormat() , ":message");
    }

    /** @test */
    public function create_a_profile_with_empty_data()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email , "password" => $data["password"]]);

        $dataProfile = Profile::factory()->make()->toArray();
        $dataProfile["skills"] = ['10' , '' , '8'];

        $request = Request::create("/createProfile" , "POST" , $dataProfile);
        $request->headers->set('Content-Type' , 'multipart/form-data');
        $request->files->add(['cv' => $dataProfile["cv"] , 'cover_letter' => $dataProfile["cover_letter"]]);

        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);
        $this->assertTrue(property_exists($profile , 'messages'));
    }

    /** @test */
    public function create_a_new_profile()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data['password']]);
       
        $dataProfile = Profile::factory()->make()->toArray();
        $dataProfile["level"] = $dataProfile["degree_id"];
        $dataProfile["skills"] = ['10' , '11' , '8'];

        $request = Request::create("/createProfile" , "POST" , $dataProfile);
        $request->headers->set('Content-Type' , 'multipart/form-data');
        $request->files->add(['cv' => $dataProfile["cv"] , 'cover_letter' => $dataProfile["cover_letter"]]);

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
        $profile = $recruitersController->getProfile(2);
        $this->assertTrue(property_exists($profile , "data"));
    }
}