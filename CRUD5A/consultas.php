<?php
include_once 'conexion.php';
try{
    $conn=getConnection();
    $sql="SELECT matricula, nombre, apaterno, amaterno, fecha_nacimiento FROM Alumnos";
    $result=$conn->query($sql);
    $Alumnos=$result->fetchAll();
}catch (PDOException $e){
    echo "error de datos". $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
</head>
<body>
    <h1>Consultas de Alumnos</h1><br>
    <table border="1">
        <th>Matricula</th>
        <th>Nombre</th>
        <th>Apellido_p</th>
        <th>Apellido_m</th>
        <th>Fecha de Nacimiento</th>
        <?php foreach ($Alumnos as $Alumno){ ?>
        <tr>
            <td><?php echo $Alumno['matricula'];?></td>
            <td><?php echo $Alumno['nombre'];?></td>
            <td><?php echo $Alumno['apaterno'];?></td>
            <td><?php echo $Alumno['amaterno'];?></td>
            <td><?php echo $Alumno['fecha_nacimiento'];?></td>
        </tr>
        <?php }?>
    </table>



</body>
</html>