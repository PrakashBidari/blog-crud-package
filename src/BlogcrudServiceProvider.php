<?php

namespace Laraphant\Blogcrud;

use Illuminate\Support\ServiceProvider;
use Laraphant\Blogcrud\Console\Commands\GenerateBlogMigration;


class BlogcrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register any bindings or package-specific services here.
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateBlogMigration::class,
            ]);
        }
    }
}
