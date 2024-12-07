<?php
// Iniciar la sesión para manejar las variables de sesión del usuario
session_start();

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario esté logueado (existe una variable de sesión 'usuario_id')
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigirlo a la página de login
    header("Location: login.php");
    exit;  // Terminar la ejecución del script
}

// Obtener el ID de la película desde la URL (se pasa como parámetro 'id' en la URL)
$pelicula_id = $_GET['id'];

// Consultar la información de la película desde la base de datos
$stmt_pelicula = $conexion->prepare("SELECT * FROM peliculas WHERE id = ?");
$stmt_pelicula->bind_param("i", $pelicula_id);  // Vincular el parámetro de película
$stmt_pelicula->execute();  // Ejecutar la consulta
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();  // Obtener los datos de la película

// Consultar los horarios disponibles para la película
$stmt_horarios = $conexion->prepare("SELECT * FROM horarios WHERE pelicula_id = ?");
$stmt_horarios->bind_param("i", $pelicula_id);  // Vincular el parámetro de la película
$stmt_horarios->execute();  // Ejecutar la consulta
$horarios = $stmt_horarios->get_result();  // Obtener los horarios

// Obtener los asientos ocupados para un horario específico (si está seleccionado)
$ocupados = [];
if (!empty($_GET['horario_id'])) {
    $horario_id = $_GET['horario_id'];  // Obtener el ID del horario seleccionado
    $stmt_ocupados = $conexion->prepare("SELECT asiento FROM reservas WHERE horario_id = ?");
    $stmt_ocupados->bind_param("i", $horario_id);  // Vincular el parámetro del horario
    $stmt_ocupados->execute();  // Ejecutar la consulta
    $ocupadosResult = $stmt_ocupados->get_result();  // Obtener los asientos ocupados
    while ($row = $ocupadosResult->fetch_assoc()) {
        $ocupados[] = $row['asiento'];  // Almacenar los asientos ocupados en un array
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pelicula['titulo']); ?></title>  <!-- Título dinámico con el nombre de la película -->
    <link rel="stylesheet" href="estilo.css">  <!-- Vincula el archivo de estilos CSS -->
    <script>
        // Función para seleccionar un asiento
        function seleccionarAsiento(asiento) {
            if (asiento.classList.contains('ocupado')) return;  // Si el asiento está ocupado, no hacer nada
            asiento.classList.toggle('seleccionado');  // Cambiar la clase para marcar el asiento como seleccionado
            // Obtener todos los asientos seleccionados y actualizarlos en el campo oculto
            const seleccionados = Array.from(document.querySelectorAll('.asiento.seleccionado'))
                .map(a => a.dataset.asiento);
            document.getElementById('asientosSeleccionados').value = seleccionados.join(',');
        }

        // Función para cambiar el horario seleccionado
        function cambiarHorario() {
            const horarioId = document.getElementById('horario').value;  // Obtener el horario seleccionado
            const urlParams = new URLSearchParams(window.location.search);  // Obtener los parámetros de la URL
            urlParams.set('horario_id', horarioId);  // Actualizar el parámetro 'horario_id'
            window.location.search = urlParams.toString();  // Recargar la página con el nuevo parámetro
        }
    </script>
    <style>
        /* Estilos básicos para los asientos */
        .asiento {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: #aaa;
            border: 1px solid #aaa;
            line-height: 40px;
            cursor: pointer;
        }
        /* Asientos ocupados tienen un color distinto y no se pueden seleccionar */
        .asiento.ocupado { background-color: #ff6b6b; cursor: not-allowed; }
        /* Asientos seleccionados tienen un color diferente */
        .asiento.seleccionado { background-color: #76b852; color: #fff; }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <header>
        <div class="nav">
            <a href="index.php">Inicio</a>  <!-- Enlace a la página principal -->
            <div class="user-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Si el usuario está logueado, mostrar su nombre y opciones -->
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                    <a href="mis_reservas.php">Mis Reservas</a>
                    <a href="logout.php" class="button">Cerrar Sesión</a>
                <?php else: ?>
                    <!-- Si el usuario no está logueado, mostrar opciones de login y registro -->
                    <a href="login.php" class="button">Iniciar Sesión</a>
                    <a href="register.php" class="button">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Título y sinopsis de la película -->
    <h1>Reservar para <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>

    <div class="sinopsis">
        <h2>Sinopsis:</h2>
        <p><?php echo nl2br(htmlspecialchars($pelicula['descripcion'])); ?></p>  <!-- Descripción de la película -->
    </div>

    <!-- Formulario de reserva -->
    <form action="reservas.php" method="POST">
        <input type="hidden" name="pelicula_id" value="<?php echo $pelicula_id; ?>">  <!-- ID de la película -->
        
        <label for="horario">Selecciona la hora:</label>
        <select name="horario_id" id="horario" onchange="cambiarHorario()">
            <option value="">Seleccione un horario</option>
            <?php while ($horario = $horarios->fetch_assoc()): ?>
                <option value="<?php echo $horario['id']; ?>" 
                        <?php if (!empty($horario_id) && $horario_id == $horario['id']) echo 'selected'; ?>>
                    <?php echo date("H:i", strtotime($horario['horario'])); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Contenedor de los asientos -->
        <div class="asientos-container">
            <h3>Selecciona tus asientos:</h3>
            <input type="hidden" name="asientos" id="asientosSeleccionados">  <!-- Campo oculto para almacenar los asientos seleccionados -->
            <?php for ($i = 0; $i < 5; $i++): ?>
                <?php for ($j = 0; $j < 5; $j++): ?>
                    <?php 
                        // Asientos identificados por una coordenada como '0-0', '0-1', etc.
                        $asiento = "$i-$j";
                        // Si el asiento está ocupado, agregar la clase 'ocupado'
                        $class = in_array($asiento, $ocupados) ? 'asiento ocupado' : 'asiento';
                    ?>
                    <div class="<?php echo $class; ?>" data-asiento="<?php echo $asiento; ?>" onclick="seleccionarAsiento(this)">
                        <?php echo $asiento; ?>
                    </div>
                <?php endfor; ?>
                <br>
            <?php endfor; ?>
        </div>
        <button type="submit">Reservar</button>  <!-- Botón para enviar el formulario de reserva -->
    </form>
</body>
</html>
