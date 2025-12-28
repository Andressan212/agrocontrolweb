<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    echo "⛔ No tenés permisos para acceder.";
    exit();
}
