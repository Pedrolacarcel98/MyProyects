<?php

require 'verifySession.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
        .lista {
        color: inherit !important; /* Hereda el color del texto del elemento padre */
        text-decoration: none !important; /* Elimina el subrayado */
        
    }
    </style>
</head>
<body>
    <div class="documentos">
            <button onclick="history.back()" class="btn btn-secondary w-100">Volver</button>
        <h2>Documentos Toneleros</h2>
        <div class="documento">
            <a class="lista fa fa-file-pdf-o" href="https://toneleros.in/archivos/CONTRATO.pdf" download="CONTRATO.pdf"> Contrato En PDF</a>
        </div>
        <div class="documento">
            <a class="lista fas fa-file" href="https://toneleros.in/archivos/CONTRATO.docx" download="CONTRATO.docx"> Contrato En WORD</a>
        </div>
        <div class="documento">
            <a class="lista fa fa-file-pdf-o" href="https://toneleros.in/archivos/REPERTORIO.pdf" download="REPERTORIO.pdf"> Repertorio</a>
        </div>
        <div class="documento">
            <a class = "lista fas fa-folder " href="videos.php"> 
            
            VIDEOS
            <i class= "fas fa-arrow-right"></i>
            </a> 
        </div>
    </div>
</body>
</html>