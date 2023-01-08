<?php

namespace KieranFYI\UserUI\Services;

class RegisterUserComponent
{

    /**
     * @var array|null
     */
    private ?array $view = null;

    /**
     * @var string
     */
    private string $html = '';

    /**
     * @param array $arguments
     * @return $this
     */
    public function create(array ...$arguments): static
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

    /**
     * @return string
     */
    public function content(): string
    {
        $content = [];

        if (!empty($this->view)) {
            $content[] = view($this->view['template'], $this->view['arguments'])->render();
        }

        if (!empty($this->html)) {
            $content[] = $this->html;
        }

        return implode(PHP_EOL . PHP_EOL, $content);
    }
}