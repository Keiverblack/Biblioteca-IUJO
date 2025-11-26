<?php
session_start();

// ===========================
// BLOQUEO COMPLETO DE CACHÉ
// ===========================
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// =====================================
// SI EL USUARIO YA ESTÁ LOGUEADO
// =====================================
if (isset($_SESSION["id_estudiante"])) {
    header("Location: aulas.php"); // ANTES: aulas.html (mal)
    exit;
}

// =====================================
// PROCESAR LOGIN DESDE ESTE MISMO INDEX
// =====================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require "conexion.php";

    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    // Buscar estudiante
    $stmt = $conexion->prepare("SELECT * FROM Estudiantes WHERE correo_institucional = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario["contrasena"])) {

            // Crear sesión
            $_SESSION["id_estudiante"] = $usuario["id_estudiante"];
            $_SESSION["nombre"]        = $usuario["nombre"];
            $_SESSION["apellido"]      = $usuario["apellido"];
            $_SESSION["correo"]        = $usuario["correo_institucional"];

            header("Location: aulas.php"); // ANTES enviaba de regreso al index
            exit;

        } else {
            $_SESSION["error"] = "Contraseña incorrecta.";
        }

    } else {
        $_SESSION["error"] = "El correo no está registrado.";
    }

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | IUJO</title>
    <link rel="stylesheet" href="estilos.css">

    <!-- Meta reforzado anti-caché -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
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
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <label for="correo">Correo institucional:</label>
                <input type="email" id="correo" name="correo"
                       placeholder="usuario@correo.edu" required>

                <br>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

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
