<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Datos para gráficos
$lotes = $conn->query("SELECT COUNT(*) AS total FROM lotes")->fetch_assoc()['total'];
$cultivos = $conn->query("SELECT COUNT(*) AS total FROM cultivos")->fetch_assoc()['total'];
$insumos = $conn->query("SELECT COUNT(*) AS total FROM insumos")->fetch_assoc()['total'];
$tareas = $conn->query("SELECT COUNT(*) AS total FROM tareas")->fetch_assoc()['total'];

// Datos para cultivos por lote
$datos_cultivos_lote = $conn->query("
    SELECT l.nombre, COUNT(c.id) AS total 
    FROM lotes l 
    LEFT JOIN cultivos c ON l.id = c.id_lote 
    GROUP BY l.id
");
$lotes_labels = [];
$cultivos_data = [];
if ($datos_cultivos_lote) {
    while($row = $datos_cultivos_lote->fetch_assoc()){
        $lotes_labels[] = htmlspecialchars($row['nombre']);
        $cultivos_data[] = $row['total'];
    }
}
?>
?>

<!DOCTYPE html>
<html>
<head>
<title>Reportes - AgroSystem</title>
<link rel="stylesheet" href="../css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<h2>Reportes Generales 📊</h2>

<p>Total de lotes: <?php echo $lotes; ?></p>
<p>Total de cultivos: <?php echo $cultivos; ?></p>
<p>Total de insumos: <?php echo $insumos; ?></p>
<p>Total de tareas: <?php echo $tareas; ?></p>

<h3>Cultivos por Lote</h3>
<canvas id="cultivosLoteChart" width="400" height="200"></canvas>

<h3>Insumos por Tipo</h3>
<canvas id="insumosChart" width="400" height="200"></canvas>

<script>
// Cultivos por lote
var ctx = document.getElementById('cultivosLoteChart').getContext('2d');
var cultivosLoteChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($lotes_labels); ?>,
        datasets: [{
            label: 'Cantidad de cultivos',
            data: <?php echo json_encode($cultivos_data); ?>,
            backgroundColor: 'rgba(46, 125, 50, 0.7)',
            borderColor: 'rgba(46, 125, 50, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero:true
            }
        }
    }
});

// Insumos por tipo
var insumosData = <?php
$result = $conn->query("SELECT tipo, COUNT(*) AS total FROM insumos GROUP BY tipo");
$data_array = [];
if ($result) {
    while($r = $result->fetch_assoc()){
        $data_array[] = $r;
    }
}
echo json_encode($data_array);
?>;

var tipos = insumosData.map(i => i.tipo);
var cantidad = insumosData.map(i => i.total);

var ctx2 = document.getElementById('insumosChart').getContext('2d');
var insumosChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: tipos,
        datasets: [{
            data: cantidad,
            backgroundColor: [
                'rgba(46, 125, 50, 0.7)',
                'rgba(102, 187, 106,0.7)',
                'rgba(165, 214, 167,0.7)',
                'rgba(76, 175, 80,0.7)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
