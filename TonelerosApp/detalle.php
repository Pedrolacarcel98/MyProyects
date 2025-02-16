<?php

require 'verifySession.php';

?>

<?php
session_start();
require 'conectDb.php';
require 'connectDb2.php';

$idAct = $_GET['idAct'];
$actuacion = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateadaFecha, DATE_FORMAT(fecha, '%H:%i') as fechaFormateadaHora FROM agenda WHERE id = $idAct";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Agenda Toneleros</title>
    <script>
        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar esta actuación?");
        }
    </script>
    <style>
        .container .card {
            border: 2px solid black;
            border-radius: 5px;
            padding: 20px;
            background-color: #f8f9fa;
            margin: 30px;
        }
    </style>
</head>
<body>
    <?php require 'header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="card col-md-8">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php 
                        $suma = 0;
                        $datos = mysqli_query($conn, $actuacion);
                        if ($fila = mysqli_fetch_assoc($datos)) { 
                            $suma += $fila["presupuesto"];
                            $equipo = ($fila["equipo"] == 0) ? "Nuestro" : "Cliente";
                        ?>
                        <li class="mb-3">
                            <strong>Tipo:</strong> <?php echo $fila["tipo"]; ?><br>
                            <strong>Fecha:</strong> <?php echo $fila["fechaFormateadaFecha"]; ?><br>
                            <strong>Hora:</strong> <?php echo $fila["fechaFormateadaHora"]; ?><br>
                            <strong>Dirección:</strong> <?php echo $fila["direccion"]; ?><br>
                            <strong>Persona de Contacto:</strong> <?php echo $fila["pContacto"]; ?><br>
                            <strong>Tlf:</strong> <?php echo $fila["tlf"]; ?><br>
                            <strong>Presupuesto:</strong> <?php echo $fila["presupuesto"]; ?>€<br>
                            <strong>Señal:</strong> <?php echo $fila["senal"]; ?>€<br>
                            <strong>Cobrar:</strong> <?php echo $fila["presupuesto"] - $fila["senal"]; ?>€<br>
                            <strong>Equipo:</strong> <?php echo $equipo; ?><br>
                            <strong>Observaciones:</strong> <?php echo $fila["observaciones"]; ?><br>
                        </li>
                        <?php } else { ?>
                            <p>No se encontró la actuación.</p>
                        <?php } ?>
                    </ul>

                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-2">
                            <button onclick="history.back()" class="btn btn-secondary w-100">Volver</button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="editar.php?idAct=<?php echo $idAct; ?>" class="btn btn-primary w-100">Editar</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="borrar.php?idAct=<?php echo $idAct; ?>" onclick="return confirmDelete()" class="btn btn-danger w-100">Borrar</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="archivar.php?idAct=<?php echo $idAct; ?>" class="btn btn-warning w-100">Archivar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
