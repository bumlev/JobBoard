<?php

namespace App\Exceptions;

use App\Exceptions\ErrorException\classes\MethodErrorException;
use App\Exceptions\ErrorException\classes\QueryErrorException;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   

    public function register()
    {
        $this->reportable(function (Request $request ,  Throwable $e){   
        });
    }
    
    public function render($request, Throwable $e)
    {
        if($e instanceof QueryException)
        {
            return  QueryErrorException::execute();
        }else if($e instanceof MethodNotAllowedHttpException)
        {
            return MethodErrorException::execute();
        }
    }
}