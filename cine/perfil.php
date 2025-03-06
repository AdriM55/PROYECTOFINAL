<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = "";
$mensaje_error = "";

// Obtener datos actuales del usuario
$consulta = "SELECT nombre, email, password FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Procesar actualización de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];

    if ($nuevo_nombre === $usuario['nombre'] && $nuevo_email === $usuario['email']) {
        $mensaje_error = "No has realizado ningún cambio.";
    } else {
        // Verificar si el nombre o el email ya están en uso por otro usuario
        $sql_verificar = "SELECT id FROM usuarios WHERE (nombre = ? OR email = ?) AND id != ?";
        $stmt_verificar = $conexion->prepare($sql_verificar);
        $stmt_verificar->bind_param("ssi", $nuevo_nombre, $nuevo_email, $usuario_id);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result();

        if ($resultado_verificar->num_rows > 0) {
            $mensaje_error = "El nombre de usuario o el email ya están en uso.";
        } else {
            // Actualizar los datos
            $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $nuevo_nombre, $nuevo_email, $usuario_id);
            $stmt->execute();

            $_SESSION['nombre_usuario'] = $nuevo_nombre;
            header("Location: index.php"); // Redirige al index después de la actualización
            exit();
        }
    }
}

// Procesar actualización de contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_password'])) {
    $actual_password = $_POST['actual_password'];
    $nueva_password = $_POST['nueva_password'];

    if (password_verify($actual_password, $usuario['password'])) {
        if (password_verify($nueva_password, $usuario['password'])) {
            $mensaje_error = "La nueva contraseña no puede ser igual a la anterior.";
        } else {
            $password_hash = password_hash($nueva_password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $password_hash, $usuario_id);
            $stmt->execute();
            header("Location: index.php"); // Redirige al index después de la actualización
            exit();
        }
    } else {
        $mensaje_error = "La contraseña actual es incorrecta.";
    }
}

// Procesar eliminación de cuenta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_cuenta'])) {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();

    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
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

<div class="form-container">
    <h2>Perfil de Usuario</h2>

    <?php if (!empty($mensaje_error)): ?>
        <p class="mensaje-error"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <div id="perfil-info">
        <p><strong>Nombre:</strong> <span id="nombre-text"><?php echo htmlspecialchars($usuario['nombre']); ?></span></p>
        <p><strong>Email:</strong> <span id="email-text"><?php echo htmlspecialchars($usuario['email']); ?></span></p>
        <button onclick="editarPerfil()">Editar Perfil</button>
    </div>

    <form method="POST" id="perfil-form" style="display: none;">
        <input type="text" name="nombre" id="nombre-input" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        <input type="email" name="email" id="email-input" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        <button type="submit" name="actualizar">Guardar Cambios</button>
    </form>

    <h3>Cambiar Contraseña</h3>
    <form method="POST">
        <input type="password" name="actual_password" placeholder="Contraseña actual" required>
        <input type="password" name="nueva_password" placeholder="Nueva contraseña" required>
        <button type="submit" name="cambiar_password">Actualizar Contraseña</button>
    </form>

    <h3>Eliminar Cuenta</h3>
    <form method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">
        <button type="submit" name="eliminar_cuenta" class="boton-eliminar">Eliminar Cuenta</button>
    </form>
</div>

<footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a>
    </p>
</footer>

<style>
    .form-container {
        background-color: #222;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
        width: 350px;
        margin: 50px auto;
        text-align: center;
    }
    input, button {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ffcc00;
        border-radius: 5px;
        background-color: #303030;
        color: white;
        font-size: 16px;
    }
    input::placeholder {
        color: #bbb;
    }
    button {
        background-color: #ff4500;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    button:hover {
        background-color: #e63900;
        transform: scale(1.05);
    }
    .mensaje-error {
        color: red;
        font-weight: bold;
    }

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
</style>

<script>
    function editarPerfil() {
        document.getElementById("perfil-info").style.display = "none";
        document.getElementById("perfil-form").style.display = "block";
    }
</script>

</body>
</html>
