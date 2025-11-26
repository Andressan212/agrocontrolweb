<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AgroControl - Panel</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>


<header style="display:flex; align-items:center; padding:10px; background-color:#f0f0f0;">
    <img src="img/logo.png" alt="" style="height:60px; margin-right:15px;">
    <h1>Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
</header>
<body>

<div class="sidebar">
    <h2>AgroControl</h2>
    <a href="dashboard.php">Panel</a>
    <a href="../pages/lotes.php">Lotes</a>
    <a href="../pages/cultivos.php">Cultivos</a>
    <a href="../pages/insumos.php">Insumos</a>
    <a href="../pages/tareas.php">Tareas</a>
    <a href="../pages/maquinaria.php">Maquinaria</a>
    <a href="../pages/clima.php">Clima</a>
    <a href="../pages/plagas.php">Plagas</a>
    <a href="../pages/ventas.php">Ventas</a>
    <a href="../pages/gastos.php">Gastos</a>

    <a href="logout.php">Salir</a>
</div>

<div class="content">
    <h1>Bienvenido al sistema AgroControl</h1>
    <p>Desde aquí puedes gestionar todo tu campo.</p>

    <div class="cards">
        <div class="card">Lotes registrados</div>
        <div class="card">Cultivos activos</div>
        <div class="card">Insumos disponibles</div>
        <div class="card">Ganancia estimada</div>
    </div>
</div>

</body>
</html>
