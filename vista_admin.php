<?php
session_start();

// Verifica estrictamente que la sesión rol exista Y sea admin
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    // Si falla, manda al login
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Refuerzo anti-caché -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Aulas y Espacios | IUJO</title>

<!-- NO BORRAR ESTE SCRIPT POR FAVOR, SIRVE PARA MATAR LA SESION POR COMPLETO. ATT: CUBANO-->
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
            <div class="logo">
                <a href="aulas.php">
                    <img src="images/LOGO IUJO.jpg" alt="Logo IUJO" style="max-width: 300px;">
                </a>
                <h2>Panel de Control - Administrador</h2>
            </div>
        </div>
    </header>

    <main role="main"> 
        <div class="opciones-admin">
            <!-- Aquí pones el contenido que solo tú puedes ver -->
            <button>Ver lista de Estudiantes</button>
            <button>Ver Registros de Aulas</button>
            <button>Reporte</button>
            <button><a href="logout.php">Cerrar Sesión</a></button>
            <br><br>
            <h3>Bienvenido, <?php echo $_SESSION["nombre"]; ?></h3>
        </div>
    </main>

    <footer style="background-color: #f4f4f4; padding: 20px; margin-top: 20px;">
        <div class="footer-container">
            <p>&copy; 2025 Instituto Universitario Jesús Obrero (IUJO).</p>
        </div>
    </footer>

</body>
</html>