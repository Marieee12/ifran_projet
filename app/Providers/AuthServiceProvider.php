<?php

namespace App\Providers;

use App\Models\SeanceCours;
use App\Models\Presence;
use App\Policies\SeanceCoursPolicy;
use App\Policies\PresencePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SeanceCours::class => SeanceCoursPolicy::class,
        Presence::class => PresencePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
