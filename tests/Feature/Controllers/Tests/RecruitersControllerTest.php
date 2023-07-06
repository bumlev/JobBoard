<?php
namespace Tests\Feature\Controllers\Tests;

use App\Http\Controllers\RecruitersController;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RecruitesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function postedJobs()
    {
        $dataUser = User::factory()->make()->toArray();
        $dataUser["password"] = "levy_600";
        Sentinel::registerAndActivate($dataUser);

        $this->post("/authenticate" , ["email" => $dataUser["email"] , "password" => $dataUser["password"]]);

        $recruitersController = new RecruitersController();
        $response = $recruitersController->postedJobs();
        $this->assertTrue(property_exists($response , "data"));
    }

    /** @test */
    public function findRightCandidates()
    {
        $dataJob = Job::factory()->create();
        $recruitersController = new RecruitersController();
        $response = $recruitersController->findRightCandidates($dataJob->id);
        $this->assertTrue(property_exists($response , "data"));
    }

    /** @test */
    public function findRightCandidates_no_job()
    {
        $recruitersController = new RecruitersController();
        $response = $recruitersController->findRightCandidates(1);
        $this->assertTrue(property_exists($response , "data"));
    }

    /** @test */
    public function chatWithCandidate()
    {
        $dataSender = User::factory()->make()->toArray();
        $dataSender["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($dataSender);
        $this->post("/authenticate" , ["email" => $user->email , "password"=> $dataSender["password"]]);

        $data=[
            "receiver_id" => 2,
            "content" => "Hello Guy"
        ];
        $request = Request::create("/chatWithCandidate" , "POST" , $data);
        $recruitersController = new RecruitersController();
        $response = $recruitersController->chatWithCandidate($request);
        $response = $response->getData()->messages;
        $this->assertIsArray($response);
    }

    /** @test */
    public function chat_with_exists_conversation()
    {
        $dataSender = User::factory()->make()->toArray();
        $dataSender["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($dataSender);
        $this->post("/authenticate" , ["email" => $user->email , "password"=> $dataSender["password"]]);

        $data =[
            "sender_id" =>1,
            "receiver_id" =>2
        ];
        Conversation::create($data);

        $data=[
            "receiver_id" => 2,
            "content" => "Hello Guy"
        ];
        $request = Request::create("/chatWithCandidate" , "POST" , $data);
        $recruitersController = new RecruitersController();
        $response = $recruitersController->chatWithCandidate($request);
        $response = $response->getData()->messages;
        $this->assertIsArray($response);
    }

    /** @test */
    public function chat_with_empty_data()
    {
        $dataSender = User::factory()->make()->toArray();
        $dataSender["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($dataSender);
        $this->post("/authenticate" , ["email" => $user->email , "password"=> $dataSender["password"]]);

        $data=[
            "receiver_id" => 2,
            "content" => ""
        ];
        $request = Request::create("/chatWithCandidate" , "POST" , $data);
        $recruitersController = new RecruitersController();
        $response = $recruitersController->chatWithCandidate($request);
        $response = $response->getOriginalContent()["errorsChat"];
        $this->assertEquals($response->getFormat() , ":message");
    }
}