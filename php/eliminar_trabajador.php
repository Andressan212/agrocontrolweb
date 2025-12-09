<?php
include("conexion.php");
session_start();

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM trabajadores WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../pages/trabajadores.php");
exit();
