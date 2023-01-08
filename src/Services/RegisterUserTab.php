<?php

namespace KieranFYI\UserUI\Services;

class RegisterUserTab extends RegisterUserComponent
{

    /**
     * @var string
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}