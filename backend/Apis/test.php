<?php
// Permite solicitudes de cualquier origen
header("Access-Control-Allow-Origin: http://localhost:3000");

// Permite los métodos HTTP que quieres usar
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permite los encabezados que vas a usar en tus solicitudes
header("Access-Control-Allow-Headers: Content-Type");

// Si el método es OPTIONS, termina aquí
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

// Asegúrate de que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// Obtiene los datos del formulario desde el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Valida los datos aquí. Este es solo un ejemplo, deberías hacer tu propia validación.
$valid = true;
if (empty($data['capacidad']) || empty($data['fecha']) || empty($data['hora']) || empty($data['horaFinal'])) {
    $valid = false;
}

// Devuelve una respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode(['valid' => $valid]);
?>