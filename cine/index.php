<?php
session_start(); // Inicia una nueva sesión o continúa la existente.
include 'db.php'; // Incluye el archivo de conexión a la base de datos.

// Consulta SQL para obtener todas las películas ordenadas por ID
$sql = "SELECT id, titulo, portada, precio FROM peliculas ORDER BY id";
$resultado = $conexion->query($sql); // Ejecuta la consulta y guarda el resultado.
?>

<!DOCTYPE html>
<html lang="es"> <!-- Indica que el idioma de la página es español -->
<head>
    <meta charset="UTF-8"> <!-- Define el conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Asegura que la página sea responsive -->
    <title>Cine Kursaal</title> <!-- Título de la página -->
    <link rel="stylesheet" href="estilo.css"> <!-- Enlace al archivo CSS para los estilos -->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico"> <!-- Favicon de la página -->
</head>
<body>

<header>
    <div class="nav">
        <!-- Enlaces de navegación -->
        <a href="index.php">Inicio</a>
        <div class="acciones-usuario">
            <?php if (isset($_SESSION['usuario_id'])): ?> <!-- Verifica si el usuario ha iniciado sesión -->
                <a href="perfil.php" style="color: white;"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></a> <!-- Muestra el nombre del usuario -->
                <a href="mis_reservas.php">Mis Reservas</a> <!-- Enlace a las reservas del usuario -->
                <a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a> <!-- Muestra el número de artículos en el carrito -->
                <a href="logout.php" class="boton">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
            <?php else: ?> <!-- Si el usuario no ha iniciado sesión -->
                <a href="login.php" class="button">Iniciar Sesión</a> <!-- Enlace para iniciar sesión -->
                <a href="registro.php" class="button">Registrarse</a> <!-- Enlace para registrarse -->
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- 🔹 SLIDER DE IMÁGENES -->
<div class="slider-container">
    <div class="slider">
        <?php 
        // Definición de las imágenes del slider
        $imagenes_slider = [
            'slider/image.png',
            'slider/image2.png',
            'slider/image3.png',
            'slider/image4.png',
            'slider/image5.png',
            'slider/image6.png'
        ];
        
        // Bucle para mostrar todas las imágenes del slider
        foreach ($imagenes_slider as $imagen): ?>
            <div class="slide">
                <img src="<?php echo $imagen; ?>" alt="Imagen de película"> <!-- Muestra la imagen de cada slide -->
            </div>
        <?php endforeach; ?>
    </div>
    <button class="prev" onclick="moverSlide(-1)">&#10094;</button> <!-- Botón para mover el slider a la izquierda -->
    <button class="next" onclick="moverSlide(1)">&#10095;</button> <!-- Botón para mover el slider a la derecha -->
</div>

<main>
    <h1 class="titulocartelera">Cartelera</h1> <!-- Título de la sección de cartelera -->

    <div class="cartelera" id="cartelera">
        <?php 
        // Bucle para mostrar las películas obtenidas de la base de datos
        while ($pelicula = $resultado->fetch_assoc()): 
            $imagenPath = $pelicula['portada'];  
            $precio = number_format($pelicula['precio'], 2); // Formatea el precio de la película
        ?>
        <div class="pelicula">
            <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>"> <!-- Enlace a la página de detalles de la película -->
                <img src="<?php echo $imagenPath; ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>"> <!-- Muestra la imagen de la película -->
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <button id="toggleButton" class="boton-mostrar">más</button> <!-- Botón para mostrar u ocultar más películas -->
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
        overflow: hidden; /* Oculta las imágenes fuera del contenedor */
    }
    .slide img {
        width: 100%;
        height: auto;
        object-fit: cover; /* Mantiene el tamaño de la imagen, recortando si es necesario */
    }

    .slider {
        display: flex;
        transition: transform 0.5s ease-in-out; /* Transición suave al cambiar de imagen */
    }

    .slide {
        min-width: 100%; /* Cada imagen ocupa el 100% del ancho del contenedor */
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

    .prev { left: 10px; } /* Posición del botón "prev" */
    .next { right: 10px; } /* Posición del botón "next" */

    .prev:hover, .next:hover {
        background-color: rgba(0, 0, 0, 0.8); /* Efecto hover sobre los botones */
    }

</style>

<!-- 🔹 PIE DE PÁGINA -->
<footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p> <!-- Año actual -->
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a> <!-- Enlaces a las páginas legales -->
    </p>
</footer>

<!-- 🔹 SCRIPT PARA EL SLIDER -->
<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll(".slide");

    function mostrarSlide(n) {
        slides.forEach(slide => slide.style.display = "none"); // Oculta todas las imágenes
        slideIndex = (n + slides.length) % slides.length; // Calcula el índice correcto de la imagen a mostrar
        slides[slideIndex].style.display = "block"; // Muestra la imagen correspondiente
    }

    function moverSlide(n) {
        mostrarSlide(slideIndex + n); // Mueve el slider hacia adelante o atrás
    }

    // 🔹 Mostrar la primera imagen y cambiar cada 5s automáticamente
    mostrarSlide(slideIndex);
    setInterval(() => moverSlide(1), 5000); // Cambia la imagen cada 5 segundos

    // 🔹 Mostrar/Ocultar películas
    document.addEventListener('DOMContentLoaded', () => {
        const peliculas = document.querySelectorAll('.cartelera .pelicula');
        const button = document.getElementById('toggleButton');
        let mostrarMas = true;

        // Inicialmente, oculta las películas después de la décima
        peliculas.forEach((pelicula, index) => {
            if (index >= 10) pelicula.style.display = 'none';
        });

        // Al hacer clic en el botón, cambia la visibilidad de las películas
        button.addEventListener('click', () => {
            if (mostrarMas) {
                peliculas.forEach(pelicula => pelicula.style.display = 'block');
                button.textContent = 'menos'; // Cambia el texto del botón
            } else {
                peliculas.forEach((pelicula, index) => {
                    pelicula.style.display = index < 10 ? 'block' : 'none'; // Vuelve a mostrar solo las primeras 10 películas
                });
                button.textContent = 'más'; // Cambia el texto del botón
            }
            mostrarMas = !mostrarMas; // Alterna entre mostrar más y mostrar menos
        });
    });
</script>

</body>
</html>
