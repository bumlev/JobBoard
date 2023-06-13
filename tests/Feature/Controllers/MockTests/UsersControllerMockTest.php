<?php
namespace Tests\Feature\Controllers\MockTests;

use App\Http\Controllers\UsersController;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class UsersControllerMocTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_store_an_exist_user_Mock()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = "levy_600";
        $data['roles'] = [2];
       
        $request =  Request::create('/create_user' , 'POST' , $data);
        $usersControllerMock = Mockery::mock(UsersController::class);

        $usersControllerMock->shouldReceive('store')
        ->once()
        ->with($request)
        ->andReturn(":message");
        $user = $usersControllerMock->store($request);
        $this->assertEquals($user, ":message");
        Mockery::close();
    }

    /** @test */
    public function it_store_a_new_user_Mock()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $data["roles"] = [2];
        $request =  Request::create('/create_user' , 'POST' , $data);
        $usersControllerMock = Mockery::mock(UsersController::class);
        $userInterfaceMock = Mockery::mock(UserInterface::class);
        $usersControllerMock->shouldReceive('store')
        ->once()
        ->with($request)
        ->andReturn($userInterfaceMock);
        $response = $usersControllerMock->store($request);
        $this->assertEquals($response , $userInterfaceMock);   
    }

    /** @test */
    public function getUsers_Mock()
    {
        $usersControllerMock = Mockery::mock(UsersController::class);
        $usersControllerMock->shouldReceive("index")
                            ->andReturn(Collection::class);

        $response = $usersControllerMock->index();
        $this->assertEquals($response , Collection::class);
        Mockery::close();
    }
}