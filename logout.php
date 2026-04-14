<?php
// Iniciar la sesión para poder acceder a ella y destruirla
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión por completo
session_destroy();

// Redirigir de vuelta a la página de login
header("Location: login.php");
exit();
?>