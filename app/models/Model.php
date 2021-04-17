<?php

namespace App\Model;

abstract class Model
{
    private $table;

    /**
     * Model constructor.
     */
    public function __construct(?string $table_name = null)
    {
        $this->table = $table_name ?? get_class($this);
    }


    public function getById($id)
    {

    }
}
