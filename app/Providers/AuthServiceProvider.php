<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\Room;
use App\Models\User;
use App\Policies\EventPolicy;
use App\Policies\RoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Room::class => RoomPolicy::class,
        Event::class => EventPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
