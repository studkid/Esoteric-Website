<?php

class DatabaseConnection
{
    protected $type = "mysql";
    protected $server = "localhost";
    protected $database = "esotericemporium";
    protected $port = 3306;
    protected $charset = "utf8mb4";

    protected $username = "root";
    protected $password = "";

    protected $options = [
        \PDO::ATTR_ERRMODE               => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE    => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES      => false,
    ];

    protected $dsn;
    protected $pdo;

    public function connect()
    {
        try 
        {
            $this->dsn = "$this->type:host=$this->server;dbname=$this->database;port=$this->port;charset=$this->charset";
            $this->pdo = new \PDO( $this->dsn, $this->username, $this->password, $this->options );
        } 
        catch(\PDOException $e) 
        {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function getPDO() : \PDO
    {
        return $this->pdo;
    }
}