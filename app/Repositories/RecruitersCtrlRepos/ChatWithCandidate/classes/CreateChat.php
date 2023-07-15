<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Models\Conversation;

class CreateChat 
{
    static function execute(array $data)
    {
        $conversation = Conversation::whereIn('sender_id', $data)
                                    ->whereIn('receiver_id', $data)
                                    ->firstOrCreate([], $data);
        return $conversation;
    }
}