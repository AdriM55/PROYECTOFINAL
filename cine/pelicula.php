<?php
// Iniciar la sesión para manejar las variables de sesión del usuario
session_start();

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario esté logueado (existe una variable de sesión 'usuario_id')
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");  // Si no está logueado, redirige a la página de login
    exit; 
}

// Obtener el ID de la película desde la URL
$pelicula_id = $_GET['id'] ?? 0;  // Si no existe el parámetro 'id', se asigna 0

// Consultar la información de la película
$stmt_pelicula = $conexion->prepare("SELECT * FROM peliculas WHERE id = ?");  // Prepara la consulta
$stmt_pelicula->bind_param("i", $pelicula_id);  // Vincula el parámetro para la consulta
$stmt_pelicula->execute();  // Ejecuta la consulta
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();  // Obtiene los resultados de la consulta

// Consultar los horarios disponibles para la película
$stmt_horarios = $conexion->prepare("SELECT * FROM horarios WHERE pelicula_id = ?");
$stmt_horarios->bind_param("i", $pelicula_id);  // Vincula el parámetro de la película
$stmt_horarios->execute();  // Ejecuta la consulta
$horarios = $stmt_horarios->get_result();  // Obtiene los horarios disponibles

// Obtener los asientos ocupados para un horario específico
$ocupados = [];
$horario_id = $_GET['horario_id'] ?? '';  // Obtiene el horario seleccionado desde la URL

if (!empty($horario_id)) {
    $stmt_ocupados = $conexion->prepare("SELECT asiento FROM reservas WHERE horario_id = ?");
    $stmt_ocupados->bind_param("i", $horario_id);  // Vincula el parámetro para obtener los asientos ocupados
    $stmt_ocupados->execute();  // Ejecuta la consulta
    $ocupadosResult = $stmt_ocupados->get_result();  // Obtiene los resultados de los asientos ocupados
    while ($row = $ocupadosResult->fetch_assoc()) {
        $ocupados[] = $row['asiento'];  // Almacena los asientos ocupados en un array
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">

    <!-- Script para manejar la selección de asientos -->
    <script>
        function seleccionarAsiento(asiento) {
            if (asiento.classList.contains('ocupado')) return;  // Si el asiento está ocupado, no hace nada
            asiento.classList.toggle('seleccionado');  // Cambia el estado de selección del asiento
            const seleccionados = Array.from(document.querySelectorAll('.asiento.seleccionado'))
                .map(a => a.dataset.asiento);  // Obtiene los asientos seleccionados
            document.getElementById('asientosSeleccionados').value = seleccionados.join(',');  // Guarda los asientos seleccionados en un campo oculto
        }

        // Cambiar el horario seleccionado
        function cambiarHorario() {
            const horarioId = document.getElementById('horario').value;  // Obtiene el valor del horario
            const urlParams = new URLSearchParams(window.location.search);  // Obtiene los parámetros de la URL
            urlParams.set('horario_id', horarioId);  // Establece el nuevo parámetro de horario
            window.location.search = urlParams.toString();  // Recarga la página con el nuevo horario
        }

        // Validar el formulario antes de enviarlo
        function validarFormulario() {
            if (!document.getElementById('asientosSeleccionados').value.trim()) {  // Si no hay asientos seleccionados
                alert('Debes seleccionar al menos un asiento.');
                return false;  // No se envía el formulario
            }
            return true;  // El formulario se envía
        }
    </script>

    <style>
    .titulo-pelicula{
        text-align: center;
        margin-bottom: 20px;
    }
    .contenedor-pelicula {
        display: flex;
        margin: 20px auto;
        max-width: 800px;
        text-align: center;
        background-color: #202020;
        border-radius: 12px;
        box-shadow: 0px 4px 20px #202020;
        color: white;
        padding: 20px;
    }

    .portada img {
        max-width: 200px;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .detalles-pelicula {
        font-size: 18px;
    }

    .sinopsis {
        text-align: center;
    }

    .asiento {
        display: inline-block;
        width: 60px;
        height: 60px;
        margin: 5px;
        background-image: url('asientos/disponible.png');
        background-size: cover;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .asiento.ocupado {
        background-image: url('asientos/ocupado.png');
        cursor: not-allowed;
    }

    .asiento.seleccionado {
        background-image: url('asientos/seleccionado.png');
        transform: scale(1.1); /* Pequeño zoom cuando se selecciona */
    }

    </style>
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

<h1 class="titulo-pelicula"><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
<div class="contenedor-pelicula">
    <div class="portada">
        <img src="<?php echo htmlspecialchars($pelicula['portada']); ?>" alt="Portada de <?php echo htmlspecialchars($pelicula['titulo']); ?>">
    </div>
    <div class="detalles-pelicula">
        <p><strong>Duración:</strong> <?php echo htmlspecialchars($pelicula['duracion']); ?> min</p>
        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($pelicula['categoria']); ?></p>
        <p><strong>Director:</strong> <?php echo htmlspecialchars($pelicula['director']); ?></p>
        <div class="sinopsis">
        <h2>Sinopsis:</h2>
        <p><?php echo nl2br(htmlspecialchars($pelicula['sinopsis'])); ?></p>
    </div>
    </div>
</div>

<div style="text-align: center;">
    <form action="carrito.php" method="POST" onsubmit="return validarFormulario()">
        <input type="hidden" name="pelicula_id" value="<?php echo $pelicula_id; ?>">
        
        <label for="horario">Selecciona la hora:</label>
        <select name="horario_id" id="horario" onchange="cambiarHorario()" required>
            <option value="">Seleccione un horario</option>
            <?php while ($horario = $horarios->fetch_assoc()): ?>
                <option value="<?php echo $horario['id']; ?>" 
                        <?php if ($horario_id == $horario['id']) echo 'selected'; ?>>
                    <?php echo date("H:i", strtotime($horario['horario'])); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <h2 style="background-color: #202020;">ELIGE TU ASIENTO:</h2>
        <input type="hidden" name="asientos" id="asientosSeleccionados">

        <div class="asientos-container">
            <div class="pantalla">PANTALLA</div>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <?php for ($j = 0; $j < 5; $j++): ?>
                    <?php 
                        $asiento = "$i-$j";
                        $class = in_array($asiento, $ocupados) ? 'asiento ocupado' : 'asiento';
                    ?>
                    <div class="<?php echo $class; ?>" data-asiento="<?php echo $asiento; ?>" onclick="seleccionarAsiento(this)">
                        <?php echo $asiento; ?>
                    </div>
                <?php endfor; ?>
                <br>
            <?php endfor; ?>
        </div>

        <button class="botonagregar" type="submit">Comprar entradas</button>
    </form>

    <h2>Tráiler:</h2>
    <iframe frameborder="0" width="900" height="500" allowfullscreen="" src="<?php echo htmlspecialchars($pelicula['trailer']); ?>"></iframe>

    <script>
        // Imprimir asientos ocupados en consola para depuración
        console.log(<?php echo json_encode($ocupados); ?>);
    </script>
</div>

<!-- 🔹 PIE DE PÁGINA -->
<footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a>
    </p>
</footer>
</body>
</html>
