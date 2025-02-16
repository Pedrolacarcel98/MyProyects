<?php

require 'verifySession.php';

?>

<?php
session_start();
require 'connectDb2.php';

$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') as fechaFormateada FROM agenda WHERE tipo = 'FeriaS' and archivado != 1 ORDER BY fecha";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuadrante Feria Sevilla 2025</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos responsivos */
        body {
            font-family: Arial, sans-serif;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Ajustar encabezados y celdas para pantallas pequeñas */
        @media (max-width: 768px) {
            .table {
                font-size: 0.85rem;
            }

            th, td {
                white-space: nowrap;
                padding: 0.5rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .btn {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 1.2rem;
            }

            .btn {
                font-size: 0.8rem;
            }

            th:first-child,
            td:first-child {
                position: sticky;
                left: 0;
                background-color: #f8f9fa;
                z-index: 1;
            }

            th {
                font-size: 0.75rem;
            }
        }
    </style>
</head>


<body class="bg-light d-flex flex-column align-items-center py-4">

<div class="container">
    <h2 class="text-center text-primary mb-4">Cuadrante Feria Sevilla 2025</h2>

    <button onclick="history.back()" class="btn btn-secondary mb-4 w-100">Volver</button>

    <?php        
    $datos = mysqli_query($conn, $consulta);

    $eventos = [];
    while ($fila = mysqli_fetch_assoc($datos)) {
        $dia = date("j", strtotime($fila["fecha"]));  // Día del mes (ej. 5, 6, 7)
        $hora = date("H", strtotime($fila["fecha"]));  // Hora en formato HH

        // Almacena el evento en el array, usando día y hora como claves
        $eventos[$dia][$hora] = $fila["direccion"];  // Ajusta el campo según la información del evento
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <?php
                    $dias = ["Hora", 5, 6, 7, 8, 9, 10];
                    foreach ($dias as $dia) {
                        echo "<th>$dia</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Array de horas: de 19:00 a 23:00 y luego de 00:00 a 03:00
                $horas = array_merge(range(19, 23), range(0, 3));

                foreach ($horas as $hora) {
                    $hora_formateada = str_pad($hora, 2, "0", STR_PAD_LEFT) . ":00";
                    echo "<tr>";
                    echo "<td class='fw-bold bg-secondary text-white'>$hora_formateada</td>";

                    // Recorre los días de la semana para llenar cada celda
                    foreach ($dias as $dia) {
                        if ($dia === "Hora") continue; // Salta la primera columna de horas

                        // Muestra el evento si existe en el día y hora específica
                        if (isset($eventos[$dia][$hora])) {
                            echo "<td>" . htmlspecialchars($eventos[$dia][$hora]) . "</td>";
                        } else {
                            echo "<td>-</td>"; // Celda vacía si no hay evento
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
