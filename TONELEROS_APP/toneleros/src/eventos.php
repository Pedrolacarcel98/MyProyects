<?php
require 'verifySession.php';
require 'connectDb2.php';

header('Content-Type: application/json; charset=utf-8');

// Parámetros opcionales
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$excluirFeriaS = true; // como en tu consulta original
$soloNoArchivado = true;

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Asegura charset
mysqli_set_charset($conn, 'utf8mb4');

// Construir SQL con filtros
$sql = "
  SELECT 
    id,
    direccion,
    tipo,
    archivado,
    fecha
  FROM agenda
  WHERE 1=1
";

$params = [];
$types  = '';

if ($excluirFeriaS) {
    $sql   .= " AND (tipo IS NULL OR tipo <> 'FeriaS') ";
}

if ($soloNoArchivado) {
    $sql   .= " AND (archivado IS NULL OR archivado <> 1) ";
}

if ($tipo !== '') {
    $sql   .= " AND tipo = ? ";
    $types .= 's';
    $params[] = $tipo;
}

$sql .= " ORDER BY fecha ";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error preparando la consulta']);
    exit;
}
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$events = [];
while ($fila = mysqli_fetch_assoc($res)) {
    // fecha puede ser DATE o DATETIME
    $fechaRaw = $fila['fecha'];
    $startISO = null;
    $allDay   = false;
    try {
        $dt = new DateTime($fechaRaw);
        $startISO = $dt->format(DateTime::ATOM); // ISO 8601
        // Si tu columna es solo DATE (sin hora), marcamos allDay
        $allDay = (strlen($fechaRaw) <= 10);
        $hora   = $allDay ? '' : $dt->format('H:i');
    } catch (Throwable $e) {
        continue; // ignora filas con fecha inválida
    }

    $tituloBase = trim($fila['direccion'] ?? '');
    $titulo = $hora ? ($tituloBase . ' ' . $hora) : ($tituloBase ?: 'Actuación');

    $events[] = [
        'id'    => (string)$fila['id'],
        'title' => $titulo,
        'start' => $startISO,
        'allDay'=> $allDay,
        // metadatos extra para tooltips / filtros
        'extendedProps' => [
            'tipo'      => (string)($fila['tipo'] ?? ''),
            'direccion' => (string)($fila['direccion'] ?? ''),
            'hora'      => $hora
        ]
    ];
}
mysqli_free_result($res);
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Devuelve JSON
echo json_encode($events, JSON_UNESCAPED_UNICODE);
