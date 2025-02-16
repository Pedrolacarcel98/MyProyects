<?php 
    $server = '127.0.0.1:3306';
    $username = 'u186974271_TONELEROS';
    $password = 'Tonelete1234?';
    $database = 'u186974271_TONELEROS';


    try{
        $conexion = new PDO("mysql:host=$server; dbname=$database;",$username,$password);

    }
    catch(PDOException $e) {
        print('conexion fallida');
    }
?>