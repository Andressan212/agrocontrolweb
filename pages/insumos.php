<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Insertar nuevo insumo
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $precio = $_POST['precio'] ?? 0;

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO insumos(nombre, tipo, stock, precio) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $nombre, $tipo, $stock, $precio);
        $stmt->execute();
        $stmt->close();
    }
}

$consulta = $conn->query("SELECT * FROM insumos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insumos</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Insumos 📦</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre del insumo" required>
    <input type="text" name="tipo" placeholder="Tipo (fertilizante, químico, etc)">
    <input type="number" step="0.01" name="stock" placeholder="Stock disponible">
    <input type="number" step="0.01" name="precio" placeholder="Precio por unidad">
    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Lista de Insumos</h3>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Stock</th>
    <th>Precio</th>
    <th>Modificar</th>
    <th>Eliminar</th>
</tr>

<?php while ($i = $consulta->fetch_assoc()) { ?>
<tr>
    <td><?= $i['id'] ?></td>
    <td><?= $i['nombre'] ?></td>
    <td><?= $i['tipo'] ?></td>
    <td><?= $i['stock'] ?></td>
    <td><?= $i['precio'] ?></td>

    <td>
        <a href="modificar_insumo.php?id=<?= $i['id'] ?>">✏ Modificar</a>
    </td>

    <td>
        <a href="../php/eliminar_insumo.php?id=<?= $i['id'] ?>" 
           onclick="return confirm('¿Eliminar este insumo?');">
           ❌ Eliminar
        </a>
    </td>
    <td>
<?php if ($i['stock'] <= $i['stock_minimo']) { ?>
    <span style="color:red;font-weight:bold;">⚠ Stock Bajo</span>
<?php } else { ?>
    <span style="color:green;">OK</span>
<?php } ?>
</td>

</tr>
<?php } ?>

</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
