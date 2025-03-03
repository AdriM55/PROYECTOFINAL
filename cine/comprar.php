<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $query = "INSERT INTO reservas (usuario_id, horario_id, asiento) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iis", $usuario_id, $item['horario_id'], $item['asiento']);

        if (!$stmt->execute()) {
            echo "<script>alert('Error al procesar la compra.'); window.location.href = 'carrito.php';</script>";
            exit;
        }
    }
    
    // Vaciar el carrito después de comprar
    $_SESSION['carrito'] = [];

    echo "<script>alert('Compra realizada con éxito.'); window.location.href = 'mis_reservas.php';</script>";
    exit;
} else {
    echo "<script>alert('El carrito está vacío.'); window.location.href = 'index.php';</script>";
}
?>
