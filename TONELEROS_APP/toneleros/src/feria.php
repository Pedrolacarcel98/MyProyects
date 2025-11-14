<?php

    session_start();
    
    require 'connectDb2.php';

    $consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m') as fechaFormateada FROM agenda WHERE tipo = 'FeriaS' and archivado != 1 ORDER BY fecha";

    



    if(!isset($_POST['nombre'])){
        $_POST['nombre'] = '';
    }
    if(!isset($_POST['tipo'])){
        $_POST['tipo'] = '';
    }
    if(!isset($_POST['numRuta'])){
        $_POST['numRuta'] = '';
    }

    

?>



<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/agenda-responsive.css">
    <link rel="stylesheet" href="css/agenda.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    



    
    <title>Agenda Toneleros</title> 
</head>
<body>

   <?php require 'header.php'; ?>
        
   
</form>

</div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Tipo Evento</th>
                <th>Fecha</th>
                <th>Direccion</th>
                <th>Persona de Contacto</th>
                <th>Presupuesto bruto</th>
                <th>Señal</th>
                <th>A cobrar</th>
                <th>Observaciones</th>
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
                        <td><?php echo $fila["pContacto"]; ?></td>
                        <td><?php echo $fila["presupuesto"]; ?>€</td>
                        <td><?php echo $fila["senal"]; ?>€</td>
                        <td class="cobrar"><?php echo $fila["presupuesto"] - $fila["senal"]; ?>€</td>
                        <td><?php echo $fila["observaciones"] ; ?></td>

                        <td><a href="editar.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-primary">Editar</a></td>
                        <td><a href="borrar.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-danger">Borrar</a></td>
                        <td> <a href="archivar.php?idAct=<?php echo $fila["id"]; ?>" class="btn btn-warning">Archivar</a></td>


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