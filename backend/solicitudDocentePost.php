<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function getLastIdSolicitud($conn)
    {
        $sql = "SELECT MAX(idsolicitud) FROM solicitud";
        $stmt = $conn->query($sql);
        $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($lastId['max'] == null) {
            $lastId['max'] = 0;
        }
        $lastId = $lastId['max'];
        // echo json_encode($lastId);
        return $lastId;
    }
    

    
    function getIdMotivoByName($conn, $motivo)
    {
        $sql = "SELECT idmotivo FROM motivo WHERE motivosolicitud = :nombreMotivo";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombreMotivo', $motivo);
        $stmt->execute();
        $idMotivo = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo json_encode(['idmotivo' => $idMotivo]);
        // $idMotivo = $idMotivo['iddocentemotivo'];
        // $idMotivo = $idMotivo -> idmotivo;
        return $idMotivo;
    }
    

    function getIdDocenteByName($conn, $nombreDocente)
    {
        $sql = "SELECT iddocente FROM docente WHERE nombredocente = :nombreDocente";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombreDocente', $nombreDocente);
        $stmt->execute();
        $idDocente = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo json_encode(['iddocente' => $idDocente]);
        // $idDocente = $idDocente['iddocente'];
        // $idDocente = $idDocente -> iddocente;
        return $idDocente;
    }

    function getIdDocenteMotivoEmparejados($conn, $idDocente, $idMotivo) {
        $sql = 'SELECT iddocentemotivo FROM docente_motivo WHERE iddocente = :idDocente AND idmotivo = :idMotivo';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idDocente', $idDocente['iddocente']);
        $stmt->bindValue(':idMotivo', $idMotivo['idmotivo']);
        $stmt->execute();
        $idDocenteMotivo = $stmt->fetch(PDO::FETCH_ASSOC);
        $idDocenteMotivo = $idDocenteMotivo['iddocentemotivo'];
        // echo json_encode(['idDocenteMotivo' => $idDocenteMotivo]);
        return $idDocenteMotivo;
    }

    function emparejarSolicitudDocenteMotivo($conn, $idDocenteMotivo, $lastId)
    {
        // $idDocenteMotivo = getIdDocenteMotivoEmparejados($conn, $nombreDocente, $motivo);
        $sqlSolicitudDocente = 'INSERT INTO solicitud_docente (idsolicitud,iddocentemotivo) VALUES (:idsolicitud, :iddocentemotivo)';
        $stmtSolicitudDocente = $conn->prepare($sqlSolicitudDocente);
        $stmtSolicitudDocente->bindValue(':idsolicitud', $lastId);
        $stmtSolicitudDocente->bindValue(':iddocentemotivo', $idDocenteMotivo);
        // echo json_encode([$lastId, $idDocenteMotivo]);
        echo json_encode(['id solicitud emparejada' => $lastId, 'id docente motivo emparejado' => $idDocenteMotivo]);
        $stmtSolicitudDocente->execute();

        $sql = "SELECT * FROM solicitud_docente";
        $stmt = $conn->query($sql);
        $solicitudDocente = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['solicitudDocente' => $solicitudDocente]);
    }

    function emparejarDocenteMotivo($conn, $idDocente, $idMotivo) {
        $sqlDocenteMotivo = "INSERT INTO docente_motivo (iddocente, idmotivo) VALUES (:iddocente, :idmotivo)";
        $stmtDocenteMotivo = $conn->prepare($sqlDocenteMotivo);
        $stmtDocenteMotivo->bindValue(':iddocente', $idDocente['iddocente']);
        $stmtDocenteMotivo->bindValue(':idmotivo', $idMotivo['idmotivo']);
        // echo json_encode([$idDocente['iddocente'], $idMotivo['idmotivo']]);
        $stmtDocenteMotivo->execute();

        $sql = "SELECT * FROM docente_motivo";
        $stmt = $conn->query($sql);
        $docenteMotivo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['docenteMotivo' => $docenteMotivo]);
    }
}