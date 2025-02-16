<?php

require 'verifySession.php';

?>

<?php
    // Forzar el uso de cookies para sesiones

    session_start();
    require 'connectDb2.php';
    

$consulta = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') as fechaFormateada FROM agenda WHERE tipo = 'FeriaS' and archivado != 1 ORDER BY fecha";


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    /* Eliminar subrayado de enlaces dentro de las tarjetas */
    .col-md-12 .card {
        text-decoration: none; /* Elimina el subrayado */
        color: inherit; /* Hereda el color del texto del elemento padre */
    }

    .col-md-12 .card:hover {
        text-decoration: none; /* Asegúrate de que no haya subrayado al pasar el ratón */
    }
    
    .col-md-12 .card {
            border: 3px solid black; /* Borde negro de 2 píxeles */
            /* Bordes redondeados */
            transition: transform 0.3s; /* Efecto de transición */
        }

        .card:hover {
            transform: scale(1.05); /* Efecto de aumento al pasar el ratón */
        }
    </style>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    
    
    <title>Feria Sevilla  Toneleros</title> 
    
</head>



   <?php require 'header.php';
       require 'botonHerramientas.php';
?>
 <body>
    <div class="row align-items-center">
    <div class="col-md-8 text-center">
        <h1>FERIA SEVILLA 2024</h1>
    </div>
    <div class="col-md-2 text-center">
        <a class="btn btn-secondary" href="cuadranteFeria.php">Cuadrante</a>
    </div>
</div>

    
    <div class="container">
    <div class= "row">
         
     
         <?php 
                    $datos = mysqli_query($conn,$consulta);
                    while($fila = mysqli_fetch_assoc($datos)){
         ?>
         
         <?php
                        
                        $cerrada = "";
                        if($fila["cerrada"] == 0){
                            $cerrada = "bg-warning";
                        }
                        else{
                            $cerrada = "";
                        }
        ?>
         <div class = "col-md-12">
             <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="card mb-4">
             
                 <div class = "card-body <?php echo $cerrada ?>">
                    <h6><?php echo $fila["fechaFormateada"];?></h6>
                    <h6><?php echo $fila["direccion"];?></h6>
                    <h4 class = "text-success"><?php echo $fila["presupuesto"];?>€</h4>
                     
                 </div>
        
                 
             
             </a>
         </div>
                <?php   };   ?>
    </div>
 </div>
 </body>
 </html>