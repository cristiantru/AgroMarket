<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Productos - Agromarket</title>
  <link rel="stylesheet" href="style/footer.css" />
</head>
<body>
<header>
  <div class="container-header">
    <h1 class="logo">Agromarket</h1>
    <nav>
      <ul class="nav-menu">
        <li><a href="productos.php">Productos</a></li>
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
  <h2>Maquinaria</h2>
  <p>Aquí puedes ver todos los productos agrícolas disponibles.</p>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> Agromarket. Todos los derechos reservados.
</footer>
</body>
</html>
