<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['cargo'];

    $stmt = $conn->prepare("INSERT INTO trabajadores(nombre, telefono, cargo) VALUES(?,?,?)");
    $stmt->bind_param("sss", $nombre, $telefono, $cargo);
    $stmt->execute();
}

$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY id DESC");
?>

<h2>👷 Trabajadores</h2>

<form method="POST">
    <input name="nombre" placeholder="Nombre" required>
    <input name="telefono" placeholder="Teléfono">
    <input name="cargo" placeholder="Cargo">
    <button name="guardar">Guardar</button>
</form>

<table border="1" width="100%">
<tr><th>Nombre</th><th>Teléfono</th><th>Cargo</th></tr>
<?php while($t=$trabajadores->fetch_assoc()){ ?>
<tr>
<td><?= $t['nombre'] ?></td>
<td><?= $t['telefono'] ?></td>
<td><?= $t['cargo'] ?></td>
</tr>
<?php } ?>
</table>
