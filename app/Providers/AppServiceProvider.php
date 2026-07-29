<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Repuesto;
use App\Models\Servicio;
use App\Models\User;
use App\Models\Vehiculo;
use App\Observers\AuditObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        Paginator::useBootstrapFive();

        foreach ([Cliente::class, OrdenTrabajo::class, Repuesto::class, Servicio::class, User::class, Vehiculo::class] as $model) {
            $model::observe(AuditObserver::class);
        }

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
