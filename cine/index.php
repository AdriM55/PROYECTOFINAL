<?php
session_start();
include 'db.php';

// Obtener todas las películas ordenadas por ID
$sql = "SELECT id, titulo, portada, precio FROM peliculas ORDER BY id";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cine Kursaal</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>

<header>
    <div class="nav">
        <a href="index.php">Inicio</a>
        <div class="acciones-usuario">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                <a href="mis_reservas.php">Mis Reservas</a>
                <a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a>
                <a href="logout.php" class="boton">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="button">Iniciar Sesión</a>
                <a href="registro.php" class="button">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- 🔹 SLIDER DE IMÁGENES (Pegado al menú) -->
<div class="slider-container">
    <div class="slider">
        <?php 
        $imagenes_slider = [
            'slider/image.png',
            'slider/image2.png',
            'slider/image3.png',
            'slider/image4.png',
            'slider/image5.png',
            'slider/image6.png'
        ];
        
        foreach ($imagenes_slider as $imagen): ?>
            <div class="slide">
                <img src="<?php echo $imagen; ?>" alt="Imagen de película">
            </div>
        <?php endforeach; ?>
    </div>
    <button class="prev" onclick="moverSlide(-1)">&#10094;</button>
    <button class="next" onclick="moverSlide(1)">&#10095;</button>
</div>

<main>
    <h1 class="titulocartelera">Cartelera</h1>

    <div class="cartelera" id="cartelera">
        <?php 
        while ($pelicula = $resultado->fetch_assoc()): 
            $imagenPath = $pelicula['portada'];  
            $precio = number_format($pelicula['precio'], 2);
        ?>
        <div class="pelicula">
            <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>">
                <img src="<?php echo $imagenPath; ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <button id="toggleButton" class="boton-mostrar">más</button>
</main>

<!-- 🔹 ESTILOS SLIDER -->
<style>
    body {
        text-align: center;
    }
    /* 🔹 SLIDER (Pegado a la barra de navegación) */
    .slider-container {
        width: 100%;
        max-width: 100%;
        height: auto; /* Se ajusta automáticamente */
        margin: 0 auto;
        overflow: hidden;
    }
    .slide img {
        width: 100%;
        height: auto;
        object-fit: cover; /* Mantiene el tamaño pero puede recortar */
    }

    .slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .slide {
        min-width: 100%;
        display: none;
        text-align: center;
    }

    .prev, .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        font-size: 2rem;
        border-radius: 5px;
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    .prev:hover, .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

</style>

<!-- 🔹 PIE DE PÁGINA -->
<footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a>
    </p>
</footer>

<!-- 🔹 SCRIPT PARA EL SLIDER -->
<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll(".slide");

    function mostrarSlide(n) {
        slides.forEach(slide => slide.style.display = "none");
        slideIndex = (n + slides.length) % slides.length;
        slides[slideIndex].style.display = "block";
    }

    function moverSlide(n) {
        mostrarSlide(slideIndex + n);
    }

    // 🔹 Mostrar la primera imagen y cambiar cada 5s automáticamente
    mostrarSlide(slideIndex);
    setInterval(() => moverSlide(1), 5000);

    // 🔹 Mostrar/Ocultar películas
    document.addEventListener('DOMContentLoaded', () => {
        const peliculas = document.querySelectorAll('.cartelera .pelicula');
        const button = document.getElementById('toggleButton');
        let mostrarMas = true;

        peliculas.forEach((pelicula, index) => {
            if (index >= 10) pelicula.style.display = 'none';
        });

        button.addEventListener('click', () => {
            if (mostrarMas) {
                peliculas.forEach(pelicula => pelicula.style.display = 'block');
                button.textContent = 'menos';
            } else {
                peliculas.forEach((pelicula, index) => {
                    pelicula.style.display = index < 10 ? 'block' : 'none';
                });
                button.textContent = 'más';
            }
            mostrarMas = !mostrarMas;
        });
    });
</script>

</body>
</html>
