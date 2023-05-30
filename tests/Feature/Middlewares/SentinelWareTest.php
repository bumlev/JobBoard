<?php
namespace Tests\Feature\Middlewares;

use App\Http\Middleware\SentinelWare;
use Illuminate\Http\Request;
use Tests\TestCase;

class SentinelWareTest extends TestCase
{
    /** @test */
    public function is_authenticated()
    {
        $this->post("/authenticate" , ["email" => "bumwejaychris@yahoo.com" , "password" => "levy_600"]);
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
        $this->assertEquals($data['guestAccess'] , "You are not logged in!!");
    }
}
