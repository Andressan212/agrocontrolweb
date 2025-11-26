<?php 
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Finanzas</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<h2>Gastos e Ingresos 💵</h2>

<form action="" method="POST">
    <input type="text" name="concepto" placeholder="Concepto" required>
    <input type="number" name="monto" placeholder="Monto $" required>
    
    <select name="tipo">
        <option value="Ingreso">Ingreso</option>
        <option value="Gasto">Gasto</option>
    </select>

    <input type="date" name="fecha">
    <button type="submit">Registrar</button>
</form>

<?php
if(isset($_POST['concepto'])){
    $concepto = $conn->real_escape_string($_POST['concepto']);
    $monto = $conn->real_escape_string($_POST['monto']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $fecha = $conn->real_escape_string($_POST['fecha']);

    $sql = "INSERT INTO finanzas (concepto, monto, tipo, fecha) VALUES ('$concepto', '$monto', '$tipo', CURDATE())";
    $conn->query($sql);
}
?>

<table>
<tr>
    <th>Concepto</th>
    <th>Monto</th>
    <th>Tipo</th>
    <th>Fecha</th>
</tr>

<?php
$consulta = $conn->query("SELECT * FROM finanzas ORDER BY fecha DESC");
while($f = $consulta->fetch_assoc()){
?>
<tr>
    <td><?php echo htmlspecialchars($f['concepto']); ?></td>
    <td>$<?php echo htmlspecialchars($f['monto']); ?></td>
    <td><?php echo htmlspecialchars($f['tipo']); ?></td>
    <td><?php echo htmlspecialchars($f['fecha']); ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
