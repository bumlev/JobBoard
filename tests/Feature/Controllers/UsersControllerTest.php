<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\UsersController;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{

    /** @test */
    public function it_store_a_user()
    {
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret',
            'roles' => ['2']
        ];

        $request =  new Request($data);
        $usersController = new UsersController();

        $user = $usersController->store($request);

        if(property_exists($user , 'content'))
            $this->assertEquals($user->getContent() , "The Email already exists !");
        else{
            $this->assertInstanceOf(UserInterface::class , $user);
            $this->assertEquals($user->first_name , $request->input("first_name"));
        }    

    }

    /** @test */
    public function getUsers()
    {
        $usersController  = new UsersController();
        $users = $usersController->index();
        $this->assertInstanceOf(Collection::class , $users);
    }

}