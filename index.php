<!DOCTYPE html>
<html>
<head>
    <title>AgroControl - Login</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="login-box">
    <h2>AgroControl</h2>
    <form action="php/login.php" method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>
</div>

</body>
</html>
