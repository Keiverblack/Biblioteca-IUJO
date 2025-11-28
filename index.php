<?php
session_start();

// ===========================
// BLOQUEO DE CACHÉ
// ===========================
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// =====================================
// 1. SI YA ESTÁ LOGUEADO, REDIRIGIR SEGÚN ROL
// =====================================
if (isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] === "admin") {
        header("Location: vista_admin.php");
        exit;
    } elseif ($_SESSION["rol"] === "estudiante") {
        header("Location: aulas.php");
        exit;
    }
}

// =====================================
// 2. PROCESAR LOGIN
// =====================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require "conexion.php";

    $input_usuario = trim($_POST["correo"]);
    $password      = $_POST["password"];

    // ===========================================
    // A) ADMIN HARDCODEADO (SIN TABLA)
    // ===========================================
    $admin_user = "admin@iujo.edu.ve";
    $admin_pass = "admin123"; // cámbiala si quieres

    if ($input_usuario === $admin_user && $password === $admin_pass) {
        $_SESSION["id_usuario"] = 0;
        $_SESSION["nombre"]     = "Administrador";
        $_SESSION["rol"]        = "admin";

        header("Location: vista_admin.php");
        exit;
    }

    // ===========================================
    // B) SI NO ES ADMIN → BUSCAMOS ESTUDIANTE
    // ===========================================
    $stmt = $conexion->prepare("SELECT * FROM estudiantes WHERE correo_institucional = ?");
    $stmt->bind_param("s", $input_usuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $usuario = $res->fetch_assoc();

        if (password_verify($password, $usuario["contrasena"])) {

            $_SESSION["id_usuario"] = $usuario["id_estudiante"];
            $_SESSION["nombre"]     = $usuario["nombre"];
            $_SESSION["rol"]        = "estudiante";

            header("Location: aulas.php");
            exit;

        } else {
            $_SESSION["error"] = "Contraseña incorrecta.";
        }

    } else {
        $_SESSION["error"] = "Usuario no registrado.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | IUJO</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

<header>
    <div class="header-container">
        <a href="index.php">
            <img src="images/LOGO IUJO.jpg" alt="Logo IUJO" style="max-width: 300px;">
        </a>
    </div>
</header>

<main role="main">
    <nav class="main-nav">
        <ul>
            <li><a class="active" href="index.php">Inicio</a></li>
            <li><a href="registro.php">Registro</a></li>
        </ul>
    </nav>
    <hr>

    <section class="login-container">
        <div class="login-card">

            <h3>Iniciar Sesión</h3>

            <?php if (isset($_SESSION["error"])): ?>
                <p style="color:red; font-weight:bold;">
                    <?php echo $_SESSION["error"]; ?>
                </p>
                <?php unset($_SESSION["error"]); // Borrar error tras mostrarlo ?>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <!-- IMPORTANTE: Cambiado a type="text" y etiqueta actualizada -->
                <label for="correo">Correo:</label>
                <input type="text" id="correo" name="correo"
                       placeholder="Ingresar su Correo institucional" required>

                <br>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password"  placeholder="Ingrese su contraseña" required>

                <br>

                <button type="submit">Ingresar</button>
            </form>

            <p style="margin-top:15px;">
                ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
            </p>
        </div>
    </section>
</main>

</body>
</html>