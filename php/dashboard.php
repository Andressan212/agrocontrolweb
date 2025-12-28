<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
// incluir conexión y validar establecimiento
include "conexion.php";
include "establecimiento_actual.php";


$e = null;
if (isset($_SESSION['establecimiento_id'])) {
    $est_id = (int) $_SESSION['establecimiento_id'];
    $stmt = $conn->prepare("SELECT * FROM establecimientos WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $est_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $e = $res->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Control - AgroControl</title>
    <link rel="stylesheet" href="../css/estilo.css">

    <style>
        body {
            background: #f3f5f7;
            font-family: Arial;
        }

        .topbar {
            width: 100%;
            background: #2d7d46;
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 25px;
            font-weight: bold;
        }

        .container {
            width: 90%;
            margin: auto;
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card a {
            text-decoration: none;
            font-size: 22px;
            font-weight: bold;
            color: #2d7d46;
        }

        .logout {
            margin-top: 40px;
            text-align: center;
        }

        .logout a {
            padding: 10px 20px;
            background: #d62828;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="topbar">
    Panel de Control - AgroControl
</div>

<div class="container">
    <h2>Panel Principal</h2>

    <?php if ($e): ?>
        <h3>🏢 <?= htmlspecialchars($e['nombre']) ?></h3>
    <?php endif; ?>

    <div class="card"><a href="../pages/lotes.php">Lotes</a></div>
    <div class="card"><a href="../pages/cultivos.php">Cultivos</a></div>
    <div class="card"><a href="../pages/tareas.php">Tareas</a></div>
    <div class="card"><a href="../pages/consumo_insumos.php">Consumo Insumos</a></div>
    <div class="card"><a href="../pages/insumos.php">Insumos</a></div>
    <div class="card"><a href="../pages/plagas.php">Plagas</a></div>
    <div class="card"><a href="../pages/maquinaria.php">Maquinaria</a></div>
    <div class="card"><a href="../pages/ventas.php">Ventas</a></div>
    <div class="card"><a href="../pages/gastos.php">Gastos</a></div>
    <div class="card"><a href="../pages/pagos_trabajadores.php">Pagos</a></div>
    <div class="card"><a href="../pages/balance.php">Balance</a></div>
    <div class="card"><a href="../pages/reportes.php">Reportes</a></div>
    <div class="card"><a href="../pages/campanias.php">Campañas</a></div>
    <div class="card"><a href="../pages/graficos.php">Gráficos</a></div>
    <div class="card"><a href="../php/backup.php">Backup</a></div>
    <div class="card"><a href="../logout.php">Salir</a></div>

<!--
    <div class="card"><a href="../pages/lotes.php">Lotes</a></div>
    <div class="card"><a href="../pages/cultivos.php">Cultivos</a></div>
    <div class="card"><a href="../pages/tareas.php">Tareas</a></div>
    <div class="card"><a href="../pages/maquinaria.php">Maquinaria</a></div>
    <div class="card"><a href="../pages/insumos.php">Insumos</a></div>
    <div class="card"><a href="../pages/ventas.php">Ventas</a></div>
    <div class="card"><a href="../pages/trabajadores.php">Trabajadores</a></div>
    <div class="card"><a href="../pages/pagos.php">Pagos</a></div>
    <div class="card"><a href="../pages/plagas.php">Plagas</a></div>
    <div class="card"><a href="../pages/balance.php">Balance</a></div>
    <div class="card"><a href="../pages/uso_insumos.php">uso Balance</a></div>
    <div ><a href="../pages/stock_bajo.php"> <button>⚠️ Stock Bajo</button> </a></div>
<div class="card"><a href="../pages/trabajadores.php">👷 Trabajadores</a></div>
<div class="card"><a href="../pages/pagos_trabajadores.php">💰 Pagos</a></div>
<div class="card"><a href="../pages/balance.php">📊 Balance</a></div>
<div class="card"><a href="../pages/reporte_cultivos.php">📈 Reportes</a></div>
</div>
<div class="logout">  <a href="../logout.php">Cerrar Sesión</a> </div>
<div class="card"><a href="../pages/graficos.php">📊 Gráficos</a></div>
<div class="card"><a href="../pages/exportar_ventas.php">📂 Exportar Ventas</a></div>
<a href="../pages/exportar_offline.php">📥 Exportar para Offline</a>
<a href="../pages/mapa_lotes.php">🗺️ Mapa de Lotes</a>
<a href="../php/backup.php" onclick="return confirm('¿Generar backup?')"></a>
<a href="../pages/consumo_insumos.php">Consumo de Insumos</a>
<a href="../pages/campanias.php">Campañas</a>
<a href="../pages/consumo_insumos.php">Consumo de Insumos</a>
<a href="../pages/reportes.php">Reportes</a>
<a href="../pages/balance.php">Balance General</a>-->
</body>
</html>
