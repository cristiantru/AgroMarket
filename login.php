<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT id, contrasena, rol FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash, $rol);
        $stmt->fetch();

        if (password_verify($contrasena, $hash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['rol'] = $rol;  // guardamos rol en sesión

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Iniciar Sesión - Agromarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/style.css" />
</head>
<body>

<div class="contenedor">
    <div class="izquierda">
        <h1>¡Bienvenido!</h1>
        <p>Ingresa tus datos personales para utilizar todas las funciones del sitio</p>
        <a href="register.php"><button>Regístrate</button></a>
    </div>
    <div class="derecha">
        <h2>Inicio de Sesión</h2>
        <form method="post" onsubmit="return validarFormulario()">
            <input type="email" name="correo" placeholder="Correo Electrónico" required />
            <input type="password" name="contrasena" placeholder="Contraseña" required />
            <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</div>

<script>
function validarFormulario() {
    const email = document.querySelector('input[name="correo"]');
    const pass = document.querySelector('input[name="contrasena"]');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email.value || !regex.test(email.value)) {
        alert("Por favor, introduce un correo válido.");
        return false;
    }
    if (!pass.value || pass.value.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
