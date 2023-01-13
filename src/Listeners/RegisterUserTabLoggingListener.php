<?php

namespace KieranFYI\UserUI\Listeners;

use KieranFYI\Logging\Traits\HasLoggingTrait;
use KieranFYI\UserUI\Services\RegisterUserTab;
use KieranFYI\UserUI\Models\User;

class RegisterUserTabLoggingListener
{
    use HasLoggingTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(User $user): ?RegisterUserTab
    {
        if (!$this->hasLogging($user)) {
            return null;
        }
        return RegisterUserTab::create('Logs')
            ->view('users-ui::tabs.logging', ['user' => $user]);
    }

}