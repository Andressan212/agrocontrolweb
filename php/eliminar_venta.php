<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM ventas WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../pages/ventas.php");
exit();
