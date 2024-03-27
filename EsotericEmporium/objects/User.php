<?php
include 'utils/DatabaseConnection.php';
include 'utils/DBUtil.php';

class User 
{
    private $username;
    private $password;
    private $role;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = "User";
    }

    public function getUsername() : string 
    {
        return $this->username;
    }

    public function getRole() : string 
    {
        return $this->role;
    }

    public function register() : bool
    {
        try
        {
            $sql = "INSERT INTO EEUser (username, password, role) 
                    VALUES (:username, :password, :role);";

            $values = [$this->username, $this->password, $this->role];
            $dbConnection = new DatabaseConnection();
            $dbConnection->connect();
            pdo($dbConnection->getPDO(), $sql, $values);

        }
        catch (Exception $e)
        {
            throw $e;
        }

        return true;
    }

    public function login() : bool
    {
        try
        {
            $sql = "SELECT username, password, role FROM EEUser WHERE username = :username;";
            $values = [$this->username];
            $dbConnection = new DatabaseConnection();
            $dbConnection->connect();
            $statement = pdo($dbConnection->getPDO(), $sql, $values);

            $userFetched = $statement->fetch();
            $storedPassword = $userFetched['password'] ?? "";
            $authenticated = password_verify($this->password, $storedPassword);

            if($authenticated)
            {
                $this->username = $userFetched['username'];
                $this->role = $userFetched['role'];
            }
        }
        catch (Exception $e)
        {
            throw $e;
            return false;
        }

        return $authenticated;
    }
}

?>