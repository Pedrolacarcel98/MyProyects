<?php

require 'verifySession.php';

?>
<?php
session_start();
require 'connectDb2.php';

// Consulta para obtener los eventos
$consulta = "SELECT *, DATE_FORMAT(fecha, '%Y-%m-%d') AS fechaFormateada 
             FROM agenda 
             WHERE tipo != 'FeriaS' AND archivado != 1 
             ORDER BY fecha";

$events = [];

// Verifica la conexión antes de ejecutar la consulta
if ($conn) {
    $datos = mysqli_query($conn, $consulta);

    while ($fila = mysqli_fetch_assoc($datos)) {
        $date = new DateTime($fila['fecha']); 
        $hora = $date->format('H:i');

        $titulo = $fila['direccion'] . " " . $hora;
        $events[] = [
            'title' => $titulo,
            'start' => $fila['fechaFormateada'],
            'end'   => $fila['fechaFormateada']
        ];
    }
} else {
    die("Error de conexión a la base de datos.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario con FullCalendar</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="css/calendario.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>

    <!-- Botón de volver -->
    <button onclick="history.back()" class="btn btn-secondary w-100">Volver</button>

    <!-- Contenedor del calendario -->
    <div id="calendar"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("calendar");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,listMonth"
                },
                buttonText: { 
                    month: "Mes",
                    week: "Semana",
                    day: "Día",
                    list: "Lista"
                },
                locale: "es",
                firstDay: 1,
                events: <?php echo json_encode($events); ?>
            });
            calendar.render();
        });
    </script>

</body>
</html>
