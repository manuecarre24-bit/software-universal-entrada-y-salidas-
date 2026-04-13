<?php
session_start();
require_once '../../config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

$sql = "SELECT p.*, c.nombre as categoria, u.abreviatura as unidad
        FROM productos p
        JOIN categorias c ON p.categoria_id = c.id
        JOIN unidades_medida u ON p.unidad_medida_id = u.id
        WHERE p.estado = 'activo'
        ORDER BY p.nombre ASC";
$productos = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SiGePOS — Productos</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/tabla.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">SiGePOS</div>
    <nav>
        <a href="../../index.php">🏠 Inicio</a>
        <a href="modules/productos/index.php" class="activo">📦 Productos</a>
        <a href="modules/ventas/index.php">🛒 Ventas</a>
        <a href="modules/clientes/index.php">👥 Clientes</a>
        <a href="modules/proveedores/index.php">🏭 Proveedores</a>
        <a href="modules/reportes/index.php">📊 Reportes</a>
        <a href="../../logout.php">🚪 Salir</a>
    </nav>
</div>

<div class="main">
    <div class="topbar">
        <h2>📦 Productos</h2>
        <span class="rol"><?= $_SESSION['usuario_rol'] ?></span>
    </div>

    <div class="tabla-container">
        <div class="tabla-header">
            <h3>Lista de productos</h3>
            <a href="crear.php" class="btn-nuevo">+ Nuevo producto</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio compra</th>
                    <th>Precio venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($p = mysqli_fetch_assoc($productos)): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><code><?= $p['codigo_barras'] ?></code></td>
                    <td><?= $p['nombre'] ?></td>
                    <td><?= $p['categoria'] ?></td>
                    <td>$<?= number_format($p['precio_compra'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($p['precio_venta'], 0, ',', '.') ?></td>
                    <td><?= $p['stock_actual'] ?> <?= $p['unidad'] ?></td>
                    <td>
                        <?php if($p['stock_actual'] <= $p['stock_minimo']): ?>
                            <span class="badge rojo">⚠️ Stock bajo</span>
                        <?php else: ?>
                            <span class="badge verde">✅ Normal</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editar.php?id=<?= $p['id'] ?>" class="btn-editar">✏️ Editar</a>
                        <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn-eliminar"
                            onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>