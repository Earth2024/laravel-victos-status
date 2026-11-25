<?php

namespace Earth2024\VictosStatus;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VictosStatusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/victos-status.php', 'victos-status');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'victos-status');

        $this->publishes([
            __DIR__ . '/../config/victos-status.php' => config_path('victos-status.php'),
        ], 'victos-status-config');

        $this->publishes([
            __DIR__ . '/../resources/js' => public_path('vendor/victos-status'),
        ], 'victos-status-assets');

        Gate::define('manage-victos-admins', fn ($user): bool => ($user->role ?? null) === 'super_admin');
    }
}
