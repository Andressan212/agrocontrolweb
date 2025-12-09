<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// AGREGAR TRABAJADOR
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['cargo'];

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO trabajadores(nombre, telefono, cargo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $telefono, $cargo);
        $stmt->execute();
        $stmt->close();
    }
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM trabajadores WHERE id = $id");
    header("Location: trabajadores.php");
    exit();
}

$lista = $conn->query("SELECT * FROM trabajadores ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Trabajadores</title>
<link rel="stylesheet" href="../css/estilo.css">

<script>
function imprimirTabla() {
    let tabla = document.getElementById("tablaTrabajadores").outerHTML;
    let w = window.open('', '', 'width=800,height=600');
    w.document.write("<h2>Listado de Trabajadores</h2>");
    w.document.write(tabla);
    w.print();
    w.close();
}
</script>

</head>
<body>

<h2>Gestión de Trabajadores 👷‍♂️</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="telefono" placeholder="Teléfono">
    <input type="text" name="cargo" placeholder="Cargo (ej: tractorista, peón)">
    <button type="submit" name="guardar">Guardar</button>
</form>

<button onclick="imprimirTabla()">Imprimir Tabla</button>

<table id="tablaTrabajadores" border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Cargo</th>
    <th></th>
</tr>

<?php while ($t = $lista->fetch_assoc()) { ?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= $t['nombre'] ?></td>
    <td><?= $t['telefono'] ?></td>
    <td><?= $t['cargo'] ?></td>
    <td>
        <a href="mod_trabajador.php?id=<?= $t['id'] ?>">Modificar</a> | 
        <a href="trabajadores.php?eliminar=<?= $t['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
    </td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>
</body>
</html>
