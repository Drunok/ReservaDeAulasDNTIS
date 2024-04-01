<?php
class DbConnect {
    private $server = 'localhost';
    private $dbName = 'tisDB';
    private $user = 'postgres';
    private $pass = 'admin';

    public function connect() {
        try {
            $conn = new PDO("pgsql:host=$this->server;dbname=$this->dbName", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>