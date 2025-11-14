<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Menú Desplegable</title>
</head>
<body>

<div class="container mt-5 text-center">
    <!-- Botón de herramientas -->
    <div class="dropdown mb-3">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            HERRAMIENTAS
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a href="nuevaAct.php" class="dropdown-item"><i class="fa fa-plus"></i> Nueva actuación</a>
            <a href="cartas.php" class="dropdown-item"><i class="fa fa-book"></i> Agenda (Tarjetas)</a>
            <a href="agenda.php" class="dropdown-item"><i class="fa fa-book"></i> Agenda (Tabla)</a>
            <a href="ingresos.php" class="dropdown-item"><i class="fa fa-euro"></i> Ingresos</a>
            <a href="archivos.php" class="dropdown-item"><i class="fa fa-folder"></i> Archivos</a>
            <a href="calendario.php" class="dropdown-item"><i class="fa fa-calendar"></i> Calendario</a>
            <a href="feriaS.php" class="dropdown-item"><i class="fa fa-cocktail"></i> Feria De Sevilla</a>
            <a href="historial.php" class="dropdown-item"><i class="fa fa-book"></i> Historial</a>
        </div>
    </div>

    

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
