<?php

namespace KieranFYI\UserUI\Events;

use Illuminate\Foundation\Auth\User;

class AbstractRegisterUserEvent
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }
}