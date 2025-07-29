<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Traer todos los productos sin filtro de vendedor
$result = $conn->query("SELECT nombre, descripcion, precio FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Productos - Agromarket</title>
    <link rel="stylesheet" href="style/productos.css" />
</head>
<body>
<header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Panel</a></li>
                <li><a href="semillas.php">Semillas</a></li>
                <li><a href="fertilizantes.php">Fertilizantes</a></li>
                <li><a href="herramientas.php">Herramientas</a></li>
                <li><a href="maquinaria.php">Maquinaria</a></li>
                <li><a href="asesoria.php">Asesoría</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-header">Cerrar sesión</a>
    </div>
</header>

<main>
    <h2>Productos disponibles</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="productos-grid">
        <?php while ($prod = $result->fetch_assoc()): ?>
            <div class="producto-card">
                <h3><?= htmlspecialchars($prod['nombre']) ?></h3>
                <p><?= htmlspecialchars($prod['descripcion']) ?></p>
                <p class="precio">$<?= number_format($prod['precio'], 2) ?> MXN</p>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date("Y") ?> Agromarket. Todos los derechos reservados.
</footer>
</body>
</html>
