<?php
session_start();

// BLOQUEO TOTAL DE CACHÉ (VERSIÓN FUERTE)
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Si NO hay sesión → redirigir
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "estudiante") {
    header("Location: index.php");
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
            </div>
        </div>
    </header>

    <main role="main">
        <nav class="main-nav">
            <ul>
                <li><a href="logout.php">Cerrar Sesión</a></li>
                <li><a href="reservar.php">Reservar Cubículo</a></li>
                <li><a class="active" href="aulas.php">Aulas y Espacios</a></li>
                <li><a href="mis-reservas.php">Mis Reservas</a></li>
                <li><a href="biblioteca.php">Info Biblioteca</a></li>
                <li><a href="https://webiujocatia.wordpress.com/contacto/" target="_blank" rel="noopener noreferrer">Contacto</a></li>
            </ul>
        </nav>

        <hr>

        <section id="aulas" style="padding: 20px;">
            <p style="font-size:18px; font-weight:bold;">
    Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?> 👋
</p>    

            <h2>Aulas y Espacios</h2>

            <h3>Galería de Aulas</h3>

            <div class="grid-aulas">
                
                <!-- Tarjeta 1 -->
                <div class="aula-card">
                    <h4>Aula 1</h4>
                    <p><strong>Capacidad:</strong> 8-10 personas</p>
                    <p><strong>Equipamiento:</strong> Pizarra, TV HDMI, WiFi</p>
                    <p><strong>Estado:</strong> Disponible</p>
                </div>

                <!-- Tarjeta 2 -->
                <div class="aula-card">
                    <h4>Aula 2</h4>
                    <p><strong>Capacidad:</strong> 5-6 personas</p>
                    <p><strong>Equipamiento:</strong> Pizarra, TV</p>
                    <p><strong>Estado:</strong> Ocupada</p>
                </div>

                <!-- Tarjeta 3 -->
                <div class="aula-card">
                    <h4>Aula 3</h4>
                    <p><strong>Capacidad:</strong> 5-6 personas</p>
                    <p><strong>Equipamiento:</strong> Pizarra, Proyector</p>
                    <p><strong>Estado:</strong> Disponible</p>
                </div>

                <!-- Tarjeta 4 -->
                <div class="aula-card">
                    <h4>Aula 4</h4>
                    <p><strong>Capacidad:</strong> 5-6 personas</p>
                    <p><strong>Equipamiento:</strong> Mesa redonda, Pizarra</p>
                    <p><strong>Estado:</strong> Mantenimiento</p>
                </div>

                <!-- Tarjeta 5 -->
                <div class="aula-card">
                    <h4>Aula 5</h4>
                    <p><strong>Capacidad:</strong> 2-3 personas</p>
                    <p><strong>Equipamiento:</strong> Escritorio, PC</p>
                    <p><strong>Estado:</strong> Disponible</p>
                </div>

                <!-- Tarjeta 6 -->
                <div class="aula-card">
                    <h4>Aula 6</h4>
                    <p><strong>Capacidad:</strong> 2-3 personas</p>
                    <p><strong>Equipamiento:</strong> Pizarra pequeña</p>
                    <p><strong>Estado:</strong> Disponible</p>
                </div>

            </div>


            <br>

            <h3>Sistema de Visualización de Horarios Disponibles (Ejemplo)</h3>

            <p>Los horarios se pueden visualizar como una lista simple:</p>

            <ul>
                <li>Aula 1: 08:00 AM - 10:00 AM (Disponible), 10:00 AM - 12:00 PM (Ocupada).</li>
                <li>Aula 2: 09:00 AM - 11:00 AM (Ocupada), 11:00 AM - 01:00 PM (Disponible).</li>
                <li>Aula 3: 02:15 PM - 05:00 PM (Disponible), 09:00 AM - 01:00 PM (Ocupada).</li>
            </ul>

        </section>
    </main>

    <footer style="background-color: #f4f4f4; padding: 20px; margin-top: 20px;">
        <div class="footer-container">
            <p>&copy; 2025 Instituto Universitario Jesús Obrero (IUJO).</p>
        </div>
    </footer>

</body>
</html>
