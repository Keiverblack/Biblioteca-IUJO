<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas | IUJO</title>
</head>
<body>

    <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.html">
                    <img src="images/LOGO IUJO.jpg" alt="Logo IUJO" style="max-width: 300px;">
                </a>
            </div>
        </div>
    </header>

    <main role="main">
        <nav class="main-nav">
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="registro.html">Registro</a></li>
                <li><a href="reservar.html">Reservar Cubículo</a></li>
                <li><a href="aulas.html">Aulas y Espacios</a></li>
                <li><a href="mis-reservas.html">Mis Reservas</a></li>
                <li><a href="biblioteca.html">Info Biblioteca</a></li>
                <li><a href="https://webiujocatia.wordpress.com/contacto/" target="_blank" rel="noopener noreferrer">Contacto</a></li>
            </ul>
        </nav>
        <hr>

        <section id="mis-reservas" style="padding: 20px;">
            <h2>Mis Reservas</h2>
        
            <h3>Tabla de Mis Reservas Activas</h3>
            <table border="1" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>Aula reservada</th>
                        <th>Fecha y hora</th>
                        <th>Tiempo de uso</th>
                        <th>Estado de la reserva</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aula 1</td>
                        <td>2025-11-20, 10:00 AM</td>
                        <td>2 horas</td>
                        <td>Confirmada</td>
                        <td>
                            <button>Modificar</button>
                            <button>Cancelar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Aula 3</td>
                        <td>2025-11-21, 02:00 PM</td>
                        <td>1 hora</td>
                        <td>Pendiente</td>
                        <td>
                            <button>Modificar</button>
                            <button>Cancelar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        
            <br>

            <h3>Listado de Reservas Históricas</h3>
            <p>A continuación se muestra un listado de reservas pasadas:</p>
            <table border="1" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>Aula</th>
                        <th>Fecha y hora</th>
                        <th>Tiempo de uso</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aula 2</td>
                        <td>2025-11-15, 09:00 AM</td>
                        <td>3 horas</td>
                        <td>Completada</td>
                    </tr>
                    <tr>
                        <td>Aula 1</td>
                        <td>2025-11-10, 04:00 PM</td>
                        <td>1 hora</td>
                        <td>Cancelada</td>
                    </tr>
                    <tr>
                        <td>Aula 3</td>
                        <td>2025-11-01, 11:00 AM</td>
                        <td>2 horas</td>
                        <td>Completada</td>
                    </tr>
                </tbody>
            </table>
        
        </section>
    </main>

    <footer style="background-color: #f4f4f4; padding: 20px; margin-top: 20px;">
        <div class="footer-container">
            <p>&copy; 2025 Instituto Universitario Jesús Obrero (IUJO).</p>
        </div>
    </footer>
</body>
</html>