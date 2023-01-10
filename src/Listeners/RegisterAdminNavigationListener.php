<?php

namespace KieranFYI\UserUI\Listeners;

use KieranFYI\Admin\Facades\Admin;
use KieranFYI\UserUI\Models\User;

class RegisterAdminNavigationListener
{
    /**
     * Handle the event.
     */
    public function handle(): void
    {
        Admin::header('admin')
            ->menu('Users')
            ->can('viewAny')
            ->model(User::class)
            ->route('admin.users.index')
            ->icon('fas fa-users');
    }
}