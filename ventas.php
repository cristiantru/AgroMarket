<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit;
}

require 'db.php';
$vendedor_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("
    SELECT v.id, p.nombre AS producto, v.cantidad, v.total, v.fecha
    FROM ventas v
    JOIN productos p ON v.producto_id = p.id
    WHERE p.vendedor_id = ?
    ORDER BY v.fecha DESC
");
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ventas - Vendedor</title>
    <link rel="stylesheet" href="style/venta.css" />
</head>
<body>
    <header>
        <div class="dashboard-header">
            <h1>Panel del Vendedor</h1>
            <nav>
                <a href="dashboard.php">Panel</a>
                <a href="mis_productos.php">Mis Productos</a>
                <a href="agregar_producto.php">Agregar Producto</a>
                <a href="ventas.php" class="activo">Ventas</a>
                <a href="logout.php">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main class="dashboard-content">
        <h2>Historial de Ventas</h2>
        <table class="ventas-tabla">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['producto']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= $row['fecha'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
