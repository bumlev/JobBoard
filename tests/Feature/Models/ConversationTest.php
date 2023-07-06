<?php
namespace Tests\Feature\Models;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /** @test */
    public function testConversationMessagesRelation()
    {
        
        $data = [
            "sender_id" => 1,
            "receiver_id" => 2
        ];
        $Conversation = Conversation::create($data);

        $data = [
            "user_id" => 1,
            "conversation_id" => $Conversation->id,
            "content" => "Hello"
        ];
        Message::create($data);
        $relation = $Conversation->messages();
        $this->assertInstanceOf(HasMany::class , $relation);
    }
}