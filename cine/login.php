<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre'];
    $contrasena = $_POST['password'];

    // Verificar si el usuario existe
    $consulta = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    // Validar contraseña
    if ($usuario && password_verify($contrasena, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
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
                <a href="logout.php" class="boton">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="boton">Iniciar Sesión</a>
                <a href="registro.php" class="boton">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<h2>Iniciar Sesión</h2>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar Sesión</button>
</form>
</body>
</html>
