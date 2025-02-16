<?php

require 'verifySession.php';

?>

<?php



session_start();

require 'conectDb.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sql = "INSERT INTO agenda (tipo, fecha, direccion, pContacto, tlf, presupuesto, senal, equipo, observaciones, archivado, cerrada)
            VALUES (:tipo, :fecha, :direccion, :pContacto, :tlf, :presupuesto, :senal, :observaciones, :equipo , 0, :cerrada)";

    $var = $conexion->prepare($sql);
    $var->bindValue(':tipo', $_POST['tipo']);
    $var->bindValue(':fecha', $_POST['fecha']);
    $var->bindValue(':direccion', $_POST['direccion']);
    $var->bindValue(':pContacto', $_POST['pContacto']);
    $var->bindValue(':tlf', $_POST['tlf']);
    $var->bindValue(':presupuesto', $_POST['presupuesto']);
    $var->bindValue(':senal', $_POST['senal']);
    $var->bindValue(':equipo', $_POST['equipo']);
    $var->bindValue(':observaciones', $_POST['observaciones']);
    $var->bindValue(':cerrada', $_POST['cerrada']);

    



    if($var->execute()){
        $message = 'Actuación añadida, Salud y Rock and Roll!';
    }
    else{
        $message = 'Error al ejecutar la consulta: ' . $var->errorInfo()[2];
;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nuevaAct.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
     <script>
        function confirmar() {
            return confirm("¿Añadir actuación?");
        }
    </script>
    <title>Nueva Actuacion</title>
</head>
<body>

<?php require 'header.php'; ?>

<div class="container">
    <h1>NUEVA ACTUACION</h1>
    <form class="form" action="nuevaAct.php" method="post">
        <div class="row">
            <div class="col-md-6">
                 <label>¿Esta cerrada?:</label>
                <select name="cerrada">
                    <option value="">--Selecciona si esta cerrada o esta en negociación--</option>
                    <option value=1 class="text-success fw-bold">Cerrada</option>
                    <option value=0 class="text-warning fw-bold">En negociación</option>
                </select>
                <label>Tipo de evento:</label>
                <select name="tipo">
                    <option value="">Selecciona tipo de actuación</option>
                    <option value="Boda">Boda</option>
                    <option value="Fiesta">Fiesta</option>
                    <option value="Puesta de largo">Puesta de largo</option>
                    <option value="Cumpleaños">Cumpleaños</option>
                    <option value="Feria">Feria</option>
                    <option value="FeriaS">Feria de Sevilla</option>

                    <option value="Bar/Discoteca">Bar/Discoteca</option>
                </select>
            </div>

            <div class="col-md-6">
                <label>Fecha del evento:</label>
                <input type="datetime-local" name="fecha" placeholder="fecha" required>
            </div>

            <div class="col-md-6">
                <label>Dirección:</label>
                <input type="text" name="direccion" placeholder="dirección">
            </div>

            <div class="col-md-6">
                <label>Cliente/Persona de contacto:</label>
                <input type="text" name="pContacto" placeholder="Cliente" required>
            </div>

            <div class="col-md-6">
                <label>Teléfono:</label>
                <input type="number" name="tlf" placeholder="tlf" required>
            </div>

            <div class="col-md-6">
                <label>Presupuesto:</label>
                <input type="number" name="presupuesto" placeholder="presupuesto" required>
            </div>

            <div class="col-md-6">
                <label>Señal:</label>
                <input type="number" name="senal" placeholder="señal" required>
            </div>
            
            <div class="col-md-6">
                <label>Equipo:</label>
                <select name="equipo">
                    <option value="">--Selecciona si llevamos equipo o no--</option>
                    <option value=1>Si</option>
                    <option value=0>No</option>
                </select>
            </div>
            
         

            <div class="col-md-6">
                <label>Observaciones:</label>
                <input type="text" name="observaciones" placeholder="observaciones" required>
            </div>
        </div>
        <div class="row ">
            <input type="submit" class="button" onclick="return confirmar()" value="Añadir Actuación">
        </div>
    </form>
    <?php if(!empty($message)) : ?>
        <h3><?= $message ?></h3>
    <?php endif; ?>
</div>

</body>
</html>
