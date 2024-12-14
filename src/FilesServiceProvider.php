<?php

namespace Pharaonic\Laravel\Files;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Files\Models\File;
use Pharaonic\Laravel\Files\Observers\FileObserver;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // About
        AboutCommand::add('Pharaonic', fn() => ['Has Files' => '3.x']);

        // Observers
        File::observe(FileObserver::class);

        // Publishes
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], ['pharaonic', 'laravel-has-files']);
    }
}
