<?php
namespace Tests\Feature\Controllers\Tests;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilesControllerTest extends TestCase
{
   
    /** @test */
    public function getStoredImage()
    {
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
        $path ="public/testFiles/TestImage.png";
        $destinationPath ="public/images/TestImage.png";
        if(!Storage::exists($destinationPath))
            Storage::copy($path , $destinationPath);
            
        $response = $this->get("storage/app/public/images/exampl.png");
        $response->assertStatus(200);
    }
}