<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\SessionsController;
use App\Models\User;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    
    /** @test */
    public function authentication_of_a_user()
    {
        $request  = new Request([
            "email" => "bumwelevy@yahoo.in",
            "password" => "levy_600"
        ]);

        $sessionsController  = new SessionsController();
        $authenticate = $sessionsController->authenticate($request);

        if(gettype($authenticate) == "object")
            $this->assertEquals($request->input("email") , $authenticate->email);
        else
            $this->assertEquals($authenticate , "Your password or email is incorrect");
    
    }
}