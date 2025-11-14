<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #121212; /* Fondo oscuro */
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background-color: #1c1c1c; /* Fondo oscuro para el formulario */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            width: 300px;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #f1f1f1; /* Color blanco para el encabezado */
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #333; /* Fondo oscuro para los campos */
            border: none;
            border-radius: 5px;
            color: white;
            box-sizing: border-box; /* Para asegurarse de que el padding no afecte el tamaño */
        }
        input[type="submit"] {
            background-color: #444; /* Fondo oscuro para el botón */
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            box-sizing: border-box;
        }
        input[type="submit"]:hover {
            background-color: #666; /* Fondo más claro al pasar el mouse */
        }
        .message {
            margin-top: 20px;
            color: #f44336; /* Color rojo para mensajes de error */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-box">
        <h2>Iniciar sesión</h2>

        <form action="validate.php" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Entrar">
        </form>
    </div>
</div>

</body>
</html>
