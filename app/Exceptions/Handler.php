<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        // if (app()->environment('local')) {
        //     return parent::render($request, $exception);
        // }

        // 本番環境では全てのエラーで特定のルートにリダイレクトする
        return redirect()->route('home')->with('error', 'Sorry, something went wrong.');
    }


}
