<?php
namespace App\Exceptions\ErrorException\classes;

class ExceptionErrors
{
    static public function getQueryException()
    {
        $errorMessage = __("messages.QueryException");
        $errorCode = 500;
        return response()->json([
            'error' => $errorMessage
        ], $errorCode);
    }

    static public function getMethodException()
    {
        return MethodErrorException::getMessage();
    }

    static public function getNotHttpException()
    {
        return NotHttpException::getMessage();
    }

    static public function getModelException()
    {
        return ModelException::getMessage();
    }
}