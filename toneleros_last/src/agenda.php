<?php
require 'verifySession.php';
require 'connectDb2.php';
require 'conectDb.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/agenda.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
        <!-- Añadir en <head> de agenda.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <title>Agenda Toneleros</title> 
    <style>
        /* Estilo para eliminar el color azul de los enlaces */
        .lista {
            color: inherit !important; /* Hereda el color del texto del elemento padre */
            text-decoration: none !important; /* Elimina el subrayado */
        }
    </style>
</head>
<body>
    <?php 
        require 'header.php';
        require 'botonHerramientas.php';
        require 'filter.php';
    ?>
    
   
    

<?php 


switch ($month) {
    case 1:
        $nombre_mes = "Enero";
        break;
    case 2:
        $nombre_mes = "Febrero";
        break;
    case 3:
        $nombre_mes = "Marzo";
        break;
    case 4:
        $nombre_mes = "Abril";
        break;
    case 5:
        $nombre_mes = "Mayo";
        break;
    case 6:
        $nombre_mes = "Junio";
        break;
    case 7:
        $nombre_mes = "Julio";
        break;
    case 8:
        $nombre_mes = "Agosto";
        break;
    case 9:
        $nombre_mes = "Septiembre";
        break;
    case 10:
        $nombre_mes = "Octubre";
        break;
    case 11:
        $nombre_mes = "Noviembre";
        break;
    case 12:
        $nombre_mes = "Diciembre";
        break;
    default:
        $nombre_mes = " ";
        break;
}

?>

    
<h1 class="text-center my-4">AGENDA <?php echo $nombre_mes?></h1>

<div class="container mb-5">
<?php 
$suma = 0;
$suma_mes = 0;  // <--- acumulador mensual
$anyo_mes_actual = null;
$primera_tabla = true;

while ($fila = mysqli_fetch_assoc($datos)) {
    $fecha = strtotime($fila["fecha"]);
    $anyo = date('Y', $fecha);
    $mes = date('n', $fecha);
    $clave = "$anyo-$mes";

    $nombre_mes = [
        1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
        5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
        9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
    ][$mes];

    if ($anyo_mes_actual !== $clave) {
        if (!$primera_tabla) {
            // Mostrar total del mes antes de cerrar la tabla
            echo '<tr><td colspan="4" class="text-end fw-bold">Total mes:</td>
                      <td colspan="2" class="fw-bold text-success">' . number_format($suma_mes, 2, ',', '.') . ' € (' . number_format($suma_mes / 5, 2, ',', '.') . ' € / integrante)</td></tr>';
            echo '</tbody></table></div></div>';
        } else {
            $primera_tabla = false;
        }

        $anyo_mes_actual = $clave;
        $suma_mes = 0;  // <--- reiniciamos al cambiar de mes

        // Título del año y mes
        echo '<h2 class="text-center my-4">' . $nombre_mes . ' ' . $anyo . '</h2>';

        // Nueva tabla
        echo '
        <div class="container mb-5">
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Tipo Evento</th>
                            <th>Fecha</th>
                            <th>Dirección</th>
                            <th>Equipo</th>
                            <th>Presupuesto bruto</th>
                            <th>A cobrar</th>
                        </tr>
                    </thead>
                    <tbody>
        ';
    }

    if ($fila["cerrada"] == 1) {
        $suma += $fila["presupuesto"];       // total global
        $suma_mes += $fila["presupuesto"];   // total mensual
    }

    $rowClass = ($fila["cerrada"] == 0) ? "table-warning" : "";
    $equipo = ($fila["equipo"] == 1) ? "Nuestro" : "Cliente";

    echo '<tr class="' . $rowClass . '">
            <td><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none text-primary">' . $fila["tipo"] . '</a></td>
            <td><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none text-primary">' . $fila["fechaFormateada"] . '</a></td>
            <td><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none text-primary">' . $fila["direccion"] . '</a></td>
            <td><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none text-primary">' . $equipo . '</a></td>
            <td><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none text-primary">' . number_format($fila["presupuesto"], 2, ',', '.') . ' €</a></td>
            <td class="fw-bold text-success"><a href="detalle.php?idAct=' . $fila['id'] . '" class="text-decoration-none">' . number_format($fila["presupuesto"] - $fila["senal"], 2, ',', '.') . ' €</a></td>
          </tr>';
}

// Cerramos la última tabla con total mensual
if (!$primera_tabla) {
    echo '<tr><td colspan="4" class="text-end fw-bold">Total mes:</td>
              <td colspan="2" class="fw-bold text-success">' . number_format($suma_mes, 2, ',', '.') . ' € (' . number_format($suma_mes / 5, 2, ',', '.') . ' € / integrante)</td></tr>';
    echo '</tbody></table></div></div>';
}

?>

<!-- Total global -->
<div class="container mb-5">
    <div class="p-4 border rounded bg-light text-center">
        <h5 class="mb-2">Ganancias totales:</h5>
        <h3 class="text-success fw-bold"><?php echo number_format($suma, 2, ',', '.'); ?> €</h3>
        <h4 class="text-success">Por integrante: <?php echo number_format($suma / 5, 2, ',', '.'); ?> €</h4>
    </div>
</div>




            </tbody>
        </table>
    </div>
</div>



</body>
</html>
