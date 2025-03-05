<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $password);
    $stmt->execute();
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
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
                <a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a>
                <a href="logout.php" class="boton">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="button">Iniciar Sesión</a>
                <a href="registro.php" class="button">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</header>

    <h2>Registro de Usuario</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>

    <!-- 🔹 PIE DE PÁGINA -->
    <footer class="piepagina">
    <p>&copy; <?php echo date("Y"); ?> Cine Kursaal. Todos los derechos reservados.</p>
    <p>
        <a href="politica_privacidad.php">Política de Privacidad</a> |
        <a href="aviso_legal.php">Aviso Legal</a>
    </p>
</footer>

<style>
    body {
    text-align: center;
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

</body>
</html>
