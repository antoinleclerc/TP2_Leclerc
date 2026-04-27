<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repository\CategoryRepositoryInterface::class, \App\Repository\Eloquent\CategoryRepository::class);
        $this->app->bind(\App\Repository\EquipmentRepositoryInterface::class, \App\Repository\Eloquent\EquipmentRepository::class);
        $this->app->bind(\App\Repository\RentalRepositoryInterface::class, \App\Repository\Eloquent\RentalRepository::class);
        $this->app->bind(\App\Repository\ReviewRepositoryInterface::class, \App\Repository\Eloquent\ReviewRepository::class);
        $this->app->bind(\App\Repository\SportRepositoryInterface::class, \App\Repository\Eloquent\SportRepository::class);
        $this->app->bind(\App\Repository\UserRepositoryInterface::class, \App\Repository\Eloquent\UserRepository::class);
        $this->app->bind(\App\Repository\RepositoryInterface::class, \App\Repository\Eloquent\BaseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
