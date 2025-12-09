<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar trabajador nuevo
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['cargo'];
    $sueldo = $_POST['sueldo'];

    if (!empty($nombre) && !empty($cargo)) {
        $stmt = $conn->prepare("
            INSERT INTO trabajadores(nombre, telefono, cargo, sueldo)
            VALUES(?, ?, ?, ?)
        ");
        $stmt->bind_param("sssd", $nombre, $telefono, $cargo, $sueldo);
        $stmt->execute();
        $stmt->close();
    }
}

$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trabajadores</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Trabajadores 👷</h2>

<form method="POST">

    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono">

    <label>Cargo:</label>
    <input type="text" name="cargo" required>

    <label>Sueldo (mensual):</label>
    <input type="number" step="0.01" name="sueldo">

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Registro de Trabajadores</h3>

<button onclick="window.print()">🖨 Imprimir</button>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Cargo</th>
    <th>Sueldo</th>
    <th>Modificar</th>
    <th>Eliminar</th>
</tr>

<?php while ($t = $trabajadores->fetch_assoc()) { ?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= $t['nombre'] ?></td>
    <td><?= $t['telefono'] ?></td>
    <td><?= $t['cargo'] ?></td>
    <td><?= $t['sueldo'] ?></td>

    <td><a href="modificar_trabajador.php?id=<?= $t['id'] ?>">✏</a></td>

    <td>
        <a href="../php/eliminar_trabajador.php?id=<?= $t['id'] ?>"
           onclick="return confirm('¿Eliminar trabajador?');">
           ❌
        </a>
    </td>
</tr>
<?php } ?>

</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
