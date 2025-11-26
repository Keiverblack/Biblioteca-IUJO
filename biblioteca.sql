-- Tabla de estudiantes
CREATE TABLE Estudiantes (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo_institucional VARCHAR(100) UNIQUE NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL
);

-- Tabla de aulas
CREATE TABLE Aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    nombre_aula VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    ubicacion VARCHAR(100) NOT NULL
);

-- Tabla de reservas
CREATE TABLE Reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_aula INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_estudiante) REFERENCES Estudiantes(id_estudiante),
    FOREIGN KEY (id_aula) REFERENCES Aulas(id_aula)
);

-- Tabla de administradores
CREATE TABLE Administradores (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('bibliotecario','superadmin') DEFAULT 'bibliotecario'
);