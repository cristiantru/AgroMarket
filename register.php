<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];  // nuevo campo rol
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro - Agromarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/style.css" />
</head>
<body>

<div class="contenedor">
    <div class="izquierda">
        <h1>¡Únete a Agromarket!</h1>
        <p>Crea tu cuenta para disfrutar de todos los beneficios y funciones que ofrecemos.</p>
        <a href="login.php"><button>¿Ya tienes cuenta? Inicia sesión</button></a>
    </div>
    <div class="derecha">
        <h2>Crear Cuenta</h2>
        <form method="post" onsubmit="return validarFormulario()">
            <input type="text" name="nombre" placeholder="Nombre completo" required />
            <input type="email" name="correo" placeholder="Correo electrónico" required />
            <input type="password" name="contrasena" placeholder="Contraseña" required />
            
            <label for="rol">Selecciona tu rol:</label>
            <select name="rol" id="rol" required>
                <option value="comprador" selected>Comprador</option>
                <option value="vendedor">Vendedor</option>
            </select>

            <button type="submit">Registrarse</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</div>

<script>
function validarFormulario() {
    const email = document.querySelector('input[name="correo"]');
    const pass = document.querySelector('input[name="contrasena"]');
    const rol = document.querySelector('select[name="rol"]');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email.value || !regex.test(email.value)) {
        alert("Por favor, introduce un correo válido.");
        return false;
    }
    if (!pass.value || pass.value.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        return false;
    }
    if (!rol.value) {
        alert("Por favor, selecciona un rol.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
