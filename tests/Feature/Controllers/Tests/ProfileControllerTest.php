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
        $profile = $profile->getOriginalContent()["errorValidation"];

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
        $dataProfile["skills"] = ['10' , '11' , '8'];

        $request = Request::create("/createProfile" , "POST" , $dataProfile);
        $request->headers->set('Content-Type' , 'multipart/form-data');
        $request->files->add(['cv' => $dataProfile["cv"] , 'cover_letter' => $dataProfile["cover_letter"]]);

        $jobSeekersController  = new JobSeekersController();
        $profile = $jobSeekersController->createProfile($request);
        $profile = $profile->getData()->profile;
        $this->assertEquals($profile->education , $dataProfile["education"]);
    }

    /** @test */
    public function search_a_profile()
    {
        $data = ["name" => "levsas"];
        $request = new Request($data);
        $recruitersController = new RecruitersController();
        $profiles = $recruitersController->searchProfile($request);
        $profiles = $profiles->getData();
        if(property_exists($profiles , "NoProfile"))
            $this->assertTrue(true);
        else if(property_exists($profiles , "profiles"))
            $this->assertTrue(true);
    }

    /** @test */
    public function search_a_profile_empty_data()
    {
        $data = ["name" => ""];
        $request = new Request($data);
        $recruitersController = new RecruitersController();
        $profiles = $recruitersController->searchProfile($request);
        $profiles = $profiles->getOriginalContent()["errorsValidation"];
        $this->assertEquals($profiles->getFormat() , ":message");
    }

    /** @test */
    public function get_a_profile()
    {
        $recruitersController =  new RecruitersController();
        $profile = $recruitersController->getProfile(2);
        $this->assertTrue(property_exists($profile , "data"));
    }
}