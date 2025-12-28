<?php
if (!isset($_SESSION['establecimiento_id'])) {
    header("Location: ../pages/seleccionar_establecimiento.php");
    exit();
}
