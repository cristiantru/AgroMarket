<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'comprador') {
    header("Location: login.php");
    exit;
}

require 'db.php';

$stmt = $conn->prepare("SELECT p.id, p.nombre, p.descripcion, p.precio, u.nombre AS vendedor
                        FROM productos p
                        JOIN usuarios u ON p.vendedor_id = u.id");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Productos Disponibles</title>
    <link rel="stylesheet" href="style/style.css" />
</head>
<body>
    <header>
        <h1>Productos en Venta</h1>
        <nav>
            <a href="dashboard_comprador.php">Panel Comprador</a> | 
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section class="productos">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="producto">
                    <h2><?= htmlspecialchars($row['nombre']) ?></h2>
                    <p><?= htmlspecialchars($row['descripcion']) ?></p>
                    <p><strong>Precio:</strong> $<?= number_format($row['precio'], 2) ?> MXN</p>
                    <p><strong>Vendedor:</strong> <?= htmlspecialchars($row['vendedor']) ?></p>
                </div>
            <?php endwhile; ?>
        </section>
    </main>
</body>
</html>
