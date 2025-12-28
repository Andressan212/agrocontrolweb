<?php
include("../php/conexion.php");

$ventas = $conn->query("
    SELECT DATE(fecha) dia, SUM(cantidad*precio) total
    FROM ventas
    GROUP BY dia
")->fetch_all(MYSQLI_ASSOC);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h2>Gráfico de Ventas 📊</h2>

<canvas id="ventas"></canvas>

<script>
const data = {
    labels: <?= json_encode(array_column($ventas,'dia')) ?>,
    datasets: [{
        label: 'Ventas',
        data: <?= json_encode(array_column($ventas,'total')) ?>,
        borderWidth: 2
    }]
};

new Chart(document.getElementById('ventas'), {
    type: 'line',
    data: data
});
</script>

