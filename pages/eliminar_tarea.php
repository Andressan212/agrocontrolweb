<?php
include "conexion.php";

$id = intval($_GET['id']);

$sql = "DELETE FROM tareas WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: listar_tareas.php?msg=eliminado");
    exit;
} else {
    echo "Error al eliminar la tarea.";
}
