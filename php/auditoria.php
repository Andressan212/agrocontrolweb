<?php
function registrarAuditoria($conn, $usuario, $accion, $modulo, $descripcion) {
    $stmt = $conn->prepare("
        INSERT INTO auditoria(usuario, accion, modulo, descripcion)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $usuario, $accion, $modulo, $descripcion);
    $stmt->execute();
    $stmt->close();
}
