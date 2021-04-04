<?php


class Database
{
    private $name;
    private $user;
    private $password;
    private $host;

    /**
     * Database constructor.
     * @param string $name
     * @param string $user
     * @param string $password
     * @param string $host
     */
    public function __construct(string $name, string $user, string $password, string $host)
    {
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
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

    public function getPDO(): PDO
    {
        return new PDO("", "", "");
    }
}
