<?php
namespace App\Exceptions\ErrorException\classes;

class ModelException
{
    static public function getMessage()
    {
        $errorMessage = __("messages.ModelException");
        $errorCode = 404;
        return response()->json([
            'error' => $errorMessage
        ], $errorCode);
    }
}