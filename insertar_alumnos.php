<?php
// Incluimos tu archivo de conexión
require_once 'conexion1.php';

$mensaje = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtenemos la instancia de la conexión PDO
        $pdo = getConnection();

        // Recibimos los datos del formulario
        $matricula = $_POST['matricula'];
        $nombre    = $_POST['nombre'];
        $apaterno  = $_POST['apaterno'];
        $amaterno  = $_POST['amaterno'];
        $fecha_nac = $_POST['fecha_nacimiento'];
        $status    = $_POST['status'];

        // Preparamos la consulta con marcadores de posición (?)
        $sql = "INSERT INTO alumnos (matricula, nombre, apaterno, amaterno, fecha_nacimiento, status) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        // Ejecutamos pasando los valores en un arreglo
        if ($stmt->execute([$matricula, $nombre, $apaterno, $amaterno, $fecha_nac, $status])) {
            // Redirigir a la tabla de consultas después de insertar
            header("Location: consultas.php?mensaje=success");
            exit(); 
        }
    } catch (PDOException $e) {
        $mensaje = "<div style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>
                        ❌ Error al registrar: " . $e->getMessage() . "
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumnos - UTCAM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { color: #333; text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { margin-bottom: 5px; font-weight: 600; color: #555; }
        input, select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        input:focus { border-color: #007bff; outline: none; }
        button { background-color: #28a745; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 10px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h2>Nuevo Registro de Alumno</h2>
    
    <?php echo $mensaje; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Matrícula</label>
            <input type="text" name="matricula" placeholder="Ej. 12230001" maxlength="12" required>
        </div>

        <div class="form-group">
            <label>Nombre(s)</label>
            <input type="text" name="nombre" maxlength="60" required>
        </div>

        <div class="form-group">
            <label>Apellido Paterno</label>
            <input type="text" name="apaterno" maxlength="60" required>
        </div>

        <div class="form-group">
            <label>Apellido Materno</label>
            <input type="text" name="amaterno" maxlength="60" required>
        </div>

        <div class="form-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" required>
        </div>

        <div class="form-group">
            <label>Estatus</label>
            <select name="status" required>
                <option value="1">Activo / Regular</option>
                <option value="0">Baja / Inactivo</option>
            </select>
        </div>

        <button type="submit">Guardar en Base de Datos</button>
    </form>
</div>

</body>
</html>