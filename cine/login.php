<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    // Verificar usuario por nombre
    $sql = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    // Validar contraseña y crear sesión
    if ($usuario && password_verify($password, $usuario['password'])) {
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
</head>
<body>
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
                    <a href="registro.php" class="button">Registrarse</a>
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
