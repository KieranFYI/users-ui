<?php

namespace KieranFYI\UserUI\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use KieranFYI\Admin\Events\RegisterAdminNavigationEvent;
use KieranFYI\Roles\Core\Events\Register\RegisterPermissionEvent;
use KieranFYI\Roles\Core\Events\Register\RegisterRoleEvent;
use KieranFYI\UserUI\Listeners\RegisterAdminNavigationListener;
use KieranFYI\UserUI\Listeners\RegisterPermissionsListener;
use KieranFYI\UserUI\Listeners\RegisterRolesListener;
use KieranFYI\UserUI\Policies\UserPolicy;

class UserUIPackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $root = __DIR__ . '/../..';

        $this->publishes([
            $root . '/public' => public_path('vendor/kieranfyi/users-ui'),
        ], ['laravel-assets']);

        $this->loadViewsFrom($root . '/resources/views', 'users-ui');
        $this->loadRoutesFrom($root . '/routes/web.php');

        $userClass = config('auth.providers.users.model');
        Gate::policy($userClass, UserPolicy::class);

        class_alias($userClass, 'KieranFYI\\UserUI\\Models\\User');
        Gate::policy('KieranFYI\\UserUI\\Models\\User', UserPolicy::class);


        Event::listen(RegisterPermissionEvent::class, RegisterPermissionsListener::class);
        Event::listen(RegisterRoleEvent::class, RegisterRolesListener::class);
        Event::listen(RegisterAdminNavigationEvent::class, RegisterAdminNavigationListener::class);
    }
}
