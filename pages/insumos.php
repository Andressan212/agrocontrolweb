<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Agregar insumo
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $precio = $_POST['precio'] ?? 0;

    if (!empty($nombre) && !empty($tipo) && is_numeric($stock) && is_numeric($precio)) {
        $stmt = $conn->prepare("INSERT INTO insumos(nombre, tipo, stock, precio) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $nombre, $tipo, $stock, $precio);
        $stmt->execute();
        $stmt->close();
    }
}

// Eliminar insumo
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $conn->query("DELETE FROM insumos WHERE id = $idEliminar");
    header("Location: insumos.php");
    exit();
}

// Obtener insumos
$insumos = $conn->query("SELECT * FROM insumos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insumos - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        table { width:100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border:1px solid #ccc; padding:8px; text-align:left;}
        th { background:#222; color:#fff; }
        a.boton { padding:5px 10px; text-decoration:none; border-radius:4px; color:white; margin-right:5px;}
        .eliminar { background:#d9534f;}
        .modificar { background:#f0ad4e;}
        .imprimir { background:#007bff;}
        button.imprimirTabla { margin-top:10px; padding:8px 15px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer;}
        button.imprimirTabla:hover { opacity:0.8; }
        a.boton:hover { opacity:0.8; }
    </style>
    <script>
        function imprimirTabla() {
            var contenido = document.getElementById('tablaInsumos').outerHTML;
            var win = window.open('', '', 'height=600,width=800');
            win.document.write('<html><head><title>Insumos</title></head><body>');
            win.document.write('<h2>Lista de Insumos</h2>');
            win.document.write(contenido);
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        }
    </script>
</head>
<body>

<h2>Gestión de Insumos 🧰</h2>

<form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Tipo:</label>
    <input type="text" name="tipo" required>

    <label>Stock:</label>
    <input type="number" step="0.01" name="stock" required>

    <label>Precio:</label>
    <input type="number" step="0.01" name="precio" required>

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Insumos registrados</h3>

<button class="imprimirTabla" onclick="imprimirTabla()">Imprimir Toda la Tabla</button>

<table id="tablaInsumos">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Stock</th>
    <th>Precio</th>
    <th></th>
</tr>

<?php if($insumos && $insumos->num_rows>0): ?>
    <?php while($i = $insumos->fetch_assoc()): ?>
<tr>
    <td><?= $i['id']; ?></td>
    <td><?= htmlspecialchars($i['nombre']); ?></td>
    <td><?= htmlspecialchars($i['tipo']); ?></td>
    <td><?= number_format($i['stock'],2); ?></td>
    <td>$<?= number_format($i['precio'],2); ?></td>
    <td>
        <a class="boton modificar" href="modificar_insumo.php?id=<?= $i['id']; ?>">Modificar</a>
        <a class="boton eliminar" href="?eliminar=<?= $i['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este insumo?');">Eliminar</a>
    </td>
</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6">No hay insumos registrados</td></tr>
<?php endif; ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
