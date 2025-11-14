-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 10-11-2025 a las 18:55:13
-- Versión del servidor: 8.0.44
-- Versión de PHP: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `toneleros`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `tipo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pContacto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tlf` int NOT NULL,
  `presupuesto` int NOT NULL,
  `senal` int NOT NULL,
  `observaciones` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `equipo` tinyint(1) NOT NULL,
  `archivado` int DEFAULT '0',
  `cerrada` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `tipo`, `fecha`, `direccion`, `pContacto`, `tlf`, `presupuesto`, `senal`, `observaciones`, `equipo`, `archivado`, `cerrada`) VALUES
(336, 'Boda', '2025-12-15 00:00:00', 'Finca El Olivar, Madrid', 'Laura Pérez', 601123456, 3500, 700, 'Requiere DJ y fotógrafo. Horario de 18:00 a 02:00.', 1, 0, 0),
(337, 'Concierto en Bar', '2025-11-20 00:00:00', 'Sala Rock Corner, C/ Mayor 15', 'Javier Ramos', 699876543, 650, 150, 'Grupo de 4 personas. Uso de equipo del local (sonido e iluminación).', 0, 0, 0),
(338, 'Evento Corporativo', '2026-01-25 00:00:00', 'Hotel Palace, Salón A', 'Marta Gómez (ACME Corp)', 915554433, 12001, 2500, 'Lanzamiento de producto. Montaje de pantalla LED y 3 azafatas.', 1, 0, 0),
(339, 'Clase Privada', '2025-12-01 00:00:00', 'Domicilio Particular, Sevilla', 'Andrés Fernández', 666112233, 80, 80, 'Clase de guitarra de 1 hora. Pago realizado.', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`) VALUES
(1, 'Pedro', '$2b$12$AZvSQtty6.gUZGOd0BgUU.yD8Ms8FZFX0nxt0uaTwMWSzxKV2eBbK');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
