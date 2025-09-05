<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit;
}

$vendedor_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE vendedor_id = ?");
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Productos - Agromarket</title>
    <link rel="stylesheet" href="style/footer.css" />
    <link rel="stylesheet" href="style/vendedor.css" />
    <link rel="stylesheet" href="style/productos.css">
</head>
<body>

<header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Panel</a></li>
                <li><a href="mis_productos.php" class="activo">Mis Productos</a></li>
                <li><a href="agregar_producto.php">Agregar Producto</a></li>
                <li><a href="ventas.php">Ventas</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-header">Cerrar sesión</a>
    </div>
</header>


<main class="contenedor">
    
    <?php if ($result->num_rows > 0): ?>
        <div class="cards-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                <p><?= htmlspecialchars($row['descripcion']) ?></p>
                <p class="price">$<?= number_format($row['precio'], 2) ?></p>
                <div class="card-buttons">
                    <a href="editar_producto.php?id=<?= $row['id'] ?>" class="btn-edit">Editar</a>
                    <a href="eliminar_producto.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-products">No tienes productos agregados aún.</p>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date("Y") ?> Agromarket. Todos los derechos reservados.
</footer>

</body>
</html>
