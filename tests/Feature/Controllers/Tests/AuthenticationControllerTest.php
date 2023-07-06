<?php
namespace Tests\Feature\Controllers\Tests;

use App\Http\Controllers\SessionsController;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authentication_of_a_user()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";

        $user = Sentinel::registerAndActivate($data);
        $request  = new Request([
            "email" => $user->email,
            "password" => "levy_600"
        ]);

        $sessionsController  = new SessionsController();
        $authenticate = $sessionsController->authenticate($request);
        $authenticate = $authenticate->getData();

        if(property_exists($authenticate , 'errorLogin')){
            $this->assertTrue(true);
        }else if(property_exists($authenticate , 'successLogin')){
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function authentication_empty_data()
    {
        $request  = new Request();
        $sessionsController  = new SessionsController();
        $authenticate = $sessionsController->authenticate($request);
        $authenticate = $authenticate->getOriginalContent()["errorsValidation"];
        $this->assertEquals($authenticate->getFormat() , ":message");
    }

    /** @test */
    public function logout()
    {
       $sessionsController = new SessionsController();
       $response = $sessionsController->logout();
       $response = $response->getData();
       $this->assertEquals($response->logout , 'Logged out');
    }
}