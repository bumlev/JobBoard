<?php
namespace Tests\Feature\Controllers\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LangControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function switchLanguage()
    {
        $response = $this->get("/lang/fr");
        $response->assertStatus(200);
    }
}