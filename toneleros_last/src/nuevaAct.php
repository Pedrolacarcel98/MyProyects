<?php

require 'verifySession.php';

?>

<?php



session_start();

require 'conectDb.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

   $sql = "INSERT INTO agenda (tipo, fecha, direccion, pContacto, tlf, presupuesto, senal, equipo, observaciones, archivado, cerrada)
VALUES (:tipo, :fecha, :direccion, :pContacto, :tlf, :presupuesto, :senal, :equipo, :observaciones, 0, :cerrada)";


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

<div class="container py-4">
    <h1 class="text-center mb-4">NUEVA ACTUACIÓN</h1>

    <form action="nuevaAct.php" method="post" onsubmit="return confirmar()" class="needs-validation" novalidate>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">¿Está cerrada?</label>
                <select name="cerrada" class="form-select" required>
                    <option value="">--Selecciona estado--</option>
                    <option value="1">Cerrada</option>
                    <option value="0">En negociación</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo de evento:</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Selecciona tipo</option>
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
                <label class="form-label">Fecha del evento:</label>
                <input type="datetime-local" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Dirección:</label>
                <input type="text" name="direccion" class="form-control" placeholder="Dirección">
            </div>

            <div class="col-md-6">
                <label class="form-label">Persona de contacto:</label>
                <input type="text" name="pContacto" class="form-control" placeholder="Cliente" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono:</label>
                <input type="number" name="tlf" class="form-control" placeholder="Teléfono" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Presupuesto:</label>
                <input type="number" name="presupuesto" class="form-control" placeholder="€" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Señal:</label>
                <input type="number" name="senal" class="form-control" placeholder="€" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Equipo:</label>
                <select name="equipo" class="form-select" required>
                    <option value="">--Cliente/Nuestro--</option>
                    <option value="1">Nuestro</option>
                    <option value="0">Cliente</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Observaciones:</label>
                <input type="text" name="observaciones" class="form-control" placeholder="Observaciones" required>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-plus"></i> Añadir Actuación
            </button>
        </div>
    </form>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-info mt-4 text-center" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>
</div>


</body>
</html>
