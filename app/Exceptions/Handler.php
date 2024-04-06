<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     // 認証等のエラーの場合
    //     if($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException){
    //         $statusCode = 403;
    //         return response()->view('errors.common', compact('statusCode'), $statusCode);

    //     }else if($exception instanceof HttpException){
    //         $statusCode = $exception->getStatusCode();
    //         return response()->view('errors.common', compact('statusCode'), $statusCode);
    //     }

    //     return parent::render($request, $exception);
    // }


}
