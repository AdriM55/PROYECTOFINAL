<?php
// Iniciar la sesión, para poder acceder a la información del usuario que ha iniciado sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: login.php");
    exit();  // Detener la ejecución del script
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para obtener las reservas del usuario
$sql_reservas = "SELECT r.id AS reserva_id, r.horario_id, r.asiento, h.horario, p.titulo
                 FROM reservas r
                 JOIN horarios h ON r.horario_id = h.id
                 JOIN peliculas p ON h.pelicula_id = p.id
                 WHERE r.usuario_id = ?";  // Usamos ? para evitar inyecciones SQL (binding de parámetros)

$stmt_reservas = $conexion->prepare($sql_reservas);  // Preparar la consulta SQL
$stmt_reservas->bind_param("i", $usuario_id);  // Vincular el parámetro (ID del usuario)
$stmt_reservas->execute();  // Ejecutar la consulta
$resultado_reservas = $stmt_reservas->get_result();  // Obtener el resultado de la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="estilo.css">  <!-- Vincula los estilos CSS -->
</head>
<body>
    <!-- Barra de navegación -->
    <header>
        <div class="nav">
            <a href="index.php">Inicio</a> <!-- Enlace a la página principal -->
            <div class="acciones-usuario">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Si el usuario está logueado, mostrar su nombre y opciones -->
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                    <a href="mis_reservas.php">Mis Reservas</a>
                    <a href="logout.php" class="boton">Cerrar Sesión</a>
                <?php else: ?>
                    <!-- Si el usuario no está logueado, mostrar enlaces para iniciar sesión o registrarse -->
                    <a href="login.php" class="boton">Iniciar Sesión</a>
                    <a href="registro.php" class="boton">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Título de la página -->
    <h1>Mis Reservas</h1>

    <?php if ($resultado_reservas->num_rows > 0): ?>
        <!-- Si el usuario tiene reservas, mostrar las reservas en una tabla -->
        <table>
            <thead>
                <tr>
                    <th>Película</th>
                    <th>Horario</th>
                    <th>Asiento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = $resultado_reservas->fetch_assoc()): ?>
                    <!-- Recorrer todas las reservas y mostrar los detalles -->
                    <tr>
                        <td><?php echo htmlspecialchars($reserva['titulo']); ?></td>  <!-- Título de la película -->
                        <td><?php echo date("H:i", strtotime($reserva['horario'])); ?></td>  <!-- Mostrar horario formateado -->
                        <td><?php echo htmlspecialchars($reserva['asiento']); ?></td>  <!-- Mostrar asiento reservado -->
                        <td>
                            <!-- Formulario para cancelar la reserva -->
                            <form action="cancelar_reserva.php" method="POST">
                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['reserva_id']; ?>">  <!-- ID de la reserva -->
                                <button type="submit" class="boton-cancelar">Cancelar</button>  <!-- Botón para cancelar -->
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Si el usuario no tiene reservas, mostrar un mensaje -->
        <p>No tienes reservas.</p>
    <?php endif; ?>
</body>
</html>
