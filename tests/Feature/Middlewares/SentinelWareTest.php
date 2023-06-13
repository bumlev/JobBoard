<?php
namespace Tests\Feature\Middlewares;

use App\Http\Middleware\SentinelWare;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SentinelWareTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function is_authenticated()
    {
        $data  = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);

        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);
        $request = new Request();
        $nextClosure = function () {
            return response('Passed');
        };

        $sentinelWare = new SentinelWare();
        $response = $sentinelWare->handle($request , $nextClosure);
        $data = $response->getContent();
        $this->assertEquals($data , $nextClosure($request)->getContent());
    }

    /** @test */
    public function is_not_authenticated()
    {
        $request = new Request();
        $nextClosure = function () {
            return response('Passed');
        };

        $sentinelWare = new SentinelWare();
        $response = $sentinelWare->handle($request , $nextClosure);
        $jsondata = $response->getContent();
        $data = json_decode($jsondata , true);
        $this->assertEquals($data['AuthError'] , "You are not logged in!!");
    }
}
