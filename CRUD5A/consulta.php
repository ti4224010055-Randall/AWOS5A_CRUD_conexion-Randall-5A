<?php
include_once 'conexion.php';
try {
    $conn=getConnection();
    $sql="SELECT matricula, nombre, apaterno, amaterno, fecha_nacimiento FROM alumnos";
    $result=$conn->query($sql);
    $alumnos=$result->fetchAll();
    } catch (PDOException $e) {
        echo "Error de datos" . $e->getMessage();
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Consultas de Alumnos</h1>
    <div class="table-wrapper">
        <table>
            <th>Matricula</th> 
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Fecha de Nacimiento</th>
            <?php foreach ($alumnos as $alumno) { ?>
            <tr>
            <td><?php echo $alumno['matricula']; ?></td>
            <td><?php echo $alumno['nombre']; ?></td>
            <td><?php echo $alumno['apaterno']; ?></td>
            <td><?php echo $alumno['amaterno']; ?></td>
            <td><?php echo $alumno['fecha_nacimiento']; ?></td>
            </tr>
            <?php } ?>
                
           
        </table>
    </div>
    <footer>Sistema de Alumnos &mdash; CRUD5A</footer>

</body>
</html>