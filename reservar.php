<?php
session_start();

// 1. CONEXIÓN (Mover al inicio)
require "conexion.php";

// =================================
// ANTI-CACHE
// =================================
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verifica la sesión
if (!isset($_SESSION["id_estudiante"]) || !is_numeric($_SESSION["id_estudiante"]) || $_SESSION["id_estudiante"] <= 0) {
    $_SESSION["error"] = "Error de sesión. Por favor, vuelva a iniciar sesión.";
    header("Location: index.php");
    exit;
}

// =================================
// PROCESAR FORMULARIO
// =================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // A. OBTENER Y ASIGNAR VARIABLES
    $id_estudiante = $_SESSION["id_estudiante"];
    $id_aula       = filter_input(INPUT_POST, 'aula', FILTER_VALIDATE_INT);
    $fecha         = $_POST["fecha_reserva"]; 
    $hora_inicio   = $_POST["hora_inicio"];   
    $hora_fin      = $_POST["hora_fin"];      
    
    // B. VALIDACIÓN DE CAMPOS
    if (empty($id_aula) || empty($fecha) || empty($hora_inicio) || empty($hora_fin)) {
        $_SESSION["error"] = "Por favor complete todos los campos.";
        header("Location: reservar.php");
        exit;
    }

    if (strtotime($hora_inicio) >= strtotime($hora_fin)) {
        $_SESSION["error"] = "La hora de inicio debe ser anterior a la hora de salida.";
        header("Location: reservar.php");
        exit;
    }
    
    $hora_inicio_db = $hora_inicio . ":00";
    $hora_fin_db    = $hora_fin . ":00";

    // =================================
    // C. VALIDACIÓN DE DISPONIBILIDAD (MEJORADA)
    // =================================
    $stmt_check = $conexion->prepare("
        SELECT hora_inicio, hora_fin 
        FROM Reservas 
        WHERE id_aula = ? 
        AND fecha_reserva = ? 
        AND estado IN ('pendiente', 'confirmada')
        AND hora_inicio < ? 
        AND hora_fin > ?
    ");
    
    // Parametros: id_aula, fecha, tu_hora_salida, tu_hora_entrada
    $stmt_check->bind_param("isss", $id_aula, $fecha, $hora_fin_db, $hora_inicio_db);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Aquí capturamos la hora que choca
        $ocupado = $result_check->fetch_assoc();
        $ini_ocupado = date("H:i", strtotime($ocupado['hora_inicio']));
        $fin_ocupado = date("H:i", strtotime($ocupado['hora_fin']));

        $_SESSION["error"] = "El aula ya está ocupada de $ini_ocupado a $fin_ocupado. Seleccione otro horario.";
        header("Location: reservar.php");
        exit;
    }

    // =================================
    // D. INSERTAR RESERVA
    // =================================
    $stmt = $conexion->prepare("
        INSERT INTO Reservas (id_estudiante, id_aula, fecha_reserva, hora_inicio, hora_fin, estado)
        VALUES (?, ?, ?, ?, ?, 'pendiente')
    ");

    // "iisss" corregido
    $stmt->bind_param("iisss", $id_estudiante, $id_aula, $fecha, $hora_inicio_db, $hora_fin_db); 

    if ($stmt->execute()) {
        $_SESSION["success"] = "Reserva realizada exitosamente.";
        header("Location: mis-reservas.php");
        exit;
    } else {
        $_SESSION["error"] = "Hubo un error al guardar la reserva: " . $stmt->error;
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
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) { window.location.reload(); }
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
            $aulas = $conexion->query("SELECT id_aula, nombre_aula, capacidad FROM Aulas");
            if ($aulas) {
                while ($a = $aulas->fetch_assoc()) {
                    echo "<option value='{$a['id_aula']}'>".
                           htmlspecialchars($a['nombre_aula'])." (Capacidad: ".$a['capacidad'].")".
                         "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <label for="fecha_reserva">Fecha Reserva:</label>
        <input type="date" id="fecha_reserva" name="fecha_reserva" required>
        <br><br>
        
        <label for="hora_inicio">Hora de Entrada:</label>
        <input type="time" id="hora_inicio" name="hora_inicio" required>
        <br><br>

        <label for="hora_fin">Hora de Salida:</label>
        <input type="time" id="hora_fin" name="hora_fin" required>
        <br><br>

        <button type="submit">Confirmar Reserva</button>
    </form>
</section>
</main>
</body>
</html>