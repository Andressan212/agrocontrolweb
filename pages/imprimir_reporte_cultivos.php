<?php
include("../php/conexion.php");

$r = $conn->query("
    SELECT c.nombre,
           SUM(v.cantidad * v.precio) AS total
    FROM ventas v
    JOIN cultivos c ON v.cultivo_id = c.id
    GROUP BY c.id
");
?>

<h2>Reporte por Cultivo</h2>
<hr>

<?php while($f=$r->fetch_assoc()){ ?>
<p><strong><?= $f['nombre'] ?>:</strong> $<?= number_format($f['total'],2) ?></p>
<?php } ?>

<script>window.print();</script>
