<?php

namespace App\Views;

/**
 * Represent a view in the MVC model.
 * Class View
 * @package App\Views
 */
class View
{
    private $name;
    private $layout;

    /**
     * View constructor.
     * @param string $name
     * @param string $layout
     */
    private function __construct(string $name, string $layout)
    {
        $this->name = $name;
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * Create a new View.
     * @param string $name
     * @param string $layout
     * @return \App\Views\View
     */
    public static function new(string $name, string $layout = 'default'): View
    {
        return new View($name, $layout);
    }
}
