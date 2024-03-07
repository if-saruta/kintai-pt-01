<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

use Illuminate\Auth\AuthenticationException;

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

    public function render($request, Throwable $exception)
    {
        // 開発中は詳細なエラーを表示する
        if (app()->environment('local')) {
            return parent::render($request, $exception);
        }

        // 認証例外の場合はログイン画面にリダイレクトする
        if ($exception instanceof AuthenticationException) {
            return redirect()->guest(route('login'));
        }

        // 419エラーを捕捉してログインページにリダイレクト
        if ($exception instanceof TokenMismatchException) {
            return redirect()->guest(route('login'));
        }

        // それ以外のエラーで本番環境ではホーム画面にリダイレクトする
        return redirect()->route('home')->with('error', 'Sorry, something went wrong.');
    }


}
