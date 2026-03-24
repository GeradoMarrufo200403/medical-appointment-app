<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // <-- IMPORTANTE: Añade esta línea
use WireUi\View\Components\Button;   // <-- IMPORTANTE: Añade esta línea

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
        // ESTA ES LA LÍNEA MÁGICA
        // Vincula la etiqueta <x-wire-button> con la clase real de WireUI
        Blade::component('wire-button', Button::class);
    }
}