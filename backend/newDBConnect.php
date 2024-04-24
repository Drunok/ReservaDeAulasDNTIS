<?php

class NewDBConnect
{
    public function connect()
    {
        $server = 'localhost';
        $dbName = 'tisDBoficial';
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