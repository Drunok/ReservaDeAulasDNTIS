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

// Si los datos son válidos, realiza la consulta a la base de datos
if ($valid) {

    require 'dbConnect.php';
    $db = new DbConnect();
    $conn = $db->connect();

    $stmt = $conn->prepare("
    SELECT a.nombreambiente, a.ubicacion, a.capacidad
    FROM ambiente a
    WHERE a.capacidad >= :capacidad
    AND a.nombreambiente NOT IN (
        SELECT sa.nombreambiente
        FROM solicitud_ambiente sa
        JOIN solicitud s ON sa.idsolicitud = s.idsolicitud
        WHERE s.fechasolicitud = :fecha
        AND s.horainicial < :horaFinal
        AND s.horafinal > :hora
    )
    ");

    $stmt->execute([
        'capacidad' => $data['capacidad'],
        'fecha' => $data['fecha'],
        'hora' => $data['hora'],
        'horaFinal' => $data['horaFinal']
    ]);

    $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    

    // $aulas = ['690A, 690B'];


    // Devuelve los resultados en formato JSON
    if (empty($aulas)) {
        // No se encontraron aulas, devuelve un mensaje de error o un estado diferente
        http_response_code(404);
        echo json_encode(['message' => 'No se encontraron aulas']);
    } else {
        // Se encontraron aulas, devuelve los resultados en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['aulas' => $aulas]);
    }
} else {
    // Si los datos no son válidos, devuelve un error
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
}
?>