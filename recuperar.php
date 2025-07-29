<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $mensaje = "Se han enviado instrucciones a tu correo (simulado).";
    } else {
        $mensaje = "Este correo no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="style\style.css">
</head>
<body>
<div class="contenedor solo-form">
    <div class="registro-form">
        <h2>Recuperar contraseña</h2>
        <form method="post" onsubmit="return validarCorreo()">
            <input type="email" name="correo" id="correo" placeholder="Ingresa tu correo" required>
            <button type="submit">Recuperar</button>
        </form>
        <?php if (isset($mensaje)) echo "<p class='error'>$mensaje</p>"; ?>
    </div>
</div>
<script>
function validarCorreo() {
    const email = document.getElementById('correo').value;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email)) {
        alert("Por favor, ingresa un correo válido.");
        return false;
    }
    return true;
}
</script>
</body>
</html>