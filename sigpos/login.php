<?php
session_start();
require_once 'config/conexion.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = MD5(trim($_POST['password']));

    $sql = "SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            JOIN roles r ON u.rol_id = r.id
            WHERE u.email = '$email' 
            AND u.password = '$password' 
            AND u.estado = 'activo'";

    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol']    = $usuario['rol_nombre'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiGePOS — Iniciar sesión</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="login-box">
    <h1>SiGePOS</h1>
    <p class="sub">Sistema de Gestión y Punto de Venta</p>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" 
                   placeholder="correo@ejemplo.com" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" 
                   placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">
            Ingresar al sistema
        </button>
    </form>
</div>

</body>
</html>