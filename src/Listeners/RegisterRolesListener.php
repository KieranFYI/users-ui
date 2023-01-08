<?php

namespace KieranFYI\UserUI\Listeners;

use KieranFYI\Roles\Core\Services\Register\RegisterRole;
use KieranFYI\UserUI\Policies\UserPolicy;

class RegisterRolesListener
{
    /**
     * Handle the event.
     *
     * @return array
     */
    public function handle(): array
    {
        return [
            RegisterRole::register('User Manager')
                ->displayOrder(10)
                ->permission(UserPolicy::class),
        ];
    }
}