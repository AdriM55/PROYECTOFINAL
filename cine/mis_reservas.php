<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener todas las reservas del usuario
$sql_reservas = "SELECT r.id AS reserva_id, r.horario_id, r.asiento, h.horario, p.titulo
                 FROM reservas r
                 JOIN horarios h ON r.horario_id = h.id
                 JOIN peliculas p ON h.pelicula_id = p.id
                 WHERE r.usuario_id = ?";
$stmt_reservas = $conexion->prepare($sql_reservas);
$stmt_reservas->bind_param("i", $usuario_id);
$stmt_reservas->execute();
$reservas_result = $stmt_reservas->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header>
        <div class="nav">
            <a href="index.php">Inicio</a>
             <!-- Enlace para acceder a las reservas -->
            <div class="user-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                    <a href="mis_reservas.php">Mis Reservas</a>
                    <a href="logout.php" class="button">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php" class="button">Iniciar Sesión</a>
                    <a href="registro.php" class="button">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <h1>Mis Reservas</h1>
    <?php if ($reservas_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Pelicula</th>
                    <th>Horario</th>
                    <th>Asiento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = $reservas_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reserva['titulo']); ?></td>
                        <td><?php echo date("H:i", strtotime($reserva['horario'])); ?></td>
                        <td><?php echo htmlspecialchars($reserva['asiento']); ?></td>
                        <td>
                            <form action="cancelar_reserva.php" method="POST">
                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['reserva_id']; ?>">
                                <button type="submit" class="cancelar-btn">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes reservas.</p>
    <?php endif; ?>
</body>
</html>
