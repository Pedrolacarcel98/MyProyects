<?php
require 'verifySession.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Toneleros</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex align-items-center justify-content-center min-vh-100">

<div class="container">
    <div class="card bg-secondary bg-opacity-25 shadow-lg rounded-4 p-4 mx-auto" style="max-width: 600px;">
        
        <a onclick="history.back()" class="btn btn-outline-light w-100 mb-4">
            <i class="fas fa-arrow-left"></i> Volver
        </a>

        <h2 class="text-center mb-4 fw-bold">Documentos Toneleros</h2>

        <div class="list-group">
                   
            <a href="https://toneleros.in/archivos/RIDER.pdf" download="RIDER.pdf" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-file-pdf me-2 text-danger"></i> Rider Tecnico
            </a>
            
            <a href="https://toneleros.in/archivos/CONTRATO.pdf" download="CONTRATO.pdf" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-file-pdf me-2 text-danger"></i> Contrato en PDF
            </a>

            <a href="https://toneleros.in/archivos/CONTRATO.docx" download="CONTRATO.docx" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-file-word me-2 text-primary"></i> Contrato en WORD
            </a>

            <a href="https://toneleros.in/archivos/REPERTORIO.pdf" download="REPERTORIO.pdf" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-file-pdf me-2 text-danger"></i> Repertorio
            </a>

            <a href="videos.php" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-folder-open me-2 text-warning"></i> Videos
                <i class="fas fa-arrow-right ms-auto"></i>
            </a>
            <a href="fotos.php" class="list-group-item list-group-item-action bg-dark text-light d-flex align-items-center">
                <i class="fa fa-folder-open me-2 text-warning"></i> Fotos
                <i class="fas fa-arrow-right ms-auto"></i>
            </a>
        </div>

    </div>
</div>

</body>
</html>
