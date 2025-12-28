<?php
include("../php/conexion.php");

$data = json_decode(file_get_contents("offline_data.json"), true);

// Ejemplo: tareas
foreach ($data['tareas'] as $t) {
    $conn->query("
        INSERT INTO tareas(descripcion, fecha, lote_id, cultivo_id)
        VALUES(
            '{$t['descripcion']}',
            '{$t['fecha']}',
            '{$t['lote_id']}',
            '{$t['cultivo_id']}'
        )
    ");
}

echo "Datos importados";
