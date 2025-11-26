<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Usar prepared statements para evitar SQL injection
    $query = "SELECT * FROM usuarios WHERE usuario = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos";
    }
    $stmt->close();
}
?>
