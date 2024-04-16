<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

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

    

    function atenderSolicitudesPendientes($conn)
    {
        $numSolicitudesSinAtender = getNumSolicitudesSinAtender($conn);
        $idsSolicitudesSinAtender = getIdsSolicitudesSinAtender($conn);

        $sql = "UPDATE solicitud SET revisionestapendiente = false WHERE revisionestapendiente = true";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sqlAceptacion = "UPDATE solicitud SET solicitudfueaceptada = true WHERE solicitudfueaceptada = false";
        $stmtAceptacion = $conn->prepare($sqlAceptacion);
        $stmtAceptacion->execute();
        
        foreach ($idsSolicitudesSinAtender as $idSolicitud) {
            $sqlGuardarRespuesta = "INSERT INTO respuesta_solicitud (idsolicitud, motivodenoreserva, fecharevision) VALUES (:idSolicitud, :motivonoreserva, :fecha)";
            $stmtGuardarRespuesta = $conn->prepare($sqlGuardarRespuesta);
            $stmtGuardarRespuesta->bindValue(':idSolicitud', $idSolicitud);
            $stmtGuardarRespuesta->bindValue(':motivonoreserva', 'si se acepto');
            $stmtGuardarRespuesta->bindValue(':fecha', date('Y-m-d H:i:s'));
            $stmtGuardarRespuesta->execute();
        }
        return $numSolicitudesSinAtender;
    }

    function getNumSolicitudesSinAtender($conn) {
        $sql = "SELECT COUNT(*) FROM solicitud WHERE revisionestapendiente = true";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $numSolicitudesSinAtender = $stmt->fetch(PDO::FETCH_ASSOC);
        return $numSolicitudesSinAtender['count'];
    }

    function getIdsSolicitudesSinAtender($conn) {
        $sql = "SELECT idsolicitud FROM solicitud WHERE revisionestapendiente = true";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $idsSolicitudesSinAtender = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $idsSolicitudesSinAtender = array_map(function($solicitud) {
            return $solicitud['idsolicitud'];
        }, $idsSolicitudesSinAtender);
        return $idsSolicitudesSinAtender;
    }

    $solicitudesAtendidas = atenderSolicitudesPendientes($conn);

    echo json_encode(['solicitudesAtendidas' => $solicitudesAtendidas]);

}