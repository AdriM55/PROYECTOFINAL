<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener la película y sus horarios
$pelicula_id = $_GET['id'];
$sql_pelicula = "SELECT * FROM peliculas WHERE id = ?";
$stmt_pelicula = $conexion->prepare($sql_pelicula);
$stmt_pelicula->bind_param("i", $pelicula_id);
$stmt_pelicula->execute();
$pelicula = $stmt_pelicula->get_result()->fetch_assoc();

$sql_horarios = "SELECT * FROM horarios WHERE pelicula_id = ?";
$stmt_horarios = $conexion->prepare($sql_horarios);
$stmt_horarios->bind_param("i", $pelicula_id);
$stmt_horarios->execute();
$horarios = $stmt_horarios->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
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
                    <a href="register.php" class="button">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>   
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="estilo.css">
    <script>
    function seleccionarAsiento(asiento) {
        // Evitar selección si el asiento está ocupado
        if (asiento.classList.contains('ocupado')) return;

        asiento.classList.toggle('seleccionado');
        const inputAsientos = document.getElementById('asientosSeleccionados');
        const seleccionados = Array.from(document.querySelectorAll('.asiento.seleccionado'))
                                   .map(a => a.dataset.asiento);
        inputAsientos.value = seleccionados.join(',');
    }
</script>

    <style>
        .asientos-container {
            display: inline-block;
            margin-top: 20px;
        }
        .asiento {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: #ddd;
            border: 1px solid #aaa;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }
        .asiento.ocupado {
            background-color: #ff6b6b;
            cursor: not-allowed;
        }
        .asiento.seleccionado {
            background-color: #76b852;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Reservar para <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
    <form action="reservas.php" method="POST">
        <input type="hidden" name="pelicula_id" value="<?php echo $pelicula_id; ?>">
        <label for="horario">Selecciona la hora:</label>
        <select name="horario_id" id="horario">
            <?php while ($horario = $horarios->fetch_assoc()): ?>
                <option value="<?php echo $horario['id']; ?>">
                    <?php echo date("H:i", strtotime($horario['horario'])); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div class="asientos-container">
            <h3>Selecciona tus asientos:</h3>
            <input type="hidden" name="asientos" id="asientosSeleccionados">
            <?php
            // Generar asientos 5x5
            $ocupadosQuery = "SELECT asiento FROM reservas WHERE horario_id = ?";
            $stmt_ocupados = $conexion->prepare($ocupadosQuery);
            $stmt_ocupados->bind_param("i", $pelicula_id);
            $stmt_ocupados->execute();
            $ocupadosResult = $stmt_ocupados->get_result();
            $ocupados = [];
            while ($row = $ocupadosResult->fetch_assoc()) {
                $ocupados[] = $row['asiento'];
            }

            for ($i = 1; $i <= 5; $i++) {
                for ($j = 1; $j <= 5; $j++) {
                    $asiento = "$i-$j";
                    $class = in_array($asiento, $ocupados) ? 'asiento ocupado' : 'asiento';
                    echo "<div class=\"$class\" data-asiento=\"$asiento\" onclick=\"seleccionarAsiento(this)\">$asiento</div>";
                }
                echo '<br>';
            }
            ?>
        </div>
        <button type="submit">Reservar</button>
    </form>
</body>
</html>
