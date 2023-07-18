<?php
namespace App\Repositories\RecruitersCtrlRepos\ChatWithCandidate\Classes;

use App\Models\Message;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class ChatWithCandidate
{
    static public function execute(Request $request)
    {
        $data = self::ValidateDataChat($request);
        if(gettype($data) == "object")
            return response()->json(["errorsChat" => $data->errors()] , 422);
            
        $currentUser = Sentinel::getUser();
        $data["sender_id"] = $currentUser->id;
        $conversation = self::createChat($data);

        $data["user_id"] = $currentUser->id;
        $data["conversation_id"] = $conversation->id;
        
        Message::create($data);
        $messages = Message::with("user" , "conversation")
                            ->where('conversation_id' , $conversation->id)
                            ->get();
        return response()->json(["messages"=> $messages] , 200);
    }

    // Create a conversation
    static private function createChat(array $data)
    {
       return CreateChat::execute($data);
    }

    // Validate data chat 
    static private function ValidateDataChat(Request $request)
    {
        return ValidatorData::execute($request);
    }
}