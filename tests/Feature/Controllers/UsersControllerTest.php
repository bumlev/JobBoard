<?php
namespace Tests\Feature\Controllers;

use App\Http\Controllers\UsersController;
use App\Models\User;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{

    /** @test */
    public function it_store_an_exist_user()
    {
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret',
            'roles' => ['2']
        ];
        $request =  Request::create('/create_user' , 'POST' , $data);
        $usersController = new UsersController();
        $user = $usersController->store($request);
        $this->assertEquals($user->getFormat() , ":message");
    }

    /** @test */
    public function it_store_a_new_user()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = "levy_600";
        $data['roles'] = [2];
        $request =  Request::create('/create_user' , 'POST' , $data);
        $usersController = new UsersController();
        $user = $usersController->store($request);
        $this->assertInstanceOf(UserInterface::class , $user);
        $this->assertEquals($user->first_name , $request->input("first_name"));
    }

    /** @test */
    public function getUsers()
    {
        $usersController  = new UsersController();
        $users = $usersController->index();
        $this->assertInstanceOf(Collection::class , $users);
    }

}