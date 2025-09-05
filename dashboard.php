<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'] ?? 'comprador';

// Conexión BD
$conexion = new mysqli("localhost", "root", "", "agromarket");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 🔹 Si es vendedor, obtenemos métricas reales
if ($rol === 'vendedor') {
    // Total de productos publicados
    $sql = "SELECT COUNT(*) AS total FROM productos WHERE vendedor_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $totalProductos = $resultado['total'];

    // Ventas del mes
   $sql = "SELECT COUNT(*) AS total 
            FROM ventas v
            INNER JOIN productos p ON v.producto_id = p.id
            WHERE p.vendedor_id = ?
            AND MONTH(v.fecha) = MONTH(CURDATE()) 
            AND YEAR(v.fecha) = YEAR(CURDATE())";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $ventasMes = $resultado['total'];

    // Ingresos del mes
     $sql = "SELECT SUM(v.total) AS ingresos 
            FROM ventas v
            INNER JOIN productos p ON v.producto_id = p.id
            WHERE p.vendedor_id = ?
            AND MONTH(v.fecha) = MONTH(CURDATE()) 
            AND YEAR(v.fecha) = YEAR(CURDATE())";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $ingresos = $resultado['ingresos'] ?? 0;

    // Productos con stock bajo (ej. < 5)

    // Ventas por semana (para gráfica)
       $sql = "SELECT WEEK(v.fecha) AS semana, COUNT(*) AS total 
            FROM ventas v
            INNER JOIN productos p ON v.producto_id = p.id
            WHERE p.vendedor_id = ?
            AND MONTH(v.fecha) = MONTH(CURDATE()) 
            AND YEAR(v.fecha) = YEAR(CURDATE()) 
            GROUP BY WEEK(v.fecha)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $valores = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = "Semana " . $row['semana'];
        $valores[] = $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Usuario - Agromarket</title>
    <link rel="stylesheet" href="style/footer.css" />
    <link rel="stylesheet" href="style/dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                <?php if ($rol === 'vendedor'): ?>
                     <li><span style="color: #fff; font-weight: bold; padding: 8px 12px; border-radius: 5px; background-color: #388E3C;">Panel del Vendedor</span></li>
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

        <!-- 🔹 Resumen en Cards -->
        <div class="dashboard">
            <div class="card">
                <h3>Productos publicados</h3>
                <p><?= $totalProductos ?></p>
            </div>
            <div class="card">
                <h3>Ventas este mes</h3>
                <p><?= $ventasMes ?></p>
            </div>
            <div class="card">
                <h3>Ingresos</h3>
                <p>$<?= number_format($ingresos, 2) ?></p>
            </div>
            
        </div>

        <!-- 🔹 Gráfica de Ventas -->
        <div class="grafica">
            <h3>Ventas del último mes</h3>
            <canvas id="ventasChart"></canvas>
        </div>

        <script>
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Ventas',
                    data: <?= json_encode($valores) ?>,
                    backgroundColor: '#27ae60'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        </script>
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
