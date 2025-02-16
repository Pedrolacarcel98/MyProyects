

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
    
   
    



    
    <h1 class="text-center">AGENDA</h1>
    
    <div class="table-responsive">
        <table class="table table-sm tabla">
            <thead>
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
                <?php 
                $suma = 0;
                while ($fila = mysqli_fetch_assoc($datos)) {
                    if($fila["cerrada"] == 1){
                        $suma += $fila["presupuesto"];}
                        
                    $cerrada = ($fila["cerrada"] == 0) ? "bg-warning" : "";
                    $equipo = ($fila["equipo"] == 0) ? "Nuestro" : "Cliente";
                ?>
                <tr class="<?php echo $cerrada; ?>">
                    <td data-label="Tipo Evento">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista">
                            <?php echo $fila["tipo"]; ?>
                        </a>
                    </td>
                    <td data-label="Fecha">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista">
                            <?php echo $fila["fechaFormateada"]; ?>
                        </a>
                    </td>
                    <td data-label="Dirección">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista">
                            <?php echo $fila["direccion"]; ?>
                        </a>
                    </td>
                    <td data-label="Equipo">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista">
                            <?php echo $equipo; ?>
                        </a>
                    </td>
                    <td data-label="Presupuesto bruto">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista">
                            <?php echo number_format($fila["presupuesto"]); ?> €
                        </a>
                    </td>
                    <td data-label="A cobrar">
                        <a href="detalle.php?idAct=<?php echo $fila['id']; ?>" class="lista text-success fw-bold">
                            <?php echo number_format($fila["presupuesto"] - $fila["senal"]); ?> €
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="total">
        <h5>Ganancias totales:</h5>
        <h3 class="text-success"> <?php echo $suma; ?> €</h3>
    </div>
</body>
</html>
