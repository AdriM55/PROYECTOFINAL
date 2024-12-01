<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["success" => false, "error" => "Debe estar logeado para reservar."]);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "cine_db");
$data = json_decode(file_get_contents("php://input"), true);
$usuario_id = $_SESSION['usuario_id'];
$horario_id = $data['horario_id'];
$asientos = $data['asientos'];

foreach ($asientos as $asiento) {
    $sql = "INSERT INTO reservas (usuario_id, horario_id, asiento) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iis", $usuario_id, $horario_id, $asiento);
    $stmt->execute();
}

echo json_encode(["success" => true]);
?>
