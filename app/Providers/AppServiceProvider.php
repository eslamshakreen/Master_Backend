<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     use Illuminate\Support\Facades\URL;

     /**
     * Register any application services.
     */
    public function register(): void
    {
        // Force HTTPS in production environment

    }
    public function boot(): void
    {

        if ($this->app->environment() != 'production') {
            $parse = parse_url(config('app.url'));
            request()->headers->set('host', $parse['host']);
        }
        FilamentView::registerRenderHook(
            'panels::scripts.after',
            fn(): string => Blade::render('
            <script>
                if(localStorage.getItem(\'theme\') === null) {
                    localStorage.setItem(\'theme\', \'dark\')
                }
            </script>'),
        );
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
