<?php

namespace Pharaonic\Laravel\Files;

use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Config Merge
        $this->mergeConfigFrom(__DIR__ . '/config/files.php', ['pharaonic', 'laravel-has-files']);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishes
        $this->publishes([
            __DIR__ . '/config/files.php'                       => config_path('Pharaonic/files.php'),
            __DIR__ . '/database/migrations/files.stub'         => database_path(sprintf('migrations/%s_create_files_table.php',          date('Y_m_d_His', time() + 3))),
        ], ['pharaonic', 'laravel-has-files']);

        // Loads
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
