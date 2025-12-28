<?php
function tienePermiso($modulo, $conn) {
    $rol = $_SESSION['rol_id'];

    $sql = "
        SELECT 1 FROM rol_permisos rp
        JOIN permisos p ON rp.permiso_id = p.id
        WHERE rp.rol_id = $rol
        AND p.modulo = '$modulo'
    ";

    return $conn->query($sql)->num_rows > 0;
}
