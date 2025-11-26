<?php
session_start();

// =================================
// ANTI-CACHE (EVITA VOLVER ATRÁS)
// =================================
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION["id_estudiante"])) {
    header("Location: index.php");
    exit;
}

// =================================
// PROCESAR FORMULARIO
// =================================
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_estudiante = $_SESSION["id_estudiante"];
    $id_aula       = $_POST["aula"];
    $fechaHora     = $_POST["fecha_hora"];

    // Validación
    if (empty($id_aula) || empty($fechaHora)) {
        $_SESSION["error"] = "Por favor complete todos los campos.";
        header("Location: reservar.php");
        exit;
    }

    // Separar fecha y hora
    $fecha = date("Y-m-d", strtotime($fechaHora));
    $hora_inicio = date("H:i:s", strtotime($fechaHora));

    // Hora fin automática (1 hora)
    $hora_fin = date("H:i:s", strtotime($fechaHora . " +1 hour"));

    // Insertar reserva
    $stmt = $conexion->prepare("
        INSERT INTO Reservas (id_estudiante, id_aula, fecha_reserva, hora_inicio, hora_fin, estado)
        VALUES (?, ?, ?, ?, ?, 'pendiente')
    ");

    $stmt->bind_param("iisss", $id_estudiante, $id_aula, $fecha, $hora_inicio, $hora_fin);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Reserva realizada exitosamente.";
        header("Location: mis-reservas.php");
        exit;
    } else {
        $_SESSION["error"] = "Hubo un error al guardar la reserva.";
        header("Location: reservar.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Cubículo | IUJO</title>
    <link rel="stylesheet" href="estilos.css">

    <!-- BLOQUEO DE BFCache -->
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

</head>
<body>

<header>
    <div class="header-container">
        <a href="aulas.php">
            <img src="images/LOGO IUJO.jpg" alt="Logo IUJO" style="max-width: 300px;">
        </a>
    </div>
</header>

<main role="main">
<nav class="main-nav">
    <ul>
        <li><a href="aulas.php">Aulas</a></li>
        <li><a class="active" href="reservar.php">Reservar Cubículo</a></li>
        <li><a href="mis-reservas.php">Mis Reservas</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</nav>

<hr>

<section style="padding: 20px;">

    <h2>Reservar Cubículo</h2>

    <!-- Mensajes -->
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color:red; font-weight:bold;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["success"])): ?>
        <p style="color:green; font-weight:bold;"><?php echo $_SESSION["success"]; ?></p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <form method="POST" action="reservar.php">

        <label for="aula">Seleccione el Aula:</label>
        <select id="aula" name="aula" required>

            <option value="">-- Seleccione un aula --</option>

            <?php
            // Traer aulas desde BD
            $aulas = $conexion->query("SELECT id_aula, nombre_aula, capacidad FROM Aulas");
            while ($a = $aulas->fetch_assoc()) {
                echo "<option value='{$a['id_aula']}'>".
                       $a['nombre_aula']." (Capacidad: ".$a['capacidad'].")".
                     "</option>";
            }
            ?>

        </select>

        <br><br>

        <label for="fecha_hora">Fecha y Hora de Inicio:</label>
        <input type="datetime-local" id="fecha_hora" name="fecha_hora" required>

        <br><br>

        <button type="submit">Confirmar Reserva</button>

    </form>

</section>

</main>

</body>
</html>
