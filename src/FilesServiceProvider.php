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
        $this->mergeConfigFrom(__DIR__ . '/config/files.php', 'laravel-has-files');

        // Migration Loading
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
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
            __DIR__ . '/config/files.php'                                       => config_path('Pharaonic/files.php'),
            __DIR__ . '/database/migrations/2021_02_01_000003_create_files_table.php' => database_path('migrations/2021_02_01_000003_create_files_table.php'),
        ], ['pharaonic', 'laravel-has-files']);

    }
}
