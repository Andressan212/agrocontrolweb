include("../php/auditoria.php");

registrarAuditoria(
    $conn,
    $_SESSION['usuario'],
    'ELIMINAR',
    'TAREAS',
    "ID tarea eliminada: $id"
);
