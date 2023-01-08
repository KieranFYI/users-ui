<?php

namespace KieranFYI\UserUI\Services;

class RegisterUserComponent
{

    /**
     * @var array|null
     */
    public ?array $view = null;

    /**
     * @var string
     */
    public string $html = '';

    /**
     * @param array $arguments
     * @return $this
     */
    public static function create(...$arguments): static
    {
        return new static(...$arguments);
    }

    /**
     * @param string $template
     * @param array $arguments
     * @return RegisterUserTab
     */
    public function view(string $template, array ...$arguments): static
    {
        $this->view = [
            'template' => $template,
            'arguments' => $arguments,
        ];
        return $this;
    }

    /**
     * @param string $html
     * @return $this
     */
    public function html(string $html): static
    {
        $this->html = $html;
        return $this;
    }
}