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
                <a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a>
                <a href="logout.php" class="boton">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="button">Iniciar Sesión</a>
                <a href="registro.php" class="button">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div class="form-container">
<h2>Iniciar Sesión</h2>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar Sesión</button>
</form>
</div>
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
