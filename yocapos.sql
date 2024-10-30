-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-10-2024 a las 12:26:18
-- Versión del servidor: 10.11.9-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u858281167_yocapos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `id_comercio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `telefono`, `id_comercio`) VALUES
(2, 'Guille', '264588', 1),
(3, 'enrique', '2644158029', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comercios`
--

CREATE TABLE `comercios` (
  `id_comercio` int(11) NOT NULL,
  `comercio` varchar(100) NOT NULL,
  `domicilio` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comercios`
--

INSERT INTO `comercios` (`id_comercio`, `comercio`, `domicilio`, `telefono`, `correo`, `logo`) VALUES
(1, 'yoca', 'rawson', '264444', NULL, NULL),
(2, '1112', 'Lemos y 5', '423432', NULL, 'img/usuarios/1112.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_pedido`, `id_producto`, `cantidad`) VALUES
(1, 8, 1),
(2, 8, 1),
(3, 8, 1),
(4, 8, 1),
(5, 8, 1),
(5, 9, 2),
(6, 10, 1),
(7, 15, 5),
(7, 16, 5),
(8, 11, 5),
(8, 15, 5),
(9, 16, 5),
(10, 11, 5),
(10, 18, 5),
(11, 11, 5),
(11, 12, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_usuario_envia` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `notificacion` text NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_comercio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto_pagado` float NOT NULL DEFAULT 0,
  `estado` varchar(50) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_comercio`, `id_usuario`, `monto_pagado`, `estado`, `fecha`) VALUES
(1, 1, 1, 0, 'anulado', '2024-10-21'),
(2, 1, 1, 0.01, 'cobrado', '2024-10-21'),
(3, 1, 1, 0, 'anulado', '2024-10-21'),
(4, 1, 1, 0, 'cobrado', '2024-10-21'),
(5, 1, 1, 0, 'pendiente', '2024-10-23'),
(6, 2, 2, 2000, 'cobrado', '2024-10-24'),
(7, 2, 2, 10000, 'cobrado', '2024-10-24'),
(8, 2, 2, 0, 'anulado', '2024-10-28'),
(9, 2, 2, 0, 'anulado', '2024-10-28'),
(10, 2, 2, 4000, 'cobrado', '2024-10-28'),
(11, 2, 2, 0, 'pendiente', '2024-10-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cod_barra` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medida` varchar(50) NOT NULL,
  `precio_costo` float NOT NULL DEFAULT 0,
  `precio_menor` float NOT NULL DEFAULT 0,
  `precio_mayor` float NOT NULL DEFAULT 0,
  `cantidad_mayor` int(11) NOT NULL DEFAULT 0,
  `id_comercio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `producto`, `cod_barra`, `cantidad`, `medida`, `precio_costo`, `precio_menor`, `precio_mayor`, `cantidad_mayor`, `id_comercio`, `id_usuario`, `fecha`) VALUES
(8, 'quimico', '1234567', 120, '', 1200, 2300, 1900, 10, 1, 1, '2024-10-14'),
(9, 'quimico2', '234441', 26, '', 700, 1000, 900, 10, 1, 1, '2024-10-23'),
(11, 'Cloro Hipoclorito', '100', 15000, '', 50, 150, 120, 5, 2, 2, '2024-10-24'),
(12, 'Cloro Puro Pileta', '101', 10000, '', 0, 300, 400, 5, 2, 2, '2024-10-24'),
(13, 'Lavandina Ropa Color', '102', 10000, '', 0, 450, 400, 5, 2, 2, '2024-10-24'),
(14, 'Lavandina Gel', '103', 20000, '', 0, 500, 440, 5, 2, 2, '2024-10-24'),
(15, 'Jabon de ropa Tipo Ace Premium ', '104', 10000, '', 0, 700, 640, 5, 2, 2, '2024-10-24'),
(16, 'Jabon de ropa Tipo Skip Ultra ', '105', 10000, '', 0, 500, 450, 5, 2, 2, '2024-10-24'),
(17, 'Jabon de ropa Tipo Ariel Ultra', '106', 10000, '', 0, 550, 480, 5, 2, 2, '2024-10-24'),
(18, 'Jabon de ropa Tipo Skip comun', '107', 10000, '', 0, 550, 480, 5, 2, 2, '2024-10-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_comercio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `tipo_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_comercio`, `nombre`, `usuario`, `clave`, `imagen`, `tipo_usuario`, `fecha`) VALUES
(1, 1, 'Admin', 'admin', '$2y$10$PT.ENEw//4NSyGx7dWFs7e2OAdmSAvnTMzLF5X8Omv8zIAUmhiOSq', 'img/usuarios/1.jpg', 1, '2024-10-13'),
(2, 2, '11-12', 'bizzio', '$2y$10$nWXEuNLilo6jLabFvUUSc.9z5MftU2vZaGZ.bLDxK3UGvbeZuqI2W', 'img/usuarios/1112.png', 2, '2024-10-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_comercio` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `comercios`
--
ALTER TABLE `comercios`
  ADD PRIMARY KEY (`id_comercio`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comercios`
--
ALTER TABLE `comercios`
  MODIFY `id_comercio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
