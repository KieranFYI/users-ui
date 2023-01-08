<?php

namespace KieranFYI\UserUI\Listeners;

use Illuminate\Support\Facades\Auth;
use KieranFYI\Admin\Facades\Admin;

class RegisterAdminNavigationListener
{
    /**
     * Handle the event.
     */
    public function handle(): void
    {
        if (Auth::check() && !Auth::user()->hasPermission('View Any User')) {
            return;
        }

        Admin::menu('Users', 'admin')
            ->route('admin.users.index')
            ->icon('fas fa-users');
    }
}