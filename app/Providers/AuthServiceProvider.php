<?php

namespace App\Providers;

use App\Models\Classroom;
use App\Policies\ClassPolicy;
use App\Models\Announcement;
use App\Policies\AnnouncementPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Classroom::class => ClassPolicy::class,
        Task::class => TaskPolicy::class,
        Announcement::class => AnnouncementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}