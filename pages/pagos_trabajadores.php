<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['guardar'])) {
    $trabajador = $_POST['trabajador_id'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];

    $stmt = $conn->prepare("
        INSERT INTO pagos_trabajadores(trabajador_id, monto, fecha)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("ids", $trabajador, $monto, $fecha);
    $stmt->execute();
}

$trabajadores = $conn->query("SELECT * FROM trabajadores");
$pagos = $conn->query("
    SELECT p.fecha, t.nombre, t.cargo, p.monto
    FROM pagos_trabajadores p
    JOIN trabajadores t ON p.trabajador_id = t.id
    ORDER BY p.fecha DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    

<h2>Pagos a Trabajadores 👷</h2>

<form method="POST">
    <select name="trabajador_id" required>
        <?php while($t = $trabajadores->fetch_assoc()){ ?>
            <option value="<?= $t['id'] ?>">
                <?= $t['nombre'] ?> (<?= $t['cargo'] ?>)
            </option>
        <?php } ?>
    </select>

    <input type="number" step="0.01" name="monto" placeholder="Monto" required>
    <input type="date" name="fecha" required>
    <button name="guardar">Pagar</button>
</form>

<table border="1">
<tr><th>Fecha</th><th>Nombre</th><th>Cargo</th><th>Monto</th></tr>
<?php while($p = $pagos->fetch_assoc()){ ?>
<tr>
    <td><?= $p['fecha'] ?></td>
    <td><?= $p['nombre'] ?></td>
    <td><?= $p['cargo'] ?></td>
    <td>$<?= $p['monto'] ?></td>
</tr>
<?php } ?>
</table>
</body>
</html>