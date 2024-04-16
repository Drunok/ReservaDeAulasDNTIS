<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //se obtiene todos los datos del formulario
    $data = json_decode(file_get_contents('php://input'));

    //Informacion del formulario
    $formData = $data->formData;

    // $nombreDocente = 'Leticia Blanco';
    $capacidad = $formData->capacidad;
    $fecha = $formData->fecha;
    $horaInicial = $formData->hora;
    $horaFinal = $formData->horaFinal;
    // $motivo = $formData->motivo;
    // $ambiente = $formData->ambiente;
    $pendiente = 1;
    $aprobado = 0;
    $esUrgente = 0;

    //Se valida que no haya campos vacios
    if (empty($capacidad) || empty($fecha) || empty($horaInicial) || empty($horaFinal)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Faltan campos requeridos en la solicitud']);
        exit();
    }

    //Conexion a la base de datos
    require 'newDBConnect.php';
    $newDB = new NewDBConnect();
    $conn = $newDB->connect();

    //se obtiene el id del ambiente
    // function getIdAmbienteByName($conn, $ambiente)
    // {
    //     $sql = "SELECT idambiente FROM ambiente WHERE nombreambiente = :nombre";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindValue(':nombre', $ambiente);
    //     $stmt->execute();
    //     $idAmbiente = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $idAmbiente['idambiente'];
    // }

    //Se obtiene la informacion de los ambientes disponibles en los horarios establecidos
    //por el docente
    function getInfoPeriodoAcademicoDisponible($conn, $fecha, $horaInicial, $horaFinal) {
        $sql = "SELECT * FROM periodo_academico_disponible WHERE 
             fechadisponible = :fecha AND horadisponibleinicial = :horaInicial AND horadisponiblefinal = :horaFinal AND estadisponible = true";
        $stmt = $conn->prepare($sql);
        // $stmt->bindValue(':idAmbiente', $idAmbiente);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':horaInicial', $horaInicial);
        $stmt->bindValue(':horaFinal', $horaFinal);
        
        $stmt->execute();
        $idPeriodoAcademico = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode(['periodo' => $idPeriodoAcademico['idperiodoacademicodisponible']]);
        // return $idPeriodoAcademico[0]['idambiente'];
        return $idPeriodoAcademico;
    }

    

    
    // $idAmbientePost = getIdAmbienteByName($conn, $ambiente);
    $periodo = getInfoPeriodoAcademicoDisponible($conn, $fecha, $horaInicial, $horaFinal);
    // $idAmbiente = $periodo -> idambiente;
    function getNombreCapacidadAmbientesDisponibles($conn, $infoPeriodoAcademicoDisponible) {
        foreach ($infoPeriodoAcademicoDisponible as $periodo) {
            $idAmbiente = $periodo['idambiente'];
            $sql = "SELECT nombreambiente, capacidadambiente FROM ambiente WHERE idambiente = :idAmbiente";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idAmbiente', $idAmbiente);
            $stmt->execute();
            $infoAmbiente = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $infoAmbiente['idambiente'] = $idAmbiente;
            // echo json_encode($infoAmbiente);
            // $capacidad = $infoAmbiente['capacidadambiente'];
            // $nombre = $infoAmbiente['nombreambiente'];
            echo json_encode(['infoAmbiente' => $infoAmbiente]);
        }
    }

    getNombreCapacidadAmbientesDisponibles($conn, $periodo);

    //Se obtiene la informacion de los ambientes disponibles en los horarios establecidos


    
    

    

    

   
}