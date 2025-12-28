<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
include "../php/conexion.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: tareas.php?msg=error");
    exit();
}

$sql = "DELETE FROM tareas WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: tareas.php?msg=eliminado");
    exit;
} else {
    echo "Error al eliminar la tarea.";
}
