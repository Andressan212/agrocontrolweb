<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$insumos = $conn->query("SELECT * FROM insumos ORDER BY nombre ASC");

if (isset($_POST['guardar'])) {
    $insumo_id = $_POST['insumo_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];

    $stock = $conn->query(
        "SELECT stock FROM insumos WHERE id = $insumo_id"
    )->fetch_assoc()['stock'];

    if ($stock >= $cantidad) {
        // Registrar uso
        $conn->query(
            "INSERT INTO uso_insumos (insumo_id, cantidad, fecha)
             VALUES ($insumo_id, $cantidad, '$fecha')"
        );

        // Descontar stock
        $conn->query(
            "UPDATE insumos SET stock = stock - $cantidad WHERE id = $insumo_id"
        );
    }
}
?>

<h2>Uso de Insumos</h2>

<form method="POST">
    <label>Insumo:</label>
    <select name="insumo_id" required>
        <?php while ($i = $insumos->fetch_assoc()) { ?>
            <option value="<?php echo $i['id']; ?>">
                <?php echo $i['nombre']; ?> (Stock: <?php echo $i['stock']; ?>)
            </option>
        <?php } ?>
    </select>

    <label>Cantidad usada:</label>
    <input type="number" step="0.01" name="cantidad" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <button type="submit" name="guardar">Registrar Uso</button>
</form>
