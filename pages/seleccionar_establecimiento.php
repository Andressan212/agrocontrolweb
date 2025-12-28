<?php
include("../php/conexion.php");
session_start();

$ests = $conn->query("SELECT * FROM establecimientos");
?>

<h2>Seleccionar Establecimiento</h2>

<form method="POST">
<select name="establecimiento_id" required>
<?php while($e=$ests->fetch_assoc()){ ?>
<option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
<?php } ?>
</select>
<button name="ingresar">Ingresar</button>
</form>

<?php
if(isset($_POST['ingresar'])){
    $_SESSION['establecimiento_id'] = $_POST['establecimiento_id'];
    header("Location: ../php/dashboard.php");
}
?>
