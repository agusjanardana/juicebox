<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(
            'App\Repositories\NoteRepository\NoteRepositoryInterface',
            'App\Repositories\NoteRepository\NoteRepositoryImpl'
        );

        $this->app->bind(
            'App\Repositories\UserRepository\UserRepositoryInterface',
            'App\Repositories\UserRepository\UserRepositoryImpl'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}