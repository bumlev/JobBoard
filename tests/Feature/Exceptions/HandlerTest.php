<?php
namespace Tests\Feature\Exceptions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}