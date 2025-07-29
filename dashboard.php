<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener rol de sesión
$rol = $_SESSION['rol'] ?? 'comprador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Usuario - Agromarket</title>
    <link rel="stylesheet" href="style/footer.css" />
    <link rel="stylesheet" href="style/dashboard.css" />
</head>
<body>
<header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                <?php if ($rol === 'vendedor'): ?>
                    <li><a href="mis_productos.php">Mis Productos</a></li>
                    <li><a href="agregar_producto.php">Agregar Producto</a></li>
                    <li><a href="ventas.php">Ventas</a></li>
                <?php else: ?>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="semillas.php">Semillas</a></li>
                    <li><a href="fertilizantes.php">Fertilizantes</a></li>
                    <li><a href="herramientas.php">Herramientas</a></li>
                    <li><a href="maquinaria.php">Maquinaria</a></li>
                    <li><a href="asesoria.php">Asesoría</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="logout.php" class="logout-header">Cerrar sesión</a>
    </div>
</header>

<main>
    <h2>Bienvenido al Panel</h2>
    <p>Tu rol es: <strong><?= htmlspecialchars($rol) ?></strong></p>

    <?php if ($rol === 'vendedor'): ?>
        <p>Aquí puedes administrar tus productos y ver tus ventas.</p>
        <a href="mis_productos.php" class="btn">Mis Productos</a>
        <a href="agregar_producto.php" class="btn">Agregar Producto</a>
        <a href="ventas.php" class="btn">Ventas</a>
    <?php else: ?>
        <p>Explora nuestros productos disponibles para ti.</p>
        <a href="productos.php" class="btn">Ver Productos</a>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date("Y") ?> Agromarket. Todos los derechos reservados.
</footer>
</body>
</html>
