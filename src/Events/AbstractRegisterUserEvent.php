<?php

namespace KieranFYI\UserUI\Events;

use KieranFYI\UserUI\Models\User;

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