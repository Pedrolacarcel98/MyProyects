<?php



require 'conectDb.php';

    if (!empty($_POST['email']) && !empty($_POST['password'])){
        if ($_POST['password'] === $_POST['ConfirmPassword']){

        $sql = "INSERT INTO usuarios(email,password) VALUES (:email,:password)";
        $var = $conexion -> prepare ($sql);
        $var-> bindParam(':email',$_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $var-> bindParam(':password',$password);


        if($var->execute()){
            $message = 'Usuario correctamente ingresado! 
                        Pulsa Inicio e inicia Sesión!';
        }
        else{
            $message = 'Lo siento, lo has hecho mal';
        }
        
    }
}

?>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Terry</title>
</head>
<body>


<header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-black">
            <div class="container">
                <a class="navbar-brand" href="index.php"><h2>TONELEROS</h2></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link btn" href="iniciarSesion.php">Iniciar Sesion</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </header>

<div class="container">
    <h1>Regístrate aquí</h1>
    <form action="registrarse.php" method="post" class="form-container">
        
        <div class="form-input-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Tu email" required>
        </div>
        <div class="form-input-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
        </div>
        <div class="form-input-group">
            <label for="ConfirmPassword">Repite contraseña:</label>
            <input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Repite contraseña" required>
        </div>
        <input type="submit" value="Enviar" class="form-submit">
    </form>

    <?php if(!empty($message)) : ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>

</body>
</html>