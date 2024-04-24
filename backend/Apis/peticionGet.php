<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");


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

    if (empty($reservas)) {
        echo json_encode([$ambientes]);
    } else {
        foreach ($solicitudesArreglo[0] as $solicitud) {
            if (!$solicitud['aprobado']) {
                $capacidadSolicitud = $solicitud['cantestudiantes'];
                $idsolicitud = $solicitud['idsolicitud'];
                getAmbientesparaSolicitud($ambientesArreglo, $reservasArreglo, $idsolicitud, $capacidadSolicitud, $solicitudesArreglo);
            }
        }
    }

}

function getAmbientesparaSolicitud($ambientes, $reservas, $idsolicitud, $cantEstudiantes, $solicitudes)
{
    foreach ($ambientes[0] as $ambiente) {
        if ($ambiente['capacidad'] >= $cantEstudiantes) {
            if (!estaReservado($reservas, $ambiente, $solicitudes)) {
                echo json_encode([
                    'solicitud'=>$idsolicitud,
                    'cantidadEstudiantes'=>$cantEstudiantes,
                    'ambientes'=>$ambiente
                ]);
            }
        }
    }
}

function estaReservado($reservas, $ambienteObservado, $solicitudes)
{
    $res = false;
    if (empty($reservas)) {
        $res = false;
    } else {
        foreach ($reservas[0] as $ambienteReservado) {
            if ($ambienteReservado['nombreambiente'] == $ambienteObservado['nombreambiente']) {
                foreach ($solicitudes[0] as $solicitud) {
                    if (fechaOcupada($solicitudes, $solicitud) && $solicitud['aprobado']) {
                         return true;
                    }
                }
            }
        }
    }
    return $res;
}

function fechaOcupada($arregloSolicitudes, $solicitud)
{
    $res = false;
    foreach ($arregloSolicitudes[0] as $otraSolicitud) {
        if ($otraSolicitud !== $solicitud) {
            $fechaSolicitud = $solicitud['fechasolicitud'];
            $horaSolicitud = $solicitud['horainicial'];
            $horaFinalSolicitud = $solicitud['horafinal'];
            if ($otraSolicitud['fechasolicitud'] === $fechaSolicitud && $otraSolicitud['horainicial'] === $horaSolicitud && $otraSolicitud['horafinal'] === $horaFinalSolicitud) {
                return true;
            } else {
                $res = false;
            }
        }
    }
    return $res;
}


?>