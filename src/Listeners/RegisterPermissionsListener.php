<?php
namespace KieranFYI\UserUI\Listeners;

use KieranFYI\UserUI\Policies\UserPolicy;

class RegisterPermissionsListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(): void
    {
        UserPolicy::register();
    }
}