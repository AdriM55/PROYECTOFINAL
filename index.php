<?php
session_start();
include 'db.php';

// Obtener todas las películas ordenadas por ID
$sql = "SELECT id, titulo FROM peliculas ORDER BY id";
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
            <div class="acciones-usuario">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                    <a href="mis_reservas.php">Mis Reservas</a>
                    <a href="logout.php" class="boton">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php" class="button">Iniciar Sesión</a>
                    <a href="registro.php" class="button">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <h1>Cartelera de Cine</h1>
        <div class="cartelera">
            <?php 
            $contador = 1; // Contador para asignar imágenes secuenciales
            while ($pelicula = $resultado->fetch_assoc()): 
                $imagenPath = "img/{$contador}.jpg"; // Ruta de la imagen
            ?>
                <div class="pelicula">
                    <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>">
                        <img src="<?php echo $imagenPath; ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
                        <h2><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
                    </a>
                </div>
            <?php 
            $contador++; // Incrementar contador
            endwhile; 
            ?>
        </div>
    </main>
</body>
</html>
