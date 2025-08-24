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
            Css::make('custom-filemanager', asset("css/livewire-filemanager/custom.css")),
            Js::make('tailwind', asset("js/livewire-filemanager/3.4.17.es")),
            Js::make('alpine-ui', asset("js/livewire-filemanager/cdn.min.js")),
        ]);
    }
}
