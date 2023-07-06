<?php
namespace App\Exceptions\ErrorException\classes;

class MethodErrorException
{
    static public function getMessage()
    {
        $errorMessage = __("messages.MethodException");
        $errorCode = 405;
        return response()->json([
            'error' => $errorMessage
        ], $errorCode);
    }
}