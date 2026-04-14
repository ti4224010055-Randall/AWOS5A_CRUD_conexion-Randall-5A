<?php
require 'conexion1.php';

$conn = getConnection();
$alumno = null;

if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $query = "SELECT * FROM alumnos WHERE matricula = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$matricula]);
    $alumno = $stmt->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula_vieja = $_POST['matricula_vieja'];
    $matricula_nueva = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $apaterno = $_POST['apaterno'];
    $amaterno = $_POST['amaterno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $status = $_POST['status']; // Recuperamos el estatus del formulario

    // Agregamos status=? a la consulta
    $update = "UPDATE alumnos SET matricula=?, nombre=?, apaterno=?, amaterno=?, fecha_nacimiento=?, status=? WHERE matricula=?";
    $stmt = $conn->prepare($update);
    
    try {
        // Ejecutamos incluyendo el status
        $stmt->execute([$matricula_nueva, $nombre, $apaterno, $amaterno, $fecha_nacimiento, $status, $matricula_vieja]);
        header("Location: consultas.php?mensaje=edited");
        exit;
    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #e9ecef; display: flex; justify-content: center; padding-top: 50px; }
        .contenedor { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { text-align: center; color: #198754; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-guardar { background-color: #0d6efd; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-bottom: 10px; }
        .btn-cancelar { background-color: #dc3545; color: white; text-decoration: none; padding: 10px; border-radius: 4px; display: block; text-align: center; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Editar Alumno</h2>
    
    <?php if ($alumno): ?>
    <form action="editar_alumno.php" method="POST">
        <input type="hidden" name="matricula_vieja" value="<?php echo htmlspecialchars($alumno['matricula']); ?>">

        <div class="form-group">
            <label>Matrícula:</label>
            <input type="text" name="matricula" value="<?php echo htmlspecialchars($alumno['matricula']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($alumno['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label>Apellido Paterno:</label>
            <input type="text" name="apaterno" value="<?php echo htmlspecialchars($alumno['apaterno']); ?>" required>
        </div>

        <div class="form-group">
            <label>Apellido Materno:</label>
            <input type="text" name="amaterno" value="<?php echo htmlspecialchars($alumno['amaterno']); ?>">
        </div>

        <div class="form-group">
            <label>Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?>" required>
        </div>

        <div class="form-group">
            <label>Estatus</label>
            <select name="status" required>
                <option value="1" <?php echo ($alumno['status'] == '1') ? 'selected' : ''; ?>>Activo / Regular</option>
                <option value="0" <?php echo ($alumno['status'] == '0') ? 'selected' : ''; ?>>Baja / Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn-guardar">Guardar Cambios</button>
        <a href="consultas.php" class="btn-cancelar">Cancelar</a>
    </form>
    <?php else: ?>
        <p style="text-align:center; color:red;">No se encontraron los datos del alumno.</p>
        <a href="consultas.php" class="btn-cancelar">Regresar</a>
    <?php endif; ?>
</div>

</body>
</html>