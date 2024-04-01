<?php

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Methods: GET, POST");

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     $db = new DbConnect();
//     echo $db->connect();
// }
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
                
                // return json_encode([
                //     'status' => 'success',
                //     'message'=> 'Connection successful']);
                return $conn;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>