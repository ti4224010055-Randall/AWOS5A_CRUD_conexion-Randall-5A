<?php
session_start();

// Proteger la página
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php");
    exit();
}

// CORREGIDO: Ahora apunta a conexion1.php
include_once 'conexion1.php';

if (isset($_GET['matricula'])) {
    try {
        $pdo = getConnection();
        $matricula = $_GET['matricula'];

        // Actualizamos el estatus a 0 en lugar de borrarlo
        $sql = "UPDATE alumnos SET status = '0' WHERE matricula = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$matricula])) {
            header("Location: consultas.php?mensaje=deactivated");
            exit();
        }
    } catch (PDOException $e) {
        die("Error al dar de baja: " . $e->getMessage());
    }
} else {
    header("Location: consultas.php");
}
?>