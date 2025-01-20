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
    <div class="cartelera" id="cartelera">
        <?php 
        $contador = 1;
        while ($pelicula = $resultado->fetch_assoc()): 
            $imagenPath = "img/{$contador}.jpg"; 
        ?>
        <div class="pelicula" data-index="<?php echo $contador; ?>">
            <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>">
                <img src="<?php echo $imagenPath; ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
            </a>
        </div>
        <?php 
        $contador++;
        endwhile; 
        ?>
    </div>
    <button id="toggleButton" class="boton-mostrar">Mostrar más</button>
</main>

<script>
    // Lógica para mostrar/ocultar películas
    document.addEventListener('DOMContentLoaded', () => {
        const peliculas = document.querySelectorAll('.cartelera .pelicula');
        const button = document.getElementById('toggleButton');
        let mostrarMas = true;

        // Mostrar solo las primeras 5 películas al inicio
        peliculas.forEach((pelicula, index) => {
            if (index >= 10) pelicula.style.display = 'none';
        });

        // Alternar mostrar más/menos
        button.addEventListener('click', () => {
            if (mostrarMas) {
                peliculas.forEach(pelicula => pelicula.style.display = 'block');
                button.textContent = 'Mostrar menos';
            } else {
                peliculas.forEach((pelicula, index) => {
                    pelicula.style.display = index < 10 ? 'block' : 'none';
                });
                button.textContent = 'Mostrar más';
            }
            mostrarMas = !mostrarMas;
        });
    });
</script>
</body>
</html>
