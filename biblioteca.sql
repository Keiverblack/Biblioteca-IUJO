-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2025 a las 03:29:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL,
  `nombre_aula` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `televisor` enum('si','no') DEFAULT 'no',
  `pizarra` enum('si','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id_aula`, `nombre_aula`, `capacidad`, `televisor`, `pizarra`) VALUES
(1, 'Aula 1', 10, 'si', 'si'),
(2, 'Aula 2', 6, 'si', 'si'),
(3, 'Aula 3', 6, 'no', 'si'),
(4, 'Aula 4', 6, 'no', 'si'),
(5, 'Aula 5', 3, 'no', 'si'),
(6, 'Aula 6', 3, 'no', 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo_institucional` varchar(100) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `cedula`, `nombre`, `apellido`, `correo_institucional`, `carrera`, `contrasena`) VALUES
(1, '31694238', 'keiver', 'blanco', 'keiver@gmail.com', 'Informática', '$2y$10$3KzPLbhPiOpoT89uPGPpJe1B/ZsE5MMQe3vmjiZ5tRS3HXPC/Ani6'),
(2, 'v-2313123123', 'Samuel', 'Cubano', 's@g.com', 'Informática', '$2y$10$qFlG2qt0aRgu7LUHbQsUvOnNyo6NGjgfirJ9WX3LXkIiSs/loTOcG'),
(3, '32935820', 'Samuel', 'Cubano', 'keiver@g.com', 'Informática', '$2y$10$5R9Bd0U0koGebPY0wywIm.bARyJzAhrrj2KL7.Akaxz8PFQrknI0K'),
(4, '123123123', 'JOSEITO', 'perez', '123@g.com', 'Mecánica', '$2y$10$cyUcgBOq.U98A2uQTea/4e9yavaEMdYGTzFqXHz/UCax9O3ezajYO'),
(5, '443423424', 'Cristiano Ronaldo', 'Dos Santos', 'siuu@gmail.com', 'Contaduría', '$2y$10$AKhxyxnizJTjsoNxoSb9n.JikjnQybFtKi2r3oOM3f0JnpzBHOgcG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_estudiante`, `id_aula`, `fecha_reserva`, `hora_inicio`, `hora_fin`, `estado`) VALUES
(3, 1, 1, '2025-11-26', '15:21:00', '17:12:00', 'confirmada'),
(5, 1, 5, '2025-11-27', '12:52:00', '13:52:00', 'cancelada');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo_institucional` (`correo_institucional`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_aula` (`id_aula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
