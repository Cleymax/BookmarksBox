<?php

namespace App\Models;

use App\Database\Query;

abstract class Model
{
    protected $table;

    /**
     * Model constructor.
     */
    public function __construct(?string $table_name = null)
    {
        $this->table = $table_name ?? get_class($this);
    }

    public function getById($id, ?string...$field)
    {
        $query = (new Query())
            ->select(empty($field) ? '*' : $field)
            ->from($this->table)
            ->where('id = ?')
            ->params([$id])
            ->limit(1);
        return $query->first();
    }

    public function getAll(): array
    {
        $query = (new Query())
            ->from($this->table)
            ->order('od');
        return $query->all();
    }
}
