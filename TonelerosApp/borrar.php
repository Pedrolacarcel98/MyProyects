<?php
session_start();

require 'conectDb.php';
require 'connectDb2.php';

if (isset($_GET['idAct']) && is_numeric($_GET['idAct'])) {
    $idAct = filter_input(INPUT_GET, 'idAct', FILTER_VALIDATE_INT);
    
    if ($idAct === false) {
        die("ID inválido.");
    }

    try {
        $sql = "DELETE FROM agenda WHERE id = :idAct";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idAct', $idAct, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            header("Location: cartas.php");
            exit(); 
        } else {
            echo "Error al eliminar el registro.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se ha especificado un idAct válido.";
}
