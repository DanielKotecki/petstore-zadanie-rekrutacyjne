<?php

namespace App\Providers;

use App\Services\PetStoreApiService;
use App\Services\PetStoreServiceInterface;
use Illuminate\Support\ServiceProvider;

class PetStoreProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PetStoreServiceInterface::class, PetStoreApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
