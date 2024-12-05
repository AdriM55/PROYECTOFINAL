<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    echo "Debe iniciar sesión para realizar una reserva.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pelicula_id = $_POST['pelicula_id'];
$horario_id = $_POST['horario_id'];
$asientos = explode(',', $_POST['asientos']);

if (empty($horario_id) || empty($asientos)) {
    echo "Error: No se seleccionaron asientos o el horario no es válido.";
    exit;
}

foreach ($asientos as $asiento) {
    $query = "INSERT INTO reservas (usuario_id, horario_id, asiento) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $usuario_id, $horario_id, $asiento);

    if (!$stmt->execute()) {
        echo "Error al reservar el asiento $asiento.";
        exit;
    }
}

echo "<script>
    alert('Reserva completada. Redirigiendo a la página de reservas...');
    window.location.href = 'mis_reservas.php';
</script>";
exit;
?>
