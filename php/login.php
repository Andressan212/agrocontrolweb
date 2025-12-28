<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    // Buscar usuario por nombre y verificar contraseña hasheada
    $query = "SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $stored = $row['password'];
        $ok = false;
       
        // Si la contraseña almacenada parece un hash (empieza con $2y$ o $2a$), usar password_verify
        if (is_string($stored) && (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon2') === 0)) {
            $ok = password_verify($password, $stored);
        } else {
            // Retrocompatibilidad: comparar con texto plano
            if ($password === $stored) {
                $ok = true;
                // Re-hashear y actualizar en la base de datos para mayor seguridad
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                if ($up) {
                    $up->bind_param("si", $newHash, $row['id']);
                    $up->execute();
                    $up->close();
                }
            }
        }

        if ($ok) {
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['usuario_id'] = $row['id'];
            // Normalizar rol: mapear posibles valores a 'admin' o 'usuario'
            $rawRole = strtolower(trim($row['rol'] ?? ''));
            if (in_array($rawRole, ['admin', 'administrador', 'administrator'])) {
                $_SESSION['rol'] = 'admin';
            } else {
                $_SESSION['rol'] = 'usuario';
            }

            // Si solo existe un establecimiento en la base, asignarlo automáticamente
            $resEst = $conn->query("SELECT id FROM establecimientos");
            if ($resEst && $resEst->num_rows === 1) {
                $estRow = $resEst->fetch_assoc();
                $_SESSION['establecimiento_id'] = $estRow['id'];
                header("Location: dashboard.php");
                exit();
            }

            // Si hay varios establecimientos, dejar que el flujo actual pida seleccionar
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
    $stmt->close();
}

if (isset($error)) echo htmlspecialchars($error);
?>
