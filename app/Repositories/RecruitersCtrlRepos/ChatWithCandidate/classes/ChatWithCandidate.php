<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Models\Conversation;
use App\Models\Message;
use App\Repositories\HandleError\ErrorsNotMatchKeys;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatWithCandidate
{
    static public function execute(Request $request)
    {
        $currentUser = Sentinel::getUser();
        $data = self::ValidateDataChat($request);
        
        if(gettype($data) == "object")
            return $data->errors();

        $data["sender_id"] = $currentUser->id;
        $conversation = self::createChat($data);
        $data = [
            "user_id" => $currentUser->id,
            "conversation_id" => $conversation->id,
            "content" => $data["content"]
        ];
        
        Message::create($data);
        return Message::with("user" , "conversation")->where('conversation_id' , $conversation->id)->get();
    }

    // Create a conversation
    static private function createChat($data)
    {
       return CreateChat::execute($data);
    }

    // Validate data chat 
    static private function ValidateDataChat(Request $request)
    {
        return ValidatorData::execute($request);
    }
}