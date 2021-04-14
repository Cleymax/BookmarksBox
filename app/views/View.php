<?php
namespace App\View;

class View
{
    private $name;
    private $layout;

    /**
     * View constructor.
     * @param string $name
     * @param string $layout
     */
    public function __construct(string $name, string $layout)
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
}
