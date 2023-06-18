<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testConversationsRelation()
    {
        $user = User::factory()->create();
        $relation = $user->conversations();
        $this->assertInstanceOf(HasMany::class , $relation);
    }

    /** @test */
    public function testMessagesRelation()
    {
        $user = User::factory()->create();
        $relation = $user->messages();
        $this->assertInstanceOf(HasMany::class , $relation);
    }
}