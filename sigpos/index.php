<?php
session_start();
require_once 'config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Contar datos reales desde MySQL
$total_productos = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos WHERE estado='activo'")
)['total'];

$total_clientes = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) as total FROM clientes WHERE estado='activo'")
)['total'];

$ventas_hoy = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) as total FROM ventas WHERE DATE(fecha) = CURDATE()")
)['total'];

$stock_bajo = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos WHERE stock_actual <= stock_minimo AND estado='activo'")
)['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SiGePOS — Inicio</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">SiGePOS</div>
    <nav>
        <a href="index.php">🏠 Inicio</a>
        <a href="modules/productos/index.php">📦 Productos</a>
        <a href="#">🛒 Ventas</a>
        <a href="#">👥 Clientes</a>
        <a href="#">🏭 Proveedores</a>
        <a href="#">📊 Reportes</a>
        <a href="logout.php">🚪 Salir</a>
    </nav>
</div>

<div class="main">
    <div class="topbar">
        <h2>Bienvenido, <?= $_SESSION['usuario_nombre'] ?></h2>
        <span class="rol"><?= $_SESSION['usuario_rol'] ?></span>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Productos</h3>
            <p class="numero"><?= $total_productos ?></p>
            <p class="sub">en inventario</p>
        </div>
        <div class="card">
            <h3>Clientes</h3>
            <p class="numero"><?= $total_clientes ?></p>
            <p class="sub">registrados</p>
        </div>
        <div class="card">
            <h3>Ventas hoy</h3>
            <p class="numero"><?= $ventas_hoy ?></p>
            <p class="sub">transacciones</p>
        </div>
        <div class="card <?= $stock_bajo > 0 ? 'card-alerta' : '' ?>">
            <h3>Stock bajo</h3>
            <p class="numero"><?= $stock_bajo ?></p>
            <p class="sub">productos por agotar</p>
        </div>
    </div>
</div>

</body>
</html>