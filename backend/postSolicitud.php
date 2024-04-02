<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    $selected = $data->selected;
    $formData = $data->formData;

    $idUsuarioMateria = 1;
    $capacidad = $formData->capacidad;
    $fecha = $formData->fecha;
    $motivo = 'reservado para examen';
    $noAprobado = 0;
    $aprobado = 1;
    $observaciones = 'ninguna';
    $horaInicial = $formData->hora;
    $horaFinal = $formData->horaFinal;

    // echo json_encode([$capacidad, $fecha, $hora, $horaFinal]);

    require 'dbConnect.php';
    $objDb = new DbConnect();
    $conn = $objDb->connect();

    if (empty($capacidad) || empty($fecha) || empty($horaInicial) || empty($horaFinal)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Faltan campos requeridos en la solicitud']);
        exit();
    }

    $sql = "INSERT INTO solicitud (idusuariomateria, cantestudiantes, fechasolicitud, motivo, aprobado, pendiente, observaciones, horainicial, horafinal) VALUES (:idusuariomateria, :capacidad, :fecha, :motivo, :aprobado, :pendiente, :observaciones, :horaIni, :horaFin)";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':idusuariomateria', $idUsuarioMateria);
    $stmt->bindValue(':capacidad', $capacidad);
    $stmt->bindValue(':fecha', $fecha);
    $stmt->bindValue(':motivo', $motivo);
    $stmt->bindValue(':aprobado', $noAprobado);
    $stmt->bindValue(':pendiente', $aprobado);
    $stmt->bindValue(':observaciones', $observaciones);
    $stmt->bindValue(':horaIni', $horaInicial);
    $stmt->bindValue(':horaFin', $horaFinal);

    
    $sqlSolicitud = 'SELECT * FROM solicitud';
    $stmtSolicitud = $conn->query($sqlSolicitud);
    $solicitudes = $stmtSolicitud->fetchAll(PDO::FETCH_ASSOC);
    // $solicitudesJson = json_encode([$solicitudes]);
    // $solicitudesArreglo = json_decode($solicitudesJson, true);

    function validarSolicitud($solicitudes, $idUsuarioMateria, $fecha, $horaInicial, $horaFinal) {
        $fecha = new DateTime($fecha);
        $horaInicial = new DateTime($horaInicial);
        $horaFinal = new DateTime($horaFinal);
    
        foreach($solicitudes as $solicitud) {
            $fechaSolicitud = new DateTime($solicitud['fechasolicitud']);
            $horaInicialSolicitud = new DateTime($solicitud['horainicial']);
            $horaFinalSolicitud = new DateTime($solicitud['horafinal']);
    
            if ($solicitud['idusuariomateria'] == $idUsuarioMateria && $fechaSolicitud == $fecha && $horaInicialSolicitud == $horaInicial && $horaFinalSolicitud == $horaFinal) {
                return false;
            }
        }    
        return true;
    }
    
    $validation = validarSolicitud($solicitudes, $idUsuarioMateria, $fecha, $horaInicial, $horaFinal);
    if ($validation) {
        $stmt->execute();
        echo json_encode(['result' => true]);
    } else {
        echo json_encode(['result' => false]);
    }
}
?>