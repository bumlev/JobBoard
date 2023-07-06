<?php
namespace App\Exceptions\ErrorException\classes;

class QueryErrorException
{
    static public function getMessage()
    {
        $errorMessage = __("messages.QueryException");
        $errorCode = 500;
        return response()->json([
            'error' => $errorMessage
        ], $errorCode);
    }
}