<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
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
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="contenedor">
    <div class="izquierda">
        <h1>¡Únete a Agromarket!</h1>
        <p>Crea tu cuenta para disfrutar de todos los beneficios y funciones que ofrecemos.</p>
        <a href="login.php"><button>¿Ya tienes cuenta? Inicia sesión</button></a>
    </div>
    <div class="derecha">
        <img src="img/logo.png" alt="Logo AgroMarket" class="logo-esquina"> 
        <h2>Crear Cuenta</h2>
        <form method="post" onsubmit="return validarFormulario()">

            <!-- Nombre -->
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" placeholder="Nombre completo" required />
            </div>

            <!-- Correo -->
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" placeholder="Correo electrónico" required 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       title="Ejemplo: usuario@dominio.com" />
            </div>

            <!-- Contraseña -->
            <div class="input-container password-container">
                <i class="fas fa-lock"></i>
                <input type="password" name="contrasena" placeholder="Contraseña" required />
                <span class="toggle-password" onclick="mostrarOcultarPassword()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <!-- Rol -->
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
function mostrarOcultarPassword() {
    const password = document.querySelector('input[name="contrasena"]');
    const icon = document.querySelector('.toggle-password i');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function validarFormulario() {
    const nombre = document.querySelector('input[name="nombre"]');
    const email = document.querySelector('input[name="correo"]');
    const pass = document.querySelector('input[name="contrasena"]');
    const rol = document.querySelector('select[name="rol"]');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!nombre.value) {
        alert("Por favor, introduce tu nombre completo.");
        return false;
    }
    if (!email.value || !regex.test(email.value)) {
        alert("Por favor, introduce un correo válido. Ejemplo: usuario@dominio.com");
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
