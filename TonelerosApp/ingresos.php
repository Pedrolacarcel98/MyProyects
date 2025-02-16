<?php

require 'verifySession.php';

?>

<?php
session_start();
require 'connectDb2.php';

// Consultas para obtener datos
$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateada FROM agenda ";
$consultaProximo = $consulta . "WHERE archivado != 1";
$consultaHistorial = $consulta . "WHERE archivado = 1";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/ingresos.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Agenda Toneleros</title>
</head>
<body>
    <?php 
    require 'header.php'; 
    require 'botonHerramientas.php'; 
    ?>

    <div class="container">
        
        
        <h1 class="text-center">NUESTRAS GANACIAS</h1>

        <!-- PROXIMAS GANANCIAS -->
        <?php 
        $sumaProximo = 0; 
        $datosProximo = mysqli_query($conn, $consultaProximo);
        
        while ($fila = mysqli_fetch_assoc($datosProximo)) { 
            $sumaProximo += $fila["presupuesto"];
        }
        ?>

        <!-- GANANCIAS HASTA EL DIA DE HOY -->
        <?php 
        $sumaHistorial = 0; 
        $datosHistorial = mysqli_query($conn, $consultaHistorial);
        
        while ($fila = mysqli_fetch_assoc($datosHistorial)) { 
            $sumaHistorial += $fila["presupuesto"];
        }
        ?>

        <div class="total">
            <h5>PROXIMAS GANANCIAS:</h5>
            <h3 class="text-success"><?php echo $sumaProximo; ?> €</h3>
            <h5 class="text-success"><?php echo $sumaProximo / 5; ?> € por chorla</h5> 
            <h5>GANANCIAS DESDE FERIA 2024 (HASTA HOY):</h5>
            <h3 class="text-success"><?php echo $sumaHistorial; ?> €</h3>
            <h5 class="text-success"><?php echo $sumaHistorial / 5; ?> € por chorla</h5> 
        </div>
    </div>
</body>
</html>