<?php

require 'verifySession.php';

?>
<?php

    session_start();
    
    require 'connectDb2.php';

$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateada FROM agenda WHERE archivado = 1 ORDER BY fecha";

    




    

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/agenda.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    
    <title>Historial Toneleros</title> 
</head>
<body>

   <?php require 'header.php'; ?>
        
   
</form>
<div class = "text-center">
 <a href="agenda.php" class="fas fa-arrow-left"> Volver</a>
<a href="ingresos.php" class="btn btn-success fas fa-euro"> Ingresos</a>   
</div>

<h2> HISTORIAL </h2>

</div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Tipo Evento</th>
                <th>Fecha</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Presupuesto bruto</th>
                <th>A cobrar</th>
                <th></th>
                <th></th>
                <th></th>


            </tr>
        </thead>
        <tbody>
            <?php 
            $suma = 0;
            $datos = mysqli_query($conn,$consulta);
            while($fila = mysqli_fetch_assoc($datos)){ 
                $suma += $fila["presupuesto"]?>
                    <tr>
                        <td><?php echo $fila["tipo"]; ?></td>
                        <td><?php echo $fila["fechaFormateada"]; ?></td>
                        <td><?php echo $fila["direccion"]; ?></td>
                        <td><?php echo $fila["tlf"]; ?></td>
                        <td><?php echo $fila["presupuesto"]; ?>€</td>
                        <td class="cobrar"><?php echo $fila["presupuesto"] - $fila["senal"]; ?>€</td>

                        <td><a href="editar.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-primary">Editar</a></td>
                        <td><a href="detalle.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-success">Detalles</a></td>
                        <td><a href="borrar.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-danger">Borrar</a></td>
                        


                    </tr>
            <?php   };   ?>


        </tbody>
    </table>


    <div class="total">
        <h5>Ganacias totales: </h5>
        <h3 class="text-success "> <?php echo $suma ?> </h3>
    </div>




</body>
</html>