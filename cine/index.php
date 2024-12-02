<?php
session_start();
include 'db.php';

// Obtener todas las películas y sus horarios
$sql = "SELECT p.id, p.titulo, p.imagen, GROUP_CONCAT(h.horario ORDER BY h.horario SEPARATOR ', ') AS horarios 
        FROM peliculas p
        LEFT JOIN horarios h ON p.id = h.pelicula_id
        GROUP BY p.id";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera de Cine</title>
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

    <h1>Cartelera de Cine</h1>
    <div class="cartelera">
        <?php while ($pelicula = $resultado->fetch_assoc()): ?>
            <div class="pelicula">
                <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>">
                    <img src="img/<?php echo htmlspecialchars($pelicula['imagen']); ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
                    <h2><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
                </a>
                <div class="horarios">
                    <?php 
                    if (!empty($pelicula['horarios'])) {
                        $horarios = explode(', ', $pelicula['horarios']);
                        foreach ($horarios as $horario): ?>
                            <span class="horario"><?php echo htmlspecialchars(date("H:i", strtotime($horario))); ?></span>
                        <?php endforeach; 
                    } else {
                        echo "<span class='sin-horarios'>No hay horarios disponibles</span>";
                    }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
