<?php
require 'verifySession.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TONELEROS</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-black">
            <div class="container">
                <a href="agenda.php">
                    <img src="images/logo.png" alt="Logo" style="height: 100px; width: auto;">
                </a>
                <div class="ml-auto">
                    <a href="logout.php" class="btn btn-danger">Salir</a>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
