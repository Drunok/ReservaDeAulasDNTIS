<?php

class DbConnect
{
    public function connect()
    {
        $server = 'localhost';
        $dbName = 'tisDB';
        $user = 'postgres';
        $pass = 'admin';
        $port = 5432;
        try {
            $dsn = "pgsql:host=$server; port=$port; dbname=$dbName";
            $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            if ($conn) {
                return $conn;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>