<?php
session_start();
require_once '../../config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

$mensaje = '';
$error   = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo       = trim($_POST['codigo_barras']);
    $nombre       = trim($_POST['nombre']);
    $descripcion  = trim($_POST['descripcion']);
    $categoria    = $_POST['categoria_id'];
    $unidad       = $_POST['unidad_medida_id'];
    $proveedor    = $_POST['proveedor_id'];
    $precio_c     = $_POST['precio_compra'];
    $precio_v     = $_POST['precio_venta'];
    $stock        = $_POST['stock_actual'];
    $stock_min    = $_POST['stock_minimo'];

    $sql = "INSERT INTO productos 
            (codigo_barras, nombre, descripcion, categoria_id, 
             unidad_medida_id, proveedor_id, precio_compra, 
             precio_venta, stock_actual, stock_minimo)
            VALUES 
            ('$codigo','$nombre','$descripcion','$categoria',
             '$unidad','$proveedor','$precio_c',
             '$precio_v','$stock','$stock_min')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = '✅ Producto creado exitosamente';
    } else {
        $error = '❌ Error: ' . mysqli_error($conexion);
    }
}

// Cargar categorias, unidades y proveedores
$categorias  = mysqli_query($conexion, "SELECT * FROM categorias WHERE estado='activo'");
$unidades    = mysqli_query($conexion, "SELECT * FROM unidades_medida");
$proveedores = mysqli_query($conexion, "SELECT * FROM proveedores WHERE estado='activo'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SiGePOS — Nuevo producto</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/tabla.css">
    <link rel="stylesheet" href="../../assets/css/form.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">SiGePOS</div>
    <nav>
        <a href="../../index.php">🏠 Inicio</a>
        <a href="index.php" class="activo">📦 Productos</a>
        <a href="../../modules/ventas/index.php">🛒 Ventas</a>
        <a href="../../modules/clientes/index.php">👥 Clientes</a>
        <a href="../../modules/proveedores/index.php">🏭 Proveedores</a>
        <a href="../../modules/reportes/index.php">📊 Reportes</a>
        <a href="../../logout.php">🚪 Salir</a>
    </nav>
</div>

<div class="main">
    <div class="topbar">
        <h2>➕ Nuevo producto</h2>
        <span class="rol"><?= $_SESSION['usuario_rol'] ?></span>
    </div>

    <?php if ($mensaje): ?>
        <div class="alerta verde"><?= $mensaje ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alerta rojo"><?= $error ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">

            <div class="form-grid">
                <div class="form-group">
                    <label>Código de barras</label>
                    <input type="text" name="codigo_barras" 
                           placeholder="Escanea o escribe el código" 
                           autofocus>
                </div>
                <div class="form-group">
                    <label>Nombre del producto *</label>
                    <input type="text" name="nombre" 
                           placeholder="Ej: Arroz Diana 1kg" required>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" 
                          placeholder="Descripción opcional del producto"
                          rows="3"></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Categoría *</label>
                    <select name="categoria_id" required>
                        <option value="">Seleccione...</option>
                        <?php while($c = mysqli_fetch_assoc($categorias)): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= $c['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unidad de medida *</label>
                    <select name="unidad_medida_id" required>
                        <option value="">Seleccione...</option>
                        <?php while($u = mysqli_fetch_assoc($unidades)): ?>
                            <option value="<?= $u['id'] ?>">
                                <?= $u['nombre'] ?> (<?= $u['abreviatura'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Proveedor</label>
                    <select name="proveedor_id">
                        <option value="">Sin proveedor</option>
                        <?php while($p = mysqli_fetch_assoc($proveedores)): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= $p['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Precio de compra *</label>
                    <input type="number" name="precio_compra" 
                           placeholder="0" min="0" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Precio de venta *</label>
                    <input type="number" name="precio_venta" 
                            placeholder="0" min="0" required>
                </div>
                <div class="form-group">
                    <label>Stock actual *</label>
                    <input type="number" name="stock_actual" 
                            placeholder="0" min="0" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Stock mínimo *</label>
                    <input type="number" name="stock_minimo" 
                            placeholder="5" min="0" required>
                </div>
            </div>

            <div class="form-acciones">
                <a href="index.php" class="btn-cancelar">← Cancelar</a>
                <button type="submit" class="btn-guardar">
                    💾 Guardar producto
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>