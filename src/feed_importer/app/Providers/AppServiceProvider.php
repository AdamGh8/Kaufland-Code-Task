<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\StorageInterface;
use App\Services\Storage\SQLiteStorage;
use App\Services\Storage\MySQLStorage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
