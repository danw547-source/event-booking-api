<?php

namespace App\Providers;

use App\Repositories\AttendeeRepository;
use App\Repositories\BookingRepository;
use App\Repositories\Contracts\AttendeeRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\EventRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Technique: container bindings for DIP.
        // Why applied: high-level code depends on interfaces, making storage logic swappable and easier to test.
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(AttendeeRepositoryInterface::class, AttendeeRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
