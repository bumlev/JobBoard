<?php
namespace Tests\Feature\Controllers\Tests;

use App\Http\Controllers\UsersController;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_store_an_exist_user()
    {
        $user = User::factory()->create();
        $data = [
            'first_name'=> $user->first_name,
            'last_name' =>$user->last_name,
            'email' => $user->email,
            'password' => 'secret',
            'roles' => ['2']
        ];

        $request =  Request::create('/create_user' , 'POST' , $data);
        $usersController = new UsersController();
        $user = $usersController->store($request);
        $user = $user->getOriginalContent()["errorsValidation"];
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
        $user = $user->getData()->createUser;
        $this->assertEquals($user->first_name , $request->input("first_name"));
    }

    /** @test */
    public function getUsers()
    {
        $usersController  = new UsersController();
        $users = $usersController->index();
        $this->assertInstanceOf(Collection::class , $users);
    }

    /** @test */
    public function update_a_user()
    {
        $dataUser = User::factory()->make()->toArray();
        $dataUser["password"] = "levy_600";

        $user = Sentinel::registerAndActivate($dataUser);
        $user->roles()->attach([2]);
        $dataUser["roles"] = [2];

        $this->post("/authenticate" , ["email" => $user->email , "password" => $dataUser["password"]]);

        $request = new Request($dataUser);
        $usersController = new UsersController();
        $response = $usersController->update($request , $user->id);
        $response = $response->getData()->updateUser;
        $this->assertEquals($response->email , $user->email);
    }

    /** @test */
    public function update_a_user_with_empty_data()
    {
        $dataUser = User::factory()->make()->toArray();
        $dataUser["password"] = "levy_600";

        $user = Sentinel::registerAndActivate($dataUser);
        $user->roles()->attach([2]);
        $dataUser["roles"] = [""];

        $this->post("/authenticate" , ["email" => $user->email , "password" => $dataUser["password"]]);

        $request = new Request($dataUser);
        $usersController = new UsersController();
        $response = $usersController->update($request , $user->id);
        $response = $response->getOriginalContent()["errorsValidation"];
        $this->assertEquals($response->getFormat() , ":message");
    }
}