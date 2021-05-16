<?php


namespace App\Database;


use App\Exceptions\InvalidParamException;
use App\Exceptions\ProtectFieldException;
use App\Exceptions\UnknownFieldException;

class QueryApi extends Query
{
    private $fields;
    private $defaults;
    private $order_default;
    private $protect;

    public function setPossibility(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function setDefault(array $fields): self
    {
        $this->defaults = $fields;
        return $this;
    }

    public function setOrder(string $column): self
    {
        $this->order_default = $column;
        return $this;
    }

    public function setProtect(array $protect): self
    {
        $this->protect = $protect;
        return $this;
    }

    /**
     * @throws UnknownFieldException
     * @throws InvalidParamException
     * @throws ProtectFieldException
     */
    public function build()
    {
        if (isset($_GET['fields'])) {
            $f = htmlspecialchars($_GET['fields']);
            $f = explode(',', $f);
            $this->isInArray($f, $this->fields);
            $this->isProtect($f, $this->protect);
        }
        $this->selectArray($f ?? $this->defaults);
        if (isset($_GET['order'])) {
            $o = htmlspecialchars($_GET['order']);
            if (!in_array($o, $this->fields)) {
                throw new UnknownFieldException($o);
            }
        }
        if (isset($_GET['by'])) {
            $by = strtoupper(htmlspecialchars($_GET['by']));
            if (!in_array($by, ['ASC', 'DESC'])) {
                throw new InvalidParamException($by, 'ASC or DESC');
            }
        }
        if (isset($_GET['only'])) {
            $only = htmlspecialchars($_GET['only']);
            $parts = explode(':', $only);
            if (sizeof($parts) != 2) {
                throw new InvalidParamException('only', 'key:value');
            }
            if (!in_array($parts[0], $this->fields)) {
                throw new UnknownFieldException($parts[0]);
            }
            $this->where(htmlspecialchars($parts[0]) . " = ?");
            $this->params([$parts[1]]);
        }
        if(isset($_GET['count'])){
            $this->count();
        }
        if (!is_null($this->order_default) || isset($o)) {
            $this->order($o ?? $this->order_default, $by ?? 'ASC');
        }
    }

    /**
     * @throws UnknownFieldException
     */
    private function isInArray(array $array1, $array2)
    {
        foreach ($array1 as $field) {
            if (!in_array($field, $array2)) {
                throw new UnknownFieldException($field);
            }
        }
    }

    /**
     * @throws ProtectFieldException
     */
    private function isProtect(array $f, $protect)
    {
        if(is_null($this->protect)){
            return;
        }
        foreach ($f as $v) {
            if (in_array($v, $protect)) {
                throw new ProtectFieldException($v);
            }
        }
    }
}
