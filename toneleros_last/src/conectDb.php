<?php 
    $server = 'db';
    $username = 'toneleros';
    $password = 'ssiidb123';
    $database = 'toneleros';


    try{
        $conexion = new PDO("mysql:host=$server; dbname=$database;",$username,$password);

    }
    catch(PDOException $e) {
        print('conexion fallida');
    }
?>