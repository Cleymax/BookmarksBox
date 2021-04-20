<?php

namespace App\Database;

/**
 * Class Query
 * Create easily a sql request.
 * @package app\Database
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class Query
{
    private $type;
    private $select;
    private $distinct = false;
    private $table;
    private $where = [];
    private $group;
    private $order = [];
    private $limit;
    private $inner;
    private $pdo;
    private $params = [];
    private $values = [];
    private $returning = [];

    /**
     * Query constructor.
     * @param \PDO|null $pdo
     */
    public function __construct(?\PDO $pdo = null)
    {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            $this->pdo = Database::get();
        }
        $this->type = 'SELECT';
    }

    /**
     *  Create an insertion request. Choose the columns to insert.
     * @param string ...$columns
     * @return $this
     */
    public function insert(string...$columns): self
    {
        $this->select = $columns;
        $this->type = 'INSERT';
        return $this;
    }

    /**
     * Create an update request.
     * @return $this
     */
    public function update(): self
    {
        $this->type = 'UPDATE';
        return $this;
    }

    /**
     * Create a deletion request.
     * @return $this
     */
    public function delete(): self
    {
        $this->type = 'DELETE';
        return $this;
    }

    /**
     * Choose the table where insert the data.
     * @param string $table
     * @return $this
     */
    public function into(string $table): self
    {
        return $this->from($table);
    }

    /**
     * Select data from an table. You can add a alias.
     * @param string $table_name
     * @param string|null $alias
     * @return $this
     */
    public function from(string $table_name, ?string $alias = null): self
    {
        $this->table = !$alias ? $table_name : $table_name . ' AS ' . $alias;
        return $this;
    }

    /**
     * Choose which columns you are going to retrieve.
     * @param string ...$fields
     * @return $this
     */
    public function select(string...$fields): self
    {
        $this->select = $fields;
        return $this;
    }

    /**
     * Put the DISTINCT option in the selection. Allows to remove duplicates.
     * @return $this
     */
    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    /**
     * Add conditions.
     * @param string ...$conditions
     * @return $this
     */
    public function where(string...$conditions): self
    {
        $this->where = array_merge($this->where, $conditions);
        return $this;
    }

    /**
     * Add a condition if a field is or is not between a value and another certain value.
     * @param string $column the column
     * @param string $value1 first value
     * @param string $value2 second value
     * @param bool $not
     * @return $this
     */
    public function between(string $column, string $value1, string $value2, bool $not = false): self
    {
        $this->where[] = $column . ' ' . ($not ? 'NOT ' : '') . 'BETWEEN ' . $value1 . ' AND ' . $value2;
        return $this;
    }

    /**
     * Sort the selected rows in ascending or descending order according to the column chosen.
     * @param string $column
     * @param bool $asc
     * @return $this
     */
    public function order(string $column, bool $asc = true): self
    {
        $this->order[] = $column . ' ' . ($asc ? 'ASC' : 'DESC');
        return $this;
    }

    /**
     * Make a join between 2 tables.
     * @param string $table2
     * @param string $key1
     * @param string $key2
     * @return $this
     */
    public function inner(string $table2, string $key1, string $key2): self
    {
        if (empty($this->inner)) {
            $this->inner[] = $table2 . ' ON ' . explode(' ', $this->table)[0] . '.' . $key1 . ' = ' . $table2 . '.' . $key2;
        } else {
            $this->inner[] = $table2 . ' ON ' . explode(' ', $this->inner[sizeof($this->inner) - 1])[0] . '.' . $key1 . ' = ' . $table2 . '.' . $key2;
        }
        return $this;
    }

    /**
     * Set a limit in the selection of data.
     * @param int $limit
     * @param int|null $offset
     * @return $this
     */
    public function limit(int $limit, ?int $offset = null): self
    {
        $this->limit = $limit . ($offset ? ' OFFSET ' . $offset : '');
        return $this;
    }

    /**
     * Group data according to a column.
     * @param string $column
     * @return $this
     */
    public function group(string $column): self
    {
        $this->group = $column;
        return $this;
    }

    /**
     * Define the parameter values for the prepared query.
     * @param array $array
     * @return $this
     */
    public function params(array $array): self
    {
        $this->params = $array;
        return $this;
    }

    /**
     * Allows you to count the number of elements returned by the selection.
     * @param string|null $column
     * @param string|null $alias
     * @return $this
     */
    public function count(string $column = null, ?string $alias = null): self
    {
        $this->select = [];
        $this->select[] = 'COUNT(' . ($column ?? '*') . ')' . ($alias ? ' AS ' . $alias : '');
        return $this;
    }

    /**
     * Define the values to be inserted in the table.
     * @param array $values
     * @return $this
     */
    public function values(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Define the values to be updated in the table.
     * @param array $values
     * @return $this
     */
    public function set(array $values): self
    {
        return $this->values($values);
    }

    /**
     * Allows you to define which columns to return after the insertion or update.
     * @param string ...$columns
     * @return $this
     */
    public function returning(string...$columns): self
    {
        $this->returning = $columns;
        return $this;
    }

    /**
     * Condition in which the value of a column is in the list of values returned in another query.
     * @param string $column
     * @param \App\Database\Query $q
     * @return $this
     */
    public function whereIn(string $column, Query $q): self
    {
        $this->where[] = $column . ' IN (' . $q->__toString() . ')';
        return $this;
    }

    /**
     * Test if the request is an insertion.
     * @return bool
     */
    private function isInsert(): bool
    {
        return $this->type == 'INSERT';
    }

    /**
     * Test if the request is an update.
     * @return bool
     */
    private function isUpdate(): bool
    {
        return $this->type == 'UPDATE';
    }

    /**
     * Test if the request is an deletion.
     * @return bool
     */
    private function isDelete(): bool
    {
        return $this->type == 'DELETE';
    }

    public function __toString(): string
    {
        $parts = [$this->type];
        if ($this->isInsert()) {
            $parts[] = 'INTO';
        } else if (!$this->isUpdate() && !$this->isDelete()) {
            if ($this->distinct) {
                $parts[] = 'DISTINCT';
            }
            if ($this->select) {
                $parts[] = join(',', $this->select);
            } else {
                $parts[] = '*';
            }
        }
        if (!$this->isUpdate()) {
            $parts[] = 'FROM';
        }
        $parts[] = $this->table;
        if ($this->isInsert()) {
            $parts[] = '(' . join(',', $this->select) . ')';
            $parts[] = 'VALUES';
            $parts[] = '(' . join(',', $this->values) . ')';
        }
        if ($this->isUpdate()) {
            $parts[] = 'SET';
            $sets = [];
            foreach ($this->values as $k => $v) {
                $sets[] = $k . '=' . $v;
            }
            $parts[] = join(', ', $sets);
        }
        if (!empty($this->where)) {
            $parts[] = "WHERE";
            $parts[] = "(" . join(') AND (', $this->where) . ')';
        }
        if ($this->group) {
            $parts[] = 'GROUP BY ' . $this->group;
        }
        if (!empty($this->order)) {
            $parts[] = 'ORDER BY ' . join(', ', $this->order);
        }
        if ($this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }
        if (($this->isUpdate() || $this->isInsert()) && !empty($this->returning)) {
            $parts[] = 'RETURNING ' . join(',', $this->returning);
        }
        return join(' ', $parts);
    }

    /**
     *  Retrieve the first element returned by the request.
     * @return bool|object|array
     */
    public function first()
    {
        $request = $this->pdo->prepare($this->__toString() . ';');
        $request->execute($this->params);
        return $request->fetchObject();
    }

    /**
     * Retrieve all the elements returned by the request.
     * @return array
     */
    public function all(): array
    {
        $request = $this->pdo->prepare($this->__toString() . ';');
        $request->execute($this->params);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }
}