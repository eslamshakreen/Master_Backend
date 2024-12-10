<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
    {
        ResponseFactory::macro('api', function ($data = null, $error = 0, $message = '') {
            return response()->json([
                'data' => $data,
                'error' => $error,
                'message' => $message,
            ]);
        });

        app()->setLocale('ar');
    }
}
