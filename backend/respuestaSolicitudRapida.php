<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    //Informacion del formulario
    $formData = $data->formData;

    $nombreDocente = 'Leticia Blanco';
    $capacidad = $formData->capacidad;
    $fecha = $formData->fecha;
    $horaInicial = $formData->hora;
    $horaFinal = $formData->horaFinal;
    $motivo = $formData->motivo;
    $ambiente = $formData->ambiente;
    $pendiente = 1;
    $aprobado = 0;
    $esUrgente = 0;

    //Se valida que no haya campos vacios
    if (empty($capacidad) || empty($fecha) || empty($horaInicial) || empty($horaFinal) || empty($motivo) || empty($ambiente)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Faltan campos requeridos en la solicitud']);
        exit();
    }

    //Conexion a la base de datos
    require 'newDBConnect.php';
    $newDB = new NewDBConnect();
    $conn = $newDB->connect();

    function atenderSolicitudesPendientes($conn)
    {
        $sql = "UPDATE solicitud SET revisionestapendiente = 0 WHERE revisionestapendiente = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sqlAceptacion = "UPDATE solicitud SET solicitudfueaceptada = 1 WHERE solicitudfueaceptada = 0";
        $stmtAceptacion = $conn->prepare($sqlAceptacion);
        $stmtAceptacion->execute();

        // $sqlConfirmarAmbiente = "UPDATE periodo_academico_disponible SET estadisponible = 0 WHERE estadisponible = 1 AND fechadisponible = ";

    }

}