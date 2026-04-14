<?php
session_start();

// Proteger la página (Si no tiene el pase, lo mandamos al login)
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php");
    exit(); 
}

include_once 'conexion1.php';
try {
    $conn = getConnection();
    
    // Agregamos "WHERE status = '1'" para ignorar a los dados de baja
    $sql = "SELECT matricula, nombre, apaterno, amaterno, fecha_nacimiento, status FROM alumnos WHERE status = '1'";
    
    $result = $conn->query($sql);
    $alumnos = $result->fetchAll();
} catch (PDOException $e) {
    echo "Error de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas - UTCAM</title>
    <link rel="stylesheet" href="estilos.css">

    <style>
        /* Estilos rápidos para el botón y la alerta */
        .btn-nuevo {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alerta-exito {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
    <a href="logout.php" style="background-color: #CE1126; color: white; padding: 0.6rem 1.2rem; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.9rem; margin-left: 10px;">
    <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
</a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <h1>Consultas de Alumnos</h1>

    <div class="table-wrapper">
    <a href="insertar_alumnos.php" class="btn-nuevo">+ Agregar Nuevo Alumno</a>

    <!-- Añadimos el ID 'alerta' para manipularlo con JS -->
     <?php if(isset($_GET['mensaje'])): ?>
    <div id="alerta" class="alerta-exito" style="<?php 
        if($_GET['mensaje'] == 'deleted') echo 'background-color: #f8d7da; color: #721c24;'; 
        if($_GET['mensaje'] == 'edited')  echo 'background-color: #cce5ff; color: #004085;'; 
    ?>">
        <?php 
            if($_GET['mensaje'] == 'success') echo "¡Alumno registrado correctamente!";
            if($_GET['mensaje'] == 'deleted') echo "¡Alumno eliminado correctamente!";
            if($_GET['mensaje'] == 'edited')  echo "¡Alumno actualizado correctamente!";
        ?>
    </div>
    <?php endif; ?>



        <table>
            <thead>
                <tr>
                    <th>Matricula</th> 
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($alumno['matricula']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['apaterno']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['amaterno']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                    <!-- En tu tabla, dentro del foreach -->

<td>
    <a href="editar_alumno.php?matricula=<?php echo $alumno['matricula']; ?>" 
       class="btn-editar" 
       style="color: #007bff; text-decoration: none; margin-right: 15px;">
        <i class="fa-solid fa-pen-to-square"></i> Editar
    </a>

    <a href="#" 
       class="btn-eliminar" 
       data-matricula="<?php echo $alumno['matricula']; ?>" 
       style="color: #d9534f; text-decoration: none;">
        <i class="fa-solid fa-trash-can"></i>
    </a>
</td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
   <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. CONFIGURACIÓN DE COLORES INSTITUCIONALES ---
        const COLOR_PRIMARIO = '#1b5e20'; 
        const COLOR_PELIGRO   = '#d32f2f'; 
        const COLOR_CANCELAR  = '#6c757d'; 

        // --- 2. MANEJO DE ELIMINACIÓN CON MODAL PROFESIONAL ---
        const botonesEliminar = document.querySelectorAll('.btn-eliminar');
        
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function(e) {
                e.preventDefault();
                const matricula = this.getAttribute('data-matricula');

                Swal.fire({
                    title: '¿Confirmar eliminación?',
                    text: `Estás a punto de borrar al alumno con matrícula: ${matricula}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: COLOR_PELIGRO,
                    cancelButtonColor: COLOR_CANCELAR,
                    confirmButtonText: '<i class="fa-solid fa-trash"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `eliminar_alumno.php?matricula=${matricula}`;
                    }
                });
            });
        });

        // --- 3. ALERTAS DE ÉXITO (SweetAlert) ---
        const urlParams = new URLSearchParams(window.location.search);
        const mensaje = urlParams.get('mensaje');

        if (mensaje) {
            let config = {
                timer: 2500,
                showConfirmButton: false,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            };

            if (mensaje === 'success') {
                Swal.fire({
                    ...config,
                    icon: 'success',
                    title: '¡Registro Exitoso!',
                    text: 'El alumno ha sido añadido correctamente.'
                });
            } else if (mensaje === 'deleted') {
                Swal.fire({
                    ...config,
                    icon: 'info',
                    title: 'Registro Eliminado',
                    text: 'Los datos se borraron del sistema.',
                    iconColor: COLOR_PELIGRO
                });
            } else if (mensaje === 'edited') {
                Swal.fire({
                    ...config,
                    icon: 'success',
                    title: '¡Actualización Exitosa!',
                    text: 'Los datos del alumno han sido modificados.',
                    iconColor: '#007bff'
                });
            }

            // Limpiar la URL sin recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // --- 4. ALERTA HTML (La cajita de arriba) ---
        const alerta = document.getElementById('alerta');
        if (alerta) {
            setTimeout(() => {
                alerta.style.transition = "opacity 0.5s ease";
                alerta.style.opacity = "0";
                setTimeout(() => alerta.remove(), 500);
            }, 3000);
        }
    });
    </script>



    <footer>Sistema de Alumnos &mdash; CRUD5A</footer>
</body>
</html>