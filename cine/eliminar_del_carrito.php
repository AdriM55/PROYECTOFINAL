<?php
session_start();
if (isset($_GET['index'])) {
    array_splice($_SESSION['carrito'], $_GET['index'], 1);
}
header("Location: carrito.php");
exit();
?>
