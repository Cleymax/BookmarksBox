<?php

namespace App\Models;

use App\Database\Query;
use App\Exceptions\NotFoundException;

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

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function getById($id, ?string...$field)
    {
        $query = (new Query())
            ->select(empty($field) ? '*' : $field)
            ->from($this->table)
            ->where('id = ?')
            ->params([$id])
            ->limit(1);
        if($query->rowCount() == 0){
            throw new NotFoundException('Equipe inconnue !');
        }else {
            return $query->first();
        }
    }

    public function getAll(): array
    {
        $query = (new Query())
            ->from($this->table)
            ->order('id');
        return $query->all();
    }
}
