<?php

require_once 'config.php';
require_once 'response.php';

class Connection
{
    use ApiResponse;

    protected $pdo = null;

    public function __construct()
    {
        try {
            $host = Config::HOST;
            $connection = Config::CONNECTION;
            $port = Config::PORT;
            $username = Config::USERNAME;
            $password = Config::PASSWORD;
            $database = Config::DATABASE;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            if ($connection == 'mysql') {
                $this->pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $password, $options);
            } elseif ($connection == 'pgsql') {
                $this->pdo = new PDO("pgsql:host=$host;dbname=$database;port=$port", $username, $password, $options);
            }
        } catch (PDOException $e) {
            echo $this->response($e->getMessage(), null, 409);
        }

        return $this->pdo;
    }
}
