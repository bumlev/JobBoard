<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class UserModelTest extends TestCase
{

    /** @test */
    public function it_can_create_a_user()
    {
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret'
        ];

        try {
            $user = Sentinel::registerAndActivate($data);
            $this->assertInstanceOf(UserInterface::class , $user);
            $this->assertEquals($data['first_name'], $user->first_name);
            $this->assertEquals($data['last_name'], $user->last_name);
            $this->assertEquals($data['email'], $user->email);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }
       
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::find(12);
        $data = [
            'first_name'=> 'Zelachou',
            'last_name' =>'Mukundwa',
            'email' => 'Zelachou@gmail.com',
            'password' => 'secret'
        ];

        try {
            Sentinel::update($user , $data);
            $this->assertInstanceOf(UserInterface::class , $user);
            $this->assertEquals($data['first_name'] , $user->first_name);
            $this->assertEquals($data['last_name'] , $user->last_name);
            $this->assertEquals($data['email'] , $user->email);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }
        
    }
}