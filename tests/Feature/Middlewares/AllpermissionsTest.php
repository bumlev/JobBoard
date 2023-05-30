<?php
namespace Tests\Feature\Middlewares;

use App\Http\Middleware\AllPermissionsMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class AllPermissionsTest extends TestCase
{
    /** @test */
    public function if_not_have_a_permission()
    {
        $this->post("/authenticate" , ["email" => "bumwejaychris@yahoo.com" , "password" => "levy_600"]);

        $permissions = ['jobs.appliedJobs'];
        $request = new Request();
        $nextClosure = function () {
            return response('Passed');
        };

        $AllPermissionsMiddleware = new AllPermissionsMiddleware();
        $response = $AllPermissionsMiddleware->handle($request , $nextClosure , $permissions);
        $jsondata = $response->getContent();
        $data = json_decode($jsondata , true);
        $this->assertEquals($data['Access'] , "You are not authorized to access this page...");
      
    }

    /** @test */
    public function if_have_a_permission()
    {
        $this->post("/authenticate" , ["email" => "bumwelevy@yahoo.in" , "password" => "levy_600"]);

        $permissions = ['jobs.appliedJobs'];
        $request = new Request();
        $nextClosure = function () {
            return response('Passed');
        };

        $AllPermissionsMiddleware = new AllPermissionsMiddleware();
        $response = $AllPermissionsMiddleware->handle($request , $nextClosure , $permissions);
        $data = $response->getContent();
        $this->assertEquals($data , $nextClosure($request)->getContent());
    }
}