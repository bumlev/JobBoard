<?php
namespace Tests\Feature\Controllers\Tests;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilesControllerTest extends TestCase
{
   use RefreshDatabase;
    /** @test */
    public function getStoredImage()
    {
        
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);

        $path ="public/testFiles/TestImage.png";
        $destinationPath ="public/images/TestImage.png";
        if(!Storage::exists($destinationPath))
            Storage::copy($path , $destinationPath);

        $response = $this->get("storage/app/public/images/TestImage.png");
        $response->assertStatus(200);
    }

    /** @test */
    public function getNotStoredImage()
    {
        $data = User::factory()->make()->toArray();
        $data["password"] = "levy_600";
        $user = Sentinel::registerAndActivate($data);
        $this->post("/authenticate" , ["email" => $user->email, "password" => $data["password"]]);

        $path ="public/testFiles/TestImage.png";
        $destinationPath ="public/images/TestImage.png";
        if(!Storage::exists($destinationPath))
            Storage::copy($path , $destinationPath);
            
        $response = $this->get("storage/app/public/images/exampl.png");
        $response->assertStatus(200);
    }
}