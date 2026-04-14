<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'utcam');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Puede que tu variable se llame $conn, $con, $mysqli, etc.
$conn = mysqli_connect("localhost", "root", "", "utcam");


// Función para obtener la conexión PDO
function getConnection() {
    static $conn = null;

    if ($conn === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    return $conn;
}
