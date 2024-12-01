<?php
session_start();
include 'db.php';

// Obtener todas las películas de la base de datos
$sql = "SELECT * FROM peliculas";
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
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                <a href="logout.php" class="button">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="button">Iniciar Sesión</a>
            <?php endif; ?>
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
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
