<?php
session_start();
include 'db.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener película
$pelicula_id = $_GET['id'];
$stmt_pelicula = $conexion->prepare("SELECT * FROM peliculas WHERE id = ?");
$stmt_pelicula->bind_param("i", $pelicula_id);
$stmt_pelicula->execute();
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();

// Obtener horarios
$stmt_horarios = $conexion->prepare("SELECT * FROM horarios WHERE pelicula_id = ?");
$stmt_horarios->bind_param("i", $pelicula_id);
$stmt_horarios->execute();
$horarios = $stmt_horarios->get_result();

// Obtener asientos ocupados
$ocupados = [];
if (!empty($_GET['horario_id'])) {
    $horario_id = $_GET['horario_id'];
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
    </script>
    <style>
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
        .asiento.ocupado { background-color: #ff6b6b; cursor: not-allowed; }
        .asiento.seleccionado { background-color: #76b852; color: #fff; }
    </style>
</head>
<body>
    <header>
        <div class="nav">
            <a href="index.php">Inicio</a>
            <div class="user-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</span>
                    <a href="mis_reservas.php">Mis Reservas</a>
                    <a href="logout.php" class="button">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php" class="button">Iniciar Sesión</a>
                    <a href="register.php" class="button">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <h1>Reservar para <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>

    <form action="reservas.php" method="POST">
        <input type="hidden" name="pelicula_id" value="<?php echo $pelicula_id; ?>">
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

        <div class="asientos-container">
            <h3>Selecciona tus asientos:</h3>
            <input type="hidden" name="asientos" id="asientosSeleccionados">
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
        <button type="submit">Reservar</button>
    </form>
</body>
</html>
