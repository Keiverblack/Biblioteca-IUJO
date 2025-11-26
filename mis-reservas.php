<?php
session_start();

// 1. CONEXIÓN A BD
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

    $id_estudiante = $_SESSION["id_estudiante"];
    $id_aula       = filter_input(INPUT_POST, 'aula', FILTER_VALIDATE_INT);
    $fecha         = $_POST["fecha_reserva"]; 
    $hora_inicio   = $_POST["hora_inicio"];   
    $hora_fin      = $_POST["hora_fin"];      
    
    // VALIDACIÓN BÁSICA
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

    // VALIDACIÓN DE SOLAPAMIENTO (TU CÓDIGO MEJORADO)
    $stmt_check = $conexion->prepare("
        SELECT hora_inicio, hora_fin 
        FROM Reservas 
        WHERE id_aula = ? 
        AND fecha_reserva = ? 
        AND estado IN ('pendiente', 'confirmada')
        AND hora_inicio < ? 
        AND hora_fin > ?
    ");
    
    $stmt_check->bind_param("isss", $id_aula, $fecha, $hora_fin_db, $hora_inicio_db);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $ocupado = $result_check->fetch_assoc();
        $ini_ocupado = date("H:i", strtotime($ocupado['hora_inicio']));
        $fin_ocupado = date("H:i", strtotime($ocupado['hora_fin']));

        $_SESSION["error"] = "El aula ya está ocupada de $ini_ocupado a $fin_ocupado.";
        header("Location: reservar.php");
        exit;
    }

    // INSERTAR RESERVA
    $stmt = $conexion->prepare("
        INSERT INTO Reservas (id_estudiante, id_aula, fecha_reserva, hora_inicio, hora_fin, estado)
        VALUES (?, ?, ?, ?, ?, 'pendiente')
    ");

    $stmt->bind_param("iisss", $id_estudiante, $id_aula, $fecha, $hora_inicio_db, $hora_fin_db); 

    if ($stmt->execute()) {
        $_SESSION["success"] = "Solicitud enviada exitosamente.";
        // Redirigimos a la misma página para ver la lista actualizada abajo
        header("Location: reservar.php"); 
        exit;
    } else {
        $_SESSION["error"] = "Error al guardar: " . $stmt->error;
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
    <!-- Estilos simples para la tabla de historial -->
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
        th { 
            background-color: #b71c1c; 
            color: white; 
        }
        .estado-pendiente { color: orange; font-weight: bold; }
        .estado-confirmada { color: green; font-weight: bold; }
        .estado-rechazada { color: red; font-weight: bold; }
    </style>
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
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</nav>


<section style="padding: 20px;">

    <hr style="margin: 40px 0;">

    <!-- SECCIÓN HISTORIAL DE SOLICITUDES -->
    <h2>Mis Solicitudes Realizadas</h2>

    <?php
    // 1. Preparamos la consulta para obtener las reservas de ESTE estudiante
    // Usamos JOIN para traer el nombre del aula en lugar del ID
    $id_user = $_SESSION['id_estudiante'];
    $sql_historial = "
        SELECT r.id_reserva, a.nombre_aula, r.fecha_reserva, r.hora_inicio, r.hora_fin, r.estado
        FROM Reservas r
        JOIN Aulas a ON r.id_aula = a.id_aula
        WHERE r.id_estudiante = ?
        ORDER BY r.fecha_reserva DESC, r.hora_inicio DESC
    ";

    $stmt_hist = $conexion->prepare($sql_historial);
    $stmt_hist->bind_param("i", $id_user);
    $stmt_hist->execute();
    $resultado_historial = $stmt_hist->get_result();

    if ($resultado_historial->num_rows > 0) {
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>Aula</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Estado</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        while ($fila = $resultado_historial->fetch_assoc()) {
            // Formatear fechas y horas para que se vean bien
            $fecha_fmt = date("d/m/Y", strtotime($fila['fecha_reserva']));
            $hora_i_fmt = date("H:i", strtotime($fila['hora_inicio']));
            $hora_f_fmt = date("H:i", strtotime($fila['hora_fin']));
            
            // Clase CSS para el color del estado
            $clase_estado = "estado-" . strtolower($fila['estado']);

            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['nombre_aula']) . "</td>";
            echo "<td>" . $fecha_fmt . "</td>";
            echo "<td>" . $hora_i_fmt . " - " . $hora_f_fmt . "</td>";
            echo "<td class='{$clase_estado}'>" . ucfirst($fila['estado']) . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No has realizado ninguna solicitud de reserva todavía.</p>";
    }
    ?>

</section>

</main>

</body>
</html>