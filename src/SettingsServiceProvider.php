<?php

namespace Nurinteractive\Settings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/config/' => config_path(),
        ], 'config');

        // Publish migration
        if (config('settings.migration.publish', true)) {
            $this->publishes([
                __DIR__ . '/migrations/' => database_path('/migrations/'),
            ], 'migrations');
        }

        // Load migration
        if (config('settings.migration.load', true)) {
            $this->loadMigrationsFrom(__DIR__ . '/migrations');
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // merge config
        $this->mergeConfigFrom(
            __DIR__ . '/config/settings.php',
            'settings'
        );
        // bind setting storage
        $this->app->bind(
            'Nurinteractive\Settings\Setting\SettingStorage',
            'Nurinteractive\Settings\Setting\SettingEloquentStorage'
        );
    }
}
