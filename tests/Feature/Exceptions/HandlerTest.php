<?php
namespace Tests\Feature\Exceptions;

use App\Exceptions\ErrorException\classes\ModelException;
use App\Exceptions\Handler;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function render_not_allowed_method()
    {
        $response = $this->put("/create_user");
        $response = $response->getData();
        $this->assertEquals($response->error , "Unauthorized Method for your request");
    }

    /** @test */
    public function render_no_connection_database()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $data["roles"] = [2];
        
        $this->beginDatabaseTransaction();
        DB::disconnect("sqlite");

        $response = $this->json('POST', '/create_user' , $data);
        $response = $response->getData();
        $this->assertEquals($response->error , "Database connection error");
    }

    /** @test */
    public function render_not_found_method()
    {
        $response = $this->post("/create_users");
        $response = $response->getData();
        $this->assertEquals($response->error , "The endpoint is not found...");
    }

    /** @test */
    public function render_model_not_found()
    { 
        $response = ModelException::getMessage();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $content = $response->getData();

        $this->assertEquals(__('messages.ModelException'), $content->error);
        $this->assertEquals(404, $response->getStatusCode());
        
    }
}