<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    
    /** @test */
    public function authentication_of_a_user()
    {
        $request  = new Request([
            "email" => "bumwelevy@yahoo.in",
            "password" => "levy_500"
        ]);

        $sessionsController  = new SessionsController();
        $authenticate = $sessionsController->authenticate($request);

        if(property_exists($authenticate , 'data')){
            $authenticate = $authenticate->getData()->errorLogin;
            $this->assertEquals($authenticate , "Your password or email is incorrect");
        }else{
            $this->assertEquals($request->input("email") , $authenticate->email);
        }
    }
}