<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// error_log("debug script");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

include "dbConnect.php";

$datos = json_decode(file_get_contents('php://input'), true);

if ($datos === null) {
    echo ($datos);
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Los datos no se recibieron correctamente']);
    exit();
}

if (empty($datos["capacidad"]) || empty($datos["hora"]) || empty($datos["horaFinal"]) || empty($datos["fecha"])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Faltan campos requeridos en la solicitud']);
    exit();
}


// $datos = [
//     "capacidad" => $_POST["capacidad"],
//     "hora" => $_POST["hora"],
//     "horaFinal" => $_POST["horaFinal"],
//     "fecha" => $_POST["fecha"]
// ];

// echo json_encode($datos);



$capacidad = $datos["capacidad"];
$horaInicial = $datos["hora"];
$horaFinal = $datos["horaFinal"];
$fecha = $datos["fecha"];

// error_log($datos);
// var_dump($datos);
$objDb = new DbConnect();
$conn = $objDb->connect();
$valid = true;


// if (empty($datos["capacidad"]) || empty($datos["fecha"]) || empty($datos["hora"]) || empty($datos["horaFinal"])){
//     $valid = false;
// }
// {"capacidad":20,"hora":"13:30","horaFinal":"14:15","fecha":"2022-01-01"}

// $capacidad = $datos['capacidad'];
// $fecha = $datos['fecha'];
// $horaInicial = $datos['hora'];
// $horaFinal = $datos['horaFinal'];

$sentencia = $conn->prepare("INSERT INTO solicitud (idusuariomateria, cantestudiantes, fechasolicitud, motivo, aprobado, pendiente, observaciones, horainicial, horafinal)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");


$resultado = $sentencia->execute([1, $capacidad, $fecha, 'Prueba solicitud 1', false, true, 'Observaciones para la solicitud 1', $horaInicial, $horaFinal]);

if ($resultado === true) {
    // $response = ['status' => 'success', 'message' => 'Solicitud creada correctamente'];
    $valid = true;
    header('Content-Type: application/json');
    echo json_encode(['valid' => $valid]);
} else {
    // $response = ['status' => 'error', 'message' => 'Error al crear la solicitud'];
    $valid = false;
    http_response_code(500); // Internal Server Error
    echo json_encode(['valid' => $valid]);
}
header('Content-Type: application/json');
echo json_encode(['valid' => $valid]);
?>