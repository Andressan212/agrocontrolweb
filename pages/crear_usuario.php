<?php
session_start();
include("../php/conexion.php");

if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

if(isset($_POST['crear'])){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $conn->real_escape_string($_POST['rol']);

    $sql = "INSERT INTO usuarios (nombre, usuario, password, rol) VALUES ('$nombre','$usuario','$password','$rol')";
    $conn->query($sql);
    $mensaje = "Usuario creado correctamente";
}
?>

<h2>Crear Nuevo Usuario</h2>

<form method="POST">
    <label>Nombre:</label><input type="text" name="nombre" required><br>
    <label>Usuario:</label><input type="text" name="usuario" required><br>
    <label>Contraseña:</label><input type="password" name="password" required><br>
    <label>Rol:</label>
    <select name="rol">
        <option value="usuario">Usuario</option>
        <option value="admin">Administrador</option>
    </select><br>
    <button type="submit" name="crear">Crear Usuario</button>
</form>

<?php if(isset($mensaje)) echo htmlspecialchars($mensaje); ?>

<a href="../php/dashboard.php">Volver al Panel</a>
