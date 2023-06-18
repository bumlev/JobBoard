<?php
namespace Tests\Feature\Models;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testConversationUserRelation()
    {
        $data = [
            "sender_id" => 1,
            "receiver_id" => 2
        ];
        $Conversation = Conversation::create($data);
        $relation = $Conversation->user();
        $this->assertInstanceOf(BelongsTo::class , $relation);
    }
}