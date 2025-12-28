<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

$lotes = $conn->query("
    SELECT l.*, c.nombre AS cultivo
    FROM lotes l
    LEFT JOIN cultivos c ON c.lote_id = l.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Mapa de Lotes</title>
<link rel="stylesheet" href="../css/estilo.css">

<style>
.mapa {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}
.lote {
    border-radius: 10px;
    padding: 15px;
    color: #fff;
}
.activo { background: #28a745; }
.inactivo { background: #6c757d; }
.problema { background: #dc3545; }
</style>
</head>

<body>

<h2>🗺️ Mapa de Lotes</h2>

<div class="mapa">

<?php while($l=$lotes->fetch_assoc()){ 
    $clase = 'activo';
    if ($l['estado'] === 'inactivo') $clase = 'inactivo';
    if ($l['estado'] === 'problema') $clase = 'problema';
?>

<div class="lote <?= $clase ?>">
    <h3><?= $l['nombre'] ?></h3>
    <p><strong>Ubicación:</strong> <?= $l['ubicacion'] ?></p>
    <p><strong>Hectáreas:</strong> <?= $l['hectareas'] ?></p>
    <p><strong>Estado:</strong> <?= $l['estado'] ?></p>
    <p><strong>Cultivo:</strong> <?= $l['cultivo'] ?? '—' ?></p>
</div>

<?php } ?>

</div>

<br>
<a href="../php/dashboard.php">Volver</a>

</body>
</html>
