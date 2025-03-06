<?php
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar si el usuario o email ya existen
    $consulta = "SELECT * FROM usuarios WHERE nombre = ? OR email = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("ss", $nombre, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $error = "El nombre de usuario o el correo electrónico ya están en uso.";
    } else {
        // Insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $email, $password);
        $stmt->execute();
        header("Location: login.php");
        exit();
    }
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
    <h2>Registro de Usuario</h2>
    
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
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
