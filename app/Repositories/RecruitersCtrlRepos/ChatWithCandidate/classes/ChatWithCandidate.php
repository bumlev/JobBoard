<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Models\Conversation;
use App\Models\Message;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;

class ChatWithCandidate
{

    static public function execute($request)
    {
        $currentUser = Sentinel::getUser();
        $data = self::ValidateDataChat($request);
        
        if(gettype($data) == "object")
            return $data->errors();

        $data["sender_id"] = $currentUser->id;
        $content = $data["content"];
        unset($data["content"]);

        $conversation = self::createChat($data);
        $data = [
            "user_id" => $currentUser->id,
            "conversation_id" => $conversation->id,
            "content" => $content
        ];
        
        Message::create($data);
        return Message::with("user" , "conversation")->where('conversation_id' , $conversation->id)->get();
    }


    // Create a conversation
    static private function createChat($data)
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
    

    // Validate data chat 
    static private function ValidateDataChat($request)
    {
        $data = [
            "receiver_id" => intval($request->input("receiver")),
            "content" => $request->input("content")
        ];
        
        $data_rules = [
            "receiver_id" => "Required|numeric|not_in:0",
            "content" => "Required"
        ];
        $dataValidator = Validator::make($data , $data_rules); 
        return $dataValidator->fails() ? $dataValidator : $data;
    }
}