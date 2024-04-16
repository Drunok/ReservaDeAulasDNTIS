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

    //Se modifica el estado de las solicitudes pendientes
    function getSolicitudes($conn) {
        $sql = "SELECT * FROM solicitud";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($solicitudes);
    }
    
    //Obtiene el id de las solicitudes atendidas
    function getFechaSolicitudesNoAtendidas($conn) {
        $sql = "SELECT fechasolicitud FROM solicitud WHERE solicitudfueaceptada = false AND revisionestapendiente = true";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $solicitudesNoAtendidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $fechas = array_map(function($solicitud) {
            return $solicitud['fechasolicitud'];
        }, $solicitudesNoAtendidas);
        echo json_encode($fechas);
        return $solicitudesNoAtendidas;
    }

    function atenderSolicitudesPendientes($conn, $fecha, $horaInicial, $horaFinal)
    {
        $sql = "UPDATE solicitud SET revisionestapendiente = 0 WHERE revisionestapendiente = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sqlAceptacion = "UPDATE solicitud SET solicitudfueaceptada = 1 WHERE solicitudfueaceptada = 0";
        $stmtAceptacion = $conn->prepare($sqlAceptacion);
        $stmtAceptacion->execute();

        $sqlConfirmarAmbiente = "UPDATE periodo_academico_disponible SET estadisponible = 0 WHERE estadisponible = 1 AND fechadisponible = :fecha AND horainicial = :horaInicialFormulario AND horafinal = :horaFinalFormulario";
        $stmtConfirmarAmbiente = $conn->prepare($sqlConfirmarAmbiente);
        $stmtConfirmarAmbiente->bindParam(':fecha', $fecha);
        $stmtConfirmarAmbiente->bindParam(':horaInicialFormulario', $horaInicial);
        $stmtConfirmarAmbiente->bindParam(':horaFinalFormulario', $horaFinal);
    }

    $fechasSolicitudesSinAtender = getFechaSolicitudesNoAtendidas($conn);
    // echo json_encode($fechasSolicitudesSinAtender);
    // echo json_encode ($fechasSolicitudesSinAtender[0]['fechasolicitud']);

}