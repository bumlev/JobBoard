<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Models\Conversation;

class CreateChat 
{
    static function execute($data)
    {
        $conversation = Conversation::where("sender_id" , $data["sender_id"])
                                    ->where("receiver_id" , $data["receiver_id"])->first();
        if(empty($conversation))
        {
            $conversation = Conversation::create($data);
            return $conversation;
        }
        else
        {
            return Conversation::with("messages")->find($conversation->id);
        }
    }
}