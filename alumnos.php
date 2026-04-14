<?php
// Iniciar sesión SIEMPRE antes de cualquier salida HTML
session_start();

// Verificar si el usuario NO está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    // Si no está autenticado, lo pateamos de vuelta al login
    header("Location: login.php");
    exit(); // Importante detener la ejecución del script aquí
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial - Página Protegida</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .container { text-align: center; margin-top: 50px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Historial</h1>
        <p>Solo puedes ver esta página porque te autenticaste correctamente.</p>
        <br>
        <a href="consultas.php" style="background-color: #006847; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Ir a Consultas</a>
        <a href="logout.php" style="background-color: #CE1126; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">Cerrar Sesión</a>
    </div>
</body>
</html>