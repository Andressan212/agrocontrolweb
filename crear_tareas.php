<?php
include("php/conexion.php");

// Eliminar tabla tareas si existe
$conn->query("DROP TABLE IF EXISTS tareas");

// Crear tabla tareas con estructura correcta
$sql = "CREATE TABLE tareas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tarea VARCHAR(100),
    fecha DATE,
    descripcion TEXT,
    id_lote INT
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla tareas creada correctamente";
} else {
    echo "Error al crear tabla: " . $conn->error;
}

$conn->close();
?>
