<?php

namespace App\Database;

class Database
{
    private $name;
    private $user;
    private $password;
    private $host;
    private $port;

    /**
     * Database constructor.
     * @param string $name
     * @param string $user
     * @param string $password
     * @param string $host
     * @param int $port
     */
    public function __construct(string $name, string $user, string $password, string $host, int $port)
    {
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
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
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    private static $instance;

    /**
     * @return \PDO
     */
    public static function get(): \PDO
    {
        return self::$instance;
    }

    /**
     * @param \App\Database\Database $database
     */
    public static function set(self $database)
    {
        self::$instance = new \PDO("pgsql:host=$database->host;port=$database->port;dbname=$database->name", $database->user, $database->password);
    }
}
