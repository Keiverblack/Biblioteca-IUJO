<?php
// Iniciar sesión para manejar errores o datos
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario | IUJO</title>
</head>
<body>

<?php
// Si vienen datos del formulario, procesamos el registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Conectar a la BD
    require "conexion.php";

    // 2. Capturar datos del formulario
    $nombre     = trim($_POST["nombre"]);
    $apellido   = trim($_POST["apellido"]);
    $cedula     = trim($_POST["cedula"]);
    $correo     = trim($_POST["email"]);
    $carrera    = trim($_POST["carrera"]);
    $pass       = $_POST["password"];
    $pass2      = $_POST["repeat_password"];

    // 3. Validar contraseñas
    if ($pass !== $pass2) {
        $_SESSION["error"] = "Las contraseñas no coinciden.";
        header("Location: registro.php");
        exit;
    }

    // 4. Validar duplicados (cedula o correo existentes)
    $stmt = $conexion->prepare("SELECT * FROM Estudiantes WHERE cedula = ? OR correo_institucional = ?");
    $stmt->bind_param("ss", $cedula, $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION["error"] = "Ya existe un usuario con esa cédula o correo.";
        header("Location: registro.php");
        exit;
    }

    // 5. Encriptar contraseña
    $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

    // 6. Insertar en la base de datos
    $stmt2 = $conexion->prepare("
        INSERT INTO Estudiantes (cedula, nombre, apellido, correo_institucional, carrera, contrasena)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param("ssssss", $cedula, $nombre, $apellido, $correo, $carrera, $passwordHash);

    if ($stmt2->execute()) {
        $_SESSION["success"] = "Registro exitoso. Ahora puede iniciar sesión.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION["error"] = "Hubo un problema al registrar. Inténtelo nuevamente.";
        header("Location: registro.php");
        exit;
    }
}
?>

    <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="images/LOGO IUJO.jpg" alt="Logo IUJO" style="max-width: 300px;">
                </a>
            </div>
        </div>
    </header>

    <main role="main">
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a class="active" href="registro.php">Registro</a></li>
                <li><a href="reservar.php">Reservar Cubículo</a></li>
                <li><a href="aulas.php">Aulas y Espacios</a></li>
                <li><a href="mis-reservas.php">Mis Reservas</a></li>
                <li><a href="biblioteca.php">Info Biblioteca</a></li>
            </ul>
        </nav>
        <hr>

        <section id="registro-usuario" style="padding: 20px;">
            <h2>Registro de Nuevo Usuario</h2>
            <p>Regístrese para poder solicitar cubículos.</p>

            <!-- MENSAJES -->
            <?php if (isset($_SESSION["error"])): ?>
                <p style="color: red; font-weight: bold;"><?php echo $_SESSION["error"]; ?></p>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION["success"])): ?>
                <p style="color: green; font-weight: bold;"><?php echo $_SESSION["success"]; ?></p>
                <?php unset($_SESSION["success"]); ?>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                    <legend><strong>Datos del Solicitante</strong></legend>

                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <br>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>

                    <br>

                    <label for="cedula">Cédula de Identidad:</label>
                    <input type="text" id="cedula" name="cedula" placeholder="V-12345678" required>

                    <br>

                    <label for="email">Correo Institucional:</label>
                    <input type="email" id="email" name="email" placeholder="correo@iujocat.edu.ve" required>

                    <br>

                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>

                    <br>

                    <label for="repeat_password">Repetir Contraseña:</label>
                    <input type="password" id="repeat_password" name="repeat_password" required>

                    <br>

                    <label for="carrera">Carrera:</label>
                    <select id="carrera" name="carrera" required>
                        <option value="">-- Seleccione su carrera --</option>
                        <option value="Informática">Informática</option>
                        <option value="Contaduría">Contaduría</option>
                        <option value="Electrónica">Electrónica</option>
                        <option value="Mecánica">Mecánica</option>
                        <option value="Otra">Otra</option>
                    </select>
                <br>
                <button type="submit">Registrarse</button>
            </form>
        </section>
    </main>
</body>
</html>
