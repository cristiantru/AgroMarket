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
</head>
<body>

<header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="mis_productos.php" class="activo">Mis Productos</a></li>
                <li><a href="agregar_producto.php">Agregar Producto</a></li>
                <li><a href="ventas.php">Ventas</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <h2>Mis Productos</h2>
    <a href="agregar_producto.php" class="btn">Agregar Producto</a>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio ($)</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td><?= number_format($row['precio'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="no-products">No tienes productos agregados aún.</p>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date("Y") ?> Agromarket. Todos los derechos reservados.
</footer>

</body>
</html>
