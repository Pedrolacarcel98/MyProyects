
<?php

require 'verifySession.php';

?>
<?php
// Iniciar sesión y requerir conexión a la base de datos
session_start();
require 'connectDb2.php';

// Consulta SQL para obtener los eventos
$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fechaFormateada 
             FROM agenda 
             WHERE tipo != 'FeriaS' AND archivado != 1 
             ORDER BY fecha";

$datos = ($conn) ? mysqli_query($conn, $consulta) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Toneleros</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        .col-md-4 .card {
            text-decoration: none;
            color: inherit;
            border: 3px solid black;
            transition: transform 0.3s;
        }

        .col-md-4 .card:hover {
            text-decoration: none;
            transform: scale(1.05);
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }
    </style>
</head>

<body>
    <?php 
        require 'header.php';
        require 'botonHerramientas.php';
        require 'filter.php'
    ?>

    
<?php 

$nombre_mes = "";

switch ($month) {
    case 1:
        $nombre_mes .= "Enero";
        break;
    case 2:
        $nombre_mes .= "Febrero";
        break;
    case 3:
        $nombre_mes .= "Marzo";
        break;
    case 4:
        $nombre_mes .= "Abril";
        break;
    case 5:
        $nombre_mes .= "Mayo";
        break;
    case 6:
        $nombre_mes .= "Junio";
        break;
    case 7:
        $nombre_mes .= "Julio";
        break;
    case 8:
        $nombre_mes .= "Agosto";
        break;
    case 9:
        $nombre_mes .= "Septiembre";
        break;
    case 10:
        $nombre_mes .= "Octubre";
        break;
    case 11:
        $nombre_mes .= "Noviembre";
        break;
    case 12:
        $nombre_mes .= "Diciembre";
        break;
    default:
        $nombre_mes = "AGENDA";
        break;
}


?>


    
    <h1 class="text-center text-info"><?php echo $nombre_mes?></h1>

    <div class="container">
        <div class="row">
            <?php 
            
            if ($datos) {
                while ($fila = mysqli_fetch_assoc($datos)) {
                    $cerrada = ($fila["cerrada"] == 0) ? "bg-warning" : "";
            ?>
            <div class="col-md-4">
                <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="card mb-4">
                    <div class="card-body <?php echo $cerrada; ?>">
                        <h4><?php echo $fila["fechaFormateada"]; ?></h4>
                        <h6><?php echo $fila["direccion"]; ?></h6>
                        <h4 class="text-success"><?php echo $fila["presupuesto"]; ?>€</h4>
                    </div>
                </a>
            </div>
            <?php 
                } //Fin del while
            } else {
                echo "<p class='text-center text-danger'>No se encontraron eventos.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
