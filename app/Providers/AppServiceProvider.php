<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        Model::unguard();
        FilamentAsset::register([
            Css::make('tailwind', "https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"),
            Js::make('alpine-ui', "https://unpkg.com/@alpinejs/ui@3.13.3-beta.1/dist/cdn.min.js")
        ]);
    }
}
