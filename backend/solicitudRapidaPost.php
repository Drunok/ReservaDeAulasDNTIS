

<?php
//ESTE FUNCIONA CON LA NUEVA BD
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

    //Conexion a la base de datos
    require 'newDBConnect.php';
    $objDb = new NewDbConnect();
    $conn = $objDb->connect();

    if (empty($capacidad) || empty($fecha) || empty($horaInicial) || empty($horaFinal) || empty($motivo) || empty($ambiente)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Faltan campos requeridos en la solicitud']);
        exit();
    }

    //Creacion solicitud en la base de datos

    function getIdAmbienteByName($conn, $ambiente)
    {
        $sql = "SELECT idambiente FROM ambiente WHERE nombreambiente = :nombre";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', $ambiente);
        $stmt->execute();
        $idAmbiente = $stmt->fetch(PDO::FETCH_ASSOC);
        $idAmbiente = $idAmbiente['idambiente'];
        return $idAmbiente;
    }

    $idAmbiente = getIdAmbienteByName($conn, $ambiente);

    $sql = "INSERT INTO solicitud (idambiente, capacidadsolicitud, fechasolicitud, horainicialsolicitud, horafinalsolicitud, revisionestapendiente, solicitudfueaceptada, esurgente, bitacorafechasolicitud) 
            VALUES (:idambiente, :capacidadsol, :fechasol, :horaIni, :horaFin, :pendiente, :fueaceptada, :esurgente, :bitacorafecha)";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':idambiente', $idAmbiente);
    $stmt->bindValue(':capacidadsol', $capacidad);
    $stmt->bindValue(':fechasol', $fecha);
    $stmt->bindValue(':horaIni', $horaInicial);
    $stmt->bindValue(':horaFin', $horaFinal);
    $stmt->bindValue(':pendiente', $pendiente);
    $stmt->bindValue(':fueaceptada', $aprobado);
    $stmt->bindValue(':esurgente', $esUrgente);
    $stmt->bindValue(':bitacorafecha', date('Y-m-d H:i:s'));

    $sqlSolicitud = 'SELECT * FROM solicitud';
    $stmtSolicitud = $conn->query($sqlSolicitud);
    $solicitudes = $stmtSolicitud->fetchAll(PDO::FETCH_ASSOC);

    //Se comprueba que no exista una solicitud para el mismo ambiente, fecha, hora inicial y hora final
    function validarSolicitud($solicitudes, $idAmbiente, $fecha, $horaInicial, $horaFinal)
    {
        $fecha = new DateTime($fecha);
        $horaInicial = new DateTime($horaInicial);
        $horaFinal = new DateTime($horaFinal);

        foreach ($solicitudes as $solicitud) {
            $fechaSolicitud = new DateTime($solicitud['fechasolicitud']);
            $horaInicialSolicitud = new DateTime($solicitud['horainicialsolicitud']);
            $horaFinalSolicitud = new DateTime($solicitud['horafinalsolicitud']);

            if ($solicitud['idambiente'] == $idAmbiente && $fechaSolicitud == $fecha) {
                if ($horaInicialSolicitud == $horaInicial && $horaFinalSolicitud == $horaFinal) {
                    return false;
                }
            }
        }
        return true;
    }

    $validation = validarSolicitud($solicitudes, $idAmbiente, $fecha, $horaInicial, $horaFinal);

    //Se obtiene informacion de la tabla solicitud, no se usa, mas que todo es con fines de debug
    function revisarTablaSolicitud($conn) {
        $sql = "SELECT * FROM solicitud";
        $stmt = $conn->query($sql);
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $solicitudes;
    }

    $solicitudes = revisarTablaSolicitud($conn);

    require 'solicitudDocentePost.php';

    //Se inserta la solicitud rapida en la base de datos, ingresando la informacion en las tablas solicitud y solicitud_docente

    if ($validation) {
        //ejecuta la consulta y sube los datos a la tabla solicitud
        $stmt->execute();
        $lastIdSolicitud = getLastIdSolicitud($conn);
        echo json_encode(['result' => true]);
        $idDocentePost = getIdDocenteByName($conn, $nombreDocente);
        $idMotivoPost = getIdMotivoByName($conn, $motivo);
        emparejarDocenteMotivo($conn, $idDocentePost, $idMotivoPost);
        $idDocenteMotivo = getIdDocenteMotivoEmparejados($conn, $idDocentePost, $idMotivoPost);
        emparejarSolicitudDocenteMotivo($conn, $idDocenteMotivo, $lastIdSolicitud);

    } else {
        echo json_encode(['result' => false]);
    }


}
?>