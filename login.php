<?php
// 1. Iniciar la sesión
session_start();

// 2. Incluir tu archivo de conexión real
require_once 'conexion1.php';

// 3. Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_input = trim($_POST['username']);
    $password_input = $_POST['password'];

    try {
        // Usar la función de PDO que ya tienes en conexion1.php
        $pdo = getConnection();
        
        // Consultar la base de datos para buscar al usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :username");
        $stmt->bindParam(':username', $usuario_input);
        $stmt->execute();
        $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y la contraseña coincide
        if ($usuario_db && $password_input === $usuario_db['password']) {
            
            // ¡Autenticación exitosa! 
            $_SESSION['autenticado'] = true;
            
            // Redirigir a TU página principal de consultas
            header("Location: consultas.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - UTCAM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; text-align: center; border-top: 5px solid #006847; }
        .login-container h2 { color: #1a1a1a; margin-bottom: 1.5rem; }
        .login-container input { width: 90%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .login-container button { width: 90%; padding: 12px; background-color: #006847; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1rem; margin-top: 10px; }
        .login-container button:hover { background-color: #004d35; }
        .error { color: #CE1126; font-size: 0.9em; margin-bottom: 15px; font-weight: bold; }
        .link-registro { display: block; margin-top: 15px; font-size: 0.85em; color: #555; text-decoration: none; }
        .link-registro:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Acceso al Sistema</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Usuario" required autocomplete="off">
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <a href="registro.php" class="link-registro">¿No tienes cuenta? Regístrate aquí</a>
    </div>
</body>
</html>