<?php
namespace KieranFYI\UserUI\Listeners;

use KieranFYI\UserUI\Policies\UserPolicy;

class RegisterPermissionsListener
{
    /**
     * Handle the event.
     *
     * @return array
     */
    public function handle(): array
    {
        return [
            UserPolicy::class
        ];
    }
}