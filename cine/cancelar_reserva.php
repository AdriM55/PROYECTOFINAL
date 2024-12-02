<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar que se haya enviado el ID de la reserva
if (isset($_POST['reserva_id'])) {
    $reserva_id = $_POST['reserva_id'];

    // Eliminar la reserva de la base de datos
    $sql_cancelar = "DELETE FROM reservas WHERE id = ? AND usuario_id = ?";
    $stmt_cancelar = $conexion->prepare($sql_cancelar);
    $stmt_cancelar->bind_param("ii", $reserva_id, $_SESSION['usuario_id']);
    
    if ($stmt_cancelar->execute()) {
        echo "<script>alert('Reserva cancelada con éxito.'); window.location.href = 'mis_reservas.php';</script>";
    } else {
        echo "<script>alert('Error al cancelar la reserva.'); window.location.href = 'mis_reservas.php';</script>";
    }
} else {
    echo "<script>alert('No se proporcionó un ID de reserva.'); window.location.href = 'mis_reservas.php';</script>";
}
?>
