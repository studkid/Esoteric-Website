<?php
//by Dominic Dorenkamp
$type = "mysql";
$server = "localhost";
$database = "esotericemporium"; //database name
$port = 3306;
$charset = "utf8mb4";

$username = "user";
$password = "Password123$";

$options = [
    PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES      => false,
];

$dsn = "$type:host=$server;dbname=$database;port=$port;charset=$charset";

try
{
    $pdo = new PDO($dsn, $username, $password, $options);
}
catch (PDOException $e)
{
    throw new PDOException($e->getMessage(), $e->getCode()); //during testing 
    //throw new PDOException("Unable to connect to the database.", $e->getCode()); //during live site
}
?>