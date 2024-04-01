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

    $sqlAmbiente = 'SELECT * FROM ambiente';
    $stmtAmbiente = $conn->query($sqlAmbiente);
    $ambientes = $stmtAmbiente->fetchAll(PDO::FETCH_ASSOC);

    $sqlSolicitudAmbiente = 'SELECT * FROM solicitud_ambiente';
    $stmtSolicitudAmbiente = $conn->query($sqlSolicitudAmbiente);
    $reservas = $stmtSolicitudAmbiente->fetchAll(PDO::FETCH_ASSOC);

    $sqlSolicitud = 'SELECT * FROM solicitud';
    $stmtSolicitud = $conn->query($sqlSolicitud);
    $solicitudes = $stmtSolicitud->fetchAll(PDO::FETCH_ASSOC);

    $solicitudesJson = json_encode([$solicitudes]);
    $solicitudesArreglo = json_decode($solicitudesJson, true);

    $ambientesJson = json_encode([$ambientes]);
    $ambientesArreglo = json_decode($ambientesJson, true);

    $reservasJson = json_encode([$reservas]);
    $reservasArreglo = json_decode($reservasJson, true);

    // echo json_encode(['reservados'=>$reservas]);
    // echo json_encode(['solicitudes'=>$solicitudes]);
    // echo json_encode(['reservas'=>$reservas]);

    if (empty($reservas)) {
        echo json_encode([$ambientes]);
    } else {
        foreach($solicitudesArreglo[0] as $solicitud){
            if(!$solicitud['aprobado']) {
                // echo json_encode([$solicitud]);
                $capacidadSolicitud = $solicitud['cantestudiantes'];
                getAmbientesparaSolicitud($ambientesArreglo, $capacidadSolicitud, $reservasArreglo);
            }
        }
    }

}

function getAmbientesparaSolicitud ($ambientes, $cantEstudiantes, $reservas) {
    foreach($ambientes[0] as $ambiente) {
        if($ambiente['capacidad'] >= $cantEstudiantes) {
            if (!estaReservado($reservas, $ambiente)) {
                echo json_encode([$ambiente]);
            }
        }
    }
}

function estaReservado ($reservas, $ambienteObservado) {
    if (empty($reservas)) {
        return false;
    } else {
        foreach($reservas[0] as $ambienteReservado) {
            if($ambienteReservado['nombreambiente'] == $ambienteObservado['nombreambiente']) {
                return true;
            } else {
                return false;
            }    
        }
    }
}


?>