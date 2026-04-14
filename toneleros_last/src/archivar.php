<?php
session_start();

require 'conectDb.php';
require 'connectDb2.php';

if (isset($_GET['idAct']) && is_numeric($_GET['idAct'])) {
    $idAct = $_GET['idAct'];

    try {
        $sql = "SELECT * FROM agenda WHERE id = :idAct";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idAct', $idAct, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE agenda SET archivado = 1 WHERE id = :idAct";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idAct', $idAct, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header('Location: agenda.php');
                exit;
            } else {
                echo "No se pudo archivar la actuación.";
            }
        } else {
            echo "No se encontró la actuación.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Parámetro idAct no válido.";
}
