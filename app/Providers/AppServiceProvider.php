<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Gate::guessPolicyNamesUsing(function ($modelClass) {
        //     // モデルとポリシーの対応をここで手動で設定
        //     return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
        // });
    }
}
