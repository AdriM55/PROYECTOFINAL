<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener la película y sus horarios
$pelicula_id = $_GET['id'];
$sql_pelicula = "SELECT * FROM peliculas WHERE id = ?";
$stmt_pelicula = $conexion->prepare($sql_pelicula);
$stmt_pelicula->bind_param("i", $pelicula_id);
$stmt_pelicula->execute();
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();

$sql_horarios = "SELECT * FROM horarios WHERE pelicula_id = ?";
$stmt_horarios = $conexion->prepare($sql_horarios);
$stmt_horarios->bind_param("i", $pelicula_id);
$stmt_horarios->execute();
$horarios = $stmt_horarios->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="estilo.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Reservar para <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
    <div>
        <h3>Selecciona la hora:</h3>
        <select id="horario">
            <?php while ($horario = $horarios->fetch_assoc()): ?>
                <option value="<?php echo $horario['id']; ?>">
                    <?php echo date("H:i", strtotime($horario['horario'])); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="asientos">
        <h3>Selecciona tu asiento:</h3>
        <div id="grid-asientos"></div>
    </div>
    <button onclick="reservar()">Reservar Ticket</button>
</body>
</html>
