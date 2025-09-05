<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit;
}

require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $vendedor_id = $_SESSION['usuario_id'];

    if (empty($nombre) || empty($descripcion) || $precio <= 0) {
        $error = "Por favor completa todos los campos correctamente.";
    } else {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, vendedor_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $vendedor_id);
        if ($stmt->execute()) {
            header("Location: mis_productos.php");
            exit;
        } else {
            $error = "Error al agregar producto: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="style/estilos-vendedor.css">
</head>
<body>
    <header>
    <div class="container-header">
        <h1 class="logo">Agromarket</h1>
        <nav>
            <ul class="nav-menu">
                
                <li><a href="dashboard.php">Panel</a></li>
                <li><a href="mis_productos.php">Mis Productos</a></li>
                <li><a href="agregar_producto.php" class="activo">Agregar Producto</a></li>
                <li><a href="ventas.php">Ventas</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-header">Cerrar sesión</a>
    </div>
</header>


    <main class="contenedor">
        <section class="formulario">
            <h2>Agregar Nuevo Producto</h2>
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" name="nombre" id="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="4" required></textarea>

                <label for="precio">Precio (MXN):</label>
                <input type="number" name="precio" id="precio" step="0.01" required>

                <button type="submit">Agregar</button>
            </form>
        </section>
    </main>
</body>
</html>
