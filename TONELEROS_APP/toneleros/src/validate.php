<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    require 'connectDb2.php';

    if ($conn === false) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $consulta = "SELECT email, password FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $respuesta = mysqli_stmt_get_result($stmt);

    $filas = mysqli_num_rows($respuesta);

    if ($filas > 0) {
        $results = mysqli_fetch_assoc($respuesta);

        if (verifiy_password($password, $results['password'])) {
            // Si la contraseña es correcta, se inicia la sesión
            $_SESSION['username'] = $username;
            header("Location: agenda.php"); // Redirigir al usuario a la página de la agenda
            exit; // Terminar el script después de la redirección
        } else {
            // Si la contraseña no es correcta
            echo "<div class='error-message'>AUTENTICACION ERRONEA, REVISE LAS CREDENCIALES</div>";
            include("iniciarSesion.php");
        }
    } else {
        // Si no se encuentra el usuario
        echo "<div class='error-message'>AUTENTICACION ERRONEA, REVISE LAS CREDENCIALES</div>";
        include("iniciarSesion.php");
    }

    // Liberar el resultado y cerrar la conexión
    mysqli_free_result($respuesta);
    mysqli_close($conn);
} else {
    // Si no se han enviado los datos del formulario
    echo "<div class='error-message'>Por favor ingrese tanto el nombre de usuario como la contraseña.</div>";
    include("iniciarSesion.php");
}
?>
<style>
    .error-message {
        background-color: #f44336; /* Rojo de error */
        color: white;
        font-size: 18px;
        padding: 15px;
        text-align: center;
        border-radius: 5px;
        margin: 20px auto;
        width: 80%;
        max-width: 400px;
    }
</style>

