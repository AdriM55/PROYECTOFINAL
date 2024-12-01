<?php
$conexion = new mysqli("localhost", "root", "", "cine_db");
$titulo = $_GET['titulo'];

$sql = "SELECT horarios.id, horarios.horario FROM horarios 
        JOIN peliculas ON horarios.pelicula_id = peliculas.id
        WHERE peliculas.titulo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $titulo);
$stmt->execute();
$result = $stmt->get_result();

$horarios = [];
while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}
echo json_encode($horarios);
?>
