<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar trabajador
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['cargo'];

    if ($nombre != "") {
        $stmt = $conn->prepare("INSERT INTO trabajadores(nombre, telefono, cargo) VALUES(?,?,?)");
        $stmt->bind_param("sss", $nombre, $telefono, $cargo);
        $stmt->execute();
        $stmt->close();
    }
}

// Listado
$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trabajadores</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Trabajadores 👷</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="telefono" placeholder="Teléfono">
    <input type="text" name="cargo" placeholder="Cargo / Función">
    <button name="guardar">Guardar</button>
</form>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Cargo</th>
</tr>

<?php while($t = $trabajadores->fetch_assoc()){ ?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= $t['nombre'] ?></td>
    <td><?= $t['telefono'] ?></td>
    <td><?= $t['cargo'] ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>
</body>
</html>
