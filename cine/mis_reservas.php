<?php
// Iniciar la sesión, para poder acceder a la información del usuario que ha iniciado sesión
session_start();
session_regenerate_id(true);  // Regenerar el ID de la sesión para mayor seguridad

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: login.php");
    exit();  // Detener la ejecución del script
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para obtener las reservas del usuario
$sql_reservas = "SELECT r.id AS reserva_id, r.horario_id, r.asiento, h.horario, p.titulo
                 FROM reservas r
                 JOIN horarios h ON r.horario_id = h.id
                 JOIN peliculas p ON h.pelicula_id = p.id
                 WHERE r.usuario_id = ?";  // Usamos ? para evitar inyecciones SQL (binding de parámetros)

$stmt_reservas = $conexion->prepare($sql_reservas);  // Preparar la consulta SQL
$stmt_reservas->bind_param("i", $usuario_id);  // Vincular el parámetro (ID del usuario)
$stmt_reservas->execute();  // Ejecutar la consulta
$resultado_reservas = $stmt_reservas->get_result();  // Obtener el resultado de la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="estilo.css">  <!-- Vincula los estilos CSS -->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>
    <!-- Barra de navegación -->
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

    <!-- Título de la página -->
    <h1>Mis Reservas</h1>

    <?php if ($resultado_reservas->num_rows > 0): ?>
        <!-- Si el usuario tiene reservas, mostrar las reservas en una tabla -->
        <table class="tabla-reservas">
            <thead>
                <tr>
                    <th>Película</th>
                    <th>Horario</th>
                    <th>Asiento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = $resultado_reservas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reserva['titulo']); ?></td>
                        <td><?php echo date("H:i", strtotime($reserva['horario'])); ?></td>
                        <td><?php echo htmlspecialchars($reserva['asiento']); ?></td>
                        <td>
                            <!-- Formulario para cancelar la reserva -->
                            <form action="cancelar_reserva.php" method="POST">
                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['reserva_id']; ?>">
                                <button type="submit" class="boton-cancelar">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes reservas.</p>
    <?php endif; ?>

    <!-- PIE DE PÁGINA -->
    <footer class="piepagina">
        <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
        <p>
            <a href="politica_privacidad.php">Política de Privacidad</a> |
            <a href="aviso_legal.php">Aviso Legal</a>
        </p>
    </footer>

    <style>
        .piepagina {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .tabla-reservas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabla-reservas th, .tabla-reservas td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .tabla-reservas th {
            background-color:rgb(255, 0, 0);
        }

        .boton-cancelar {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .boton-cancelar:hover {
            background-color: #d32f2f;
        }

        @media (max-width: 768px) {
            .nav {
                display: block;
                text-align: center;
            }

            .tabla-reservas th, .tabla-reservas td {
                font-size: 14px;
            }
        }
    </style>

</body>
</html>
