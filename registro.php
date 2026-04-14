<?php
// 1. Iniciar la sesión
session_start();

// 2. Incluir tu archivo de conexión real
require_once 'conexion1.php';

$mensaje = "";

// 3. Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_input = trim($_POST['username']);
    $password_input = $_POST['password'];

    // Limpiar los datos para mayor seguridad
    $usuario_input = mysqli_real_escape_string($conn, $usuario_input);
    $password_input = mysqli_real_escape_string($conn, $password_input);

    // Primero, verificamos si el usuario ya existe para no tener duplicados
$sql_check = "SELECT * FROM usuarios WHERE usuario = '$usuario_input'";
    $resultado_check = mysqli_query($conn, $sql_check);

    if ($resultado_check && mysqli_num_rows($resultado_check) > 0) {
        $mensaje = "<div class='error'>Ese nombre de usuario ya existe. Por favor, elige otro.</div>";
    } else {
        // Cambia 'usuario' por el nombre real de tu columna
$sql_insert = "INSERT INTO usuarios (usuario, password) VALUES ('$usuario_input', '$password_input')";

        
        if (mysqli_query($conn, $sql_insert)) {
            $mensaje = "<div class='success'>¡Cuenta creada con éxito! Ya puedes <a href='login.php'>iniciar sesión</a>.</div>";
        } else {
            $mensaje = "<div class='error'>Hubo un error de base de datos: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta - UTCAM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; text-align: center; border-top: 5px solid #006847; }
        .login-container h2 { color: #1a1a1a; margin-bottom: 1.5rem; }
        .login-container input { width: 90%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .login-container button { width: 90%; padding: 12px; background-color: #006847; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1rem; margin-top: 10px; }
        .login-container button:hover { background-color: #004d35; }
        .error { color: #CE1126; font-size: 0.9em; margin-bottom: 15px; font-weight: bold; }
        .success { color: #006847; font-size: 0.9em; margin-bottom: 15px; font-weight: bold; background: #e6f3ed; padding: 10px; border-radius: 4px; }
        .success a { color: #006847; text-decoration: underline; }
        .link-login { display: block; margin-top: 15px; font-size: 0.85em; color: #555; text-decoration: none; }
        .link-login:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Crear Cuenta</h2>
        
        <?php echo $mensaje; ?>

        <form method="POST" action="registro.php">
            <input type="text" name="username" placeholder="Elige un usuario" required autocomplete="off">
            <input type="password" name="password" placeholder="Crea una contraseña" required>
            <button type="submit">Registrarse</button>
        </form>

        <a href="login.php" class="link-login">¿Ya tienes cuenta? Inicia sesión aquí</a>
    </div>
</body>
</html>