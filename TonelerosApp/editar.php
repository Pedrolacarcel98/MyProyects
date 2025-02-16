<?php

require 'verifySession.php';

?>

<?php

session_start();

require 'conectDb.php';

require 'connectDb2.php';


 $idAct = $_GET['idAct'];

 $actuacion = "SELECT * FROM agenda WHERE id = $idAct";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sql =  "UPDATE agenda
    SET 
    tipo = :tipo, 
    fecha = :fecha, 
    direccion = :direccion, 
    pContacto = :pContacto, 
    tlf = :tlf, 
    presupuesto = :presupuesto,
    senal = :senal, 
    equipo = :equipo,
    observaciones = :observaciones,
    cerrada = :cerrada
    WHERE
    id = :idAct";

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
    $var->bindParam(':idAct', $idAct);

    if($var->execute()){
        $message = 'Actuación actualizada';
    }
    else{
        $message = 'Lo siento, ha habido algún error al intentar ingresar la actuación';
    }
    header('Location: agenda.php');
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
    <title>Editar Actuacion</title>
</head>
<body>

<?php require 'header.php'; ?>

<?php

$datos = mysqli_query($conn,$actuacion);
while($fila = mysqli_fetch_assoc($datos)){ 

?>

<div class="container">
    <h1>NUEVA ACTUACION</h1>
    <form class="form" action="editar.php?idAct=<?php echo $idAct?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <select name="cerrada">
                    <option value="<?php  echo  $fila['cerrada']?>"><?php  echo  $fila['cerrada']?></option>
                    <option value= 1 class = "text-success fw-bold ">Cerrada</option>
                    <option value= 0 class = "text-warning fw-bold">En negociación</option>
                    
                </select>
                <label>Tipo de evento:</label>
                <select name="tipo">
                    <option value="<?php  echo  $fila['tipo']?>"><?php  echo  $fila['tipo']?></option>
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
                <input type="datetime-local" name="fecha" placeholder="fecha" value= "<?php  echo  $fila['fecha']?>"required>
            </div>

            <div class="col-md-6">
                <label>Dirección:</label>
                <input type="text" name="direccion" placeholder="dirección" value= "<?php  echo $fila['direccion']?>">
            </div>

            <div class="col-md-6">
                <label>Cliente/Persona de contacto:</label>
                <input type="text" name="pContacto" placeholder="Cliente"value= "<?php  echo $fila['pContacto']?>" required>
            </div>

            <div class="col-md-6">
                <label>Teléfono:</label>
                <input type="number" name="tlf" placeholder="tlf" value= "<?php echo $fila['tlf']?>"required>
            </div>

            <div class="col-md-6">
                <label>Presupuesto:</label>
                <input type="number" name="presupuesto" placeholder="presupuesto" value= "<?php  echo $fila['presupuesto']?>"required>
            </div>

            <div class="col-md-6">
                <label>Señal:</label>
                <input type="number" name="senal" placeholder="señal" value= "<?php  echo  $fila['senal']?>"required>
            </div>
            
            <div class="col-md-6">
                <label>Equipo:</label>
                <select name="equipo">
                    <option value="<?php  echo  $fila['equipo']?>"><?php  echo  $fila['equipo']?></option>
                    <option value=1>Sí</option>
                    <option value=0>No</option>
                    
                </select>
            </div>

            <div class="col-md-6">
                <label>Observaciones:</label>
                <input type="text" name="observaciones" placeholder="observaciones" value= "<?php  echo $fila['observaciones']?>"required>
            </div>
        </div>
        <div class="row">
            <input type="submit" class="button" value="Actualizar">
        </div>

        
        
    </form>

    <?php   };  ?>

    <?php if(!empty($message)) : ?>
        <h3><?= $message ?></h3>
    <?php endif; ?>
</div>

</body>
</html>
