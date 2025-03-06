<?php
session_start();
include 'db.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar entrada al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pelicula_id = (int)$_POST['pelicula_id']; // Validar que el ID sea un número entero
    $horario_id = (int)$_POST['horario_id'];
    $asientos = array_map('trim', explode(',', $_POST['asientos']));  // Sanitizar y eliminar espacios

    if (empty($asientos[0])) {
        // Si no se seleccionan asientos, mostrar mensaje de error
        $_SESSION['error'] = "Debes seleccionar al menos un asiento.";
        header("Location: pelicula.php?id=$pelicula_id");
        exit;
    }

    // Guardar en la sesión
    foreach ($asientos as $asiento) {
        $_SESSION['carrito'][] = [
            'pelicula_id' => $pelicula_id,
            'horario_id' => $horario_id,
            'asiento' => $asiento
        ];
    }
}

// Obtener detalles de las películas
$carrito = $_SESSION['carrito'];
$detalles = [];

if (!empty($carrito)) {
    $placeholders = implode(',', array_fill(0, count($carrito), '(?, ?)')); // Para usar en el IN de la consulta
    $query = "SELECT p.titulo, h.horario, p.precio, p.id AS pelicula_id, h.id AS horario_id 
              FROM peliculas p 
              JOIN horarios h ON p.id = h.pelicula_id 
              WHERE (p.id, h.id) IN ($placeholders)";
    
    $stmt = $conexion->prepare($query);
    
    // Bind params para todas las combinaciones de película_id y horario_id
    $types = str_repeat('ii', count($carrito));  // 'ii' por cada par (pelicula_id, horario_id)
    $params = [];
    foreach ($carrito as $item) {
        $params[] = $item['pelicula_id'];
        $params[] = $item['horario_id'];
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Asociar los resultados con las entradas del carrito
    while ($row = $result->fetch_assoc()) {
        foreach ($carrito as $item) {
            if ($item['pelicula_id'] == $row['pelicula_id'] && $item['horario_id'] == $row['horario_id']) {
                $detalles[] = [
                    'titulo' => $row['titulo'],
                    'horario' => date("H:i", strtotime($row['horario'])),
                    'asiento' => $item['asiento'],
                    'precio' => number_format($row['precio'], 2),
                    'pelicula_id' => $item['pelicula_id'],
                    'horario_id' => $item['horario_id']
                ];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
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

<h1>Carrito de Compras</h1>

<?php if (!empty($detalles)): ?>
    <table class="tabla-carrito">
        <thead>
            <tr>
                <th>Película</th>
                <th>Horario</th>
                <th>Asiento</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $index => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($item['horario']); ?></td>
                    <td><?php echo htmlspecialchars($item['asiento']); ?></td>
                    <td>$<?php echo $item['precio']; ?></td>
                    <td>
                        <a href="eliminar_del_carrito.php?index=<?php echo $index; ?>" class="boton-cancelar">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <form action="comprar.php" method="POST">
        <button type="submit">Finalizar Compra</button>
    </form>
<?php else: ?>
    <p>Tu carrito está vacío.</p>
<?php endif; ?>

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

    .tabla-carrito {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #202020;
    }

    .tabla-carrito th, .tabla-carrito td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }

    .tabla-carrito th {
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
        .tabla-carrito th, .tabla-carrito td {
            font-size: 14px;
        }
    }

    /* Estilo para el botón de Finalizar Compra */
button[type="submit"] {
    background-color: #4CAF50; /* Verde para un color llamativo */
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px; /* Bordes redondeados */
    transition: background-color 0.3s ease; /* Efecto al pasar el ratón */
    width: 100%; /* Hacer el botón más grande en pantallas pequeñas */
}

/* Cambio de color al pasar el ratón */
button[type="submit"]:hover {
    background-color: #45a049;
}

/* Estilo en pantallas pequeñas */
@media (max-width: 768px) {
    button[type="submit"] {
        width: 100%; /* Asegura que ocupe todo el ancho disponible */
    }
}

</style>

</body>
</html>
