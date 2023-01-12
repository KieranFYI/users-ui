<?php

namespace KieranFYI\UserUI\Listeners;

use KieranFYI\Roles\Core\Services\Register\RegisterRole;
use KieranFYI\UserUI\Policies\UserPolicy;

class RegisterRolesListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(): void
    {
            RegisterRole::register('User Manager')
                ->displayOrder(10)
                ->permission(UserPolicy::class)
            ->permission('View Any Role');
    }
}