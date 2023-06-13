<?php
namespace Tests\Feature\Middlewares;

use App\Http\Controllers\UsersController;
use App\Http\Middleware\AllPermissionsMiddleware;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AllPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function if_not_have_a_permission()
    {
        $dataRoles = RoleFactory::new()->arrayState();
        RoleFactory::new()->createMany($dataRoles);

        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $data["roles"] = [3];

        $request = new Request($data);
        $usersController = new UsersController();
        $usersController->store($request);
        $this->post("/authenticate" , ["email" => $data["email"] , "password" => $data["password"]]);

        $permissions = ['jobs.appliedJobs'];
        $request = new Request();
        $nextClosure = function () {
            return response('Passed');
        };

        $AllPermissionsMiddleware = new AllPermissionsMiddleware();
        $response = $AllPermissionsMiddleware->handle($request , $nextClosure , $permissions);
        $jsondata = $response->getContent();
        $data = json_decode($jsondata , true);
        $this->assertEquals($data['AuthorizationError'] , "As a Recruiter You are not authorized to access this page..."); 
    }

    /** @test */
    public function if_have_a_permission()
    {
        $dataRoles = RoleFactory::new()->arrayState();
        RoleFactory::new()->createMany($dataRoles);

        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $data["roles"] = [2];

        $request = new Request($data);
        $usersController = new UsersController();
        $usersController->store($request);
        $this->post("/authenticate" , ["email" => $data["email"] , "password" => $data["password"]]);

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