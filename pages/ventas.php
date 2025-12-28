<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar nueva venta
if (isset($_POST['guardar'])) {
    $cultivo_id = $_POST['cultivo_id'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;
    $precio = $_POST['precio'] ?? 0;
    $fecha = $_POST['fecha'] ?? '';

    if (!empty($cultivo_id) && !empty($cantidad) && !empty($fecha)) {
        $stmt = $conn->prepare("
            INSERT INTO ventas(cultivo_id, cantidad, precio, fecha)
            VALUES(?, ?, ?, ?)
        ");
        $stmt->bind_param("idds", $cultivo_id, $cantidad, $precio, $fecha);
        $stmt->execute();
        $stmt->close();
    }
}

// Obtener ventas + nombre del cultivo
$ventas = $conn->query("
    SELECT v.*, c.nombre AS nombre_cultivo
    FROM ventas v
    LEFT JOIN cultivos c ON v.cultivo_id = c.id
    ORDER BY v.id DESC
");

// Cultivos para el select
$cultivos = $conn->query("SELECT id, nombre FROM cultivos ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ventas</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registro de Ventas 💸</h2>

<form method="POST">
    <label>Cultivo:</label>
    <select name="cultivo_id" required>
        <option value="">Seleccione un cultivo</option>
        <?php while ($c = $cultivos->fetch_assoc()) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
        <?php } ?>
    </select>

    <label>Cantidad (KG / Ton):</label>
    <input type="number" step="0.01" name="cantidad" required>

    <label>Precio por unidad:</label>
    <input type="number" step="0.01" name="precio" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Ventas registradas</h3>

<button onclick="window.print()">🖨 Imprimir</button>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Cultivo</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Modificar</th>
    <th>Eliminar</th>
</tr>

<?php while ($v = $ventas->fetch_assoc()) { ?>
<tr>
    <td><?= $v['id'] ?></td>
    <td><?= $v['nombre_cultivo'] ?></td>
    <td><?= $v['cantidad'] ?></td>
    <td><?= $v['precio'] ?></td>
    <td><?= number_format($v['cantidad'] * $v['precio'], 2) ?></td>
    <td><?= $v['fecha'] ?></td>

    <td><a href="modificar_venta.php?id=<?= $v['id'] ?>">✏ Modificar</a></td>

    <td>
        <a href="../php/eliminar_venta.php?id=<?= $v['id'] ?>"
           onclick="return confirm('¿Eliminar esta venta?');">
           ❌ Eliminar
        </a>
    </td>
</tr>
<?php } ?>

</table>

<br>
<a class="estilo" href="../php/dashboard.php">Volver al Panel</a>
<!--include("../php/validar_permiso.php");

if (!tienePermiso("ventas", $conn)) {
    echo "⛔ Acceso denegado";
    exit();
}-->

</body>
</html>
