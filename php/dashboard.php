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

    <div class="card"><a href="../pages/lotes.php">Lotes</a></div>

    <div class="card"><a href="../pages/cultivos.php">Cultivos</a></div>

    <div class="card"><a href="../pages/tareas.php">Tareas</a></div>

    <div class="card"><a href="../pages/maquinaria.php">Maquinaria</a></div>

    <div class="card"><a href="../pages/insumos.php">Insumos</a></div>

    <div class="card"><a href="../pages/ventas.php">Ventas</a></div>

    <div class="card"><a href="../pages/trabajadores.php">Trabajadores</a></div>

    <div class="card"><a href="../pages/pagos.php">Pagos</a></div>

    <div class="card"><a href="../pages/plagas.php">Plagas</a></div>

</div>

<div class="logout">
    <a href="../php/cerrar_sesion.php">Cerrar Sesión</a>
</div>

</body>
</html>
