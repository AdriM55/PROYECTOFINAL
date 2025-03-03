<?php
// Iniciar la sesión para manejar las variables de sesión del usuario
session_start();

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario esté logueado (existe una variable de sesión 'usuario_id')
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit; 
}

// Obtener el ID de la película desde la URL
$pelicula_id = $_GET['id'] ?? 0;

// Consultar la información de la película
$stmt_pelicula = $conexion->prepare("SELECT * FROM peliculas WHERE id = ?");
$stmt_pelicula->bind_param("i", $pelicula_id);
$stmt_pelicula->execute();
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();

// Consultar los horarios disponibles para la película
$stmt_horarios = $conexion->prepare("SELECT * FROM horarios WHERE pelicula_id = ?");
$stmt_horarios->bind_param("i", $pelicula_id);
$stmt_horarios->execute();
$horarios = $stmt_horarios->get_result();

// Obtener los asientos ocupados para un horario específico
$ocupados = [];
$horario_id = $_GET['horario_id'] ?? '';

if (!empty($horario_id)) {
    $stmt_ocupados = $conexion->prepare("SELECT asiento FROM reservas WHERE horario_id = ?");
    $stmt_ocupados->bind_param("i", $horario_id);
    $stmt_ocupados->execute();
    $ocupadosResult = $stmt_ocupados->get_result();
    while ($row = $ocupadosResult->fetch_assoc()) {
        $ocupados[] = $row['asiento'];
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

    <script>
        function seleccionarAsiento(asiento) {
            if (asiento.classList.contains('ocupado')) return;
            asiento.classList.toggle('seleccionado');
            const seleccionados = Array.from(document.querySelectorAll('.asiento.seleccionado'))
                .map(a => a.dataset.asiento);
            document.getElementById('asientosSeleccionados').value = seleccionados.join(',');
        }

        function cambiarHorario() {
            const horarioId = document.getElementById('horario').value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('horario_id', horarioId);
            window.location.search = urlParams.toString();
        }

        function validarFormulario() {
            const asientosSeleccionados = document.getElementById('asientosSeleccionados').value;
            if (asientosSeleccionados === '') {
                alert('Debes seleccionar al menos un asiento.');
                return false;
            }
            return true;
        }
    </script>

    <style>
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
        }

    </style>
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

    <h1><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>

    <div class="sinopsis">
        <h2>Sinopsis:</h2>
        <p><?php echo nl2br(htmlspecialchars($pelicula['sinopsis'])); ?></p>
    </div>

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

        <h3>Selecciona tus asientos:</h3>
        <input type="hidden" name="asientos" id="asientosSeleccionados">

        <div class="asientos-container">
            <div class="pantalla">Pantalla</div>
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

        <button type="submit">Agregar al carrito</button>
    </form>

    <h2>Tráiler:</h2>
    <iframe frameborder="0" width="900" height="500" allowfullscreen="" src="<?php echo htmlspecialchars($pelicula['trailer']); ?>"></iframe>

    <script>
        // Imprimir asientos ocupados en consola para depuración
        console.log(<?php echo json_encode($ocupados); ?>);
    </script>
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
