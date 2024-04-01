<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'dbConnect.php';
    $db = new DbConnect();
    $conn = $db->connect();
    $sql = 'SELECT * FROM ambiente';
    $stmt = $conn->query($sql);
    $classrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['classrooms' => $classrooms]);
}

?>