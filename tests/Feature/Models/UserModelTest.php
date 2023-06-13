<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret'
        ];

        $user = Sentinel::registerAndActivate($data);
        $this->assertInstanceOf(UserInterface::class , $user);
        $this->assertEquals($data['first_name'], $user->first_name);
        $this->assertEquals($data['last_name'], $user->last_name);
        $this->assertEquals($data['email'], $user->email); 
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $dataUser = User::factory()->make()->toArray();
        $dataUser["password"] = "levy_600";
        $User = Sentinel::registerAndActivate($dataUser);

        $user = User::find($User->id);
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret'
        ];

        Sentinel::update($user , $data);
        $this->assertInstanceOf(UserInterface::class , $user);
        $this->assertEquals($data['first_name'] , $user->first_name);
    }
}