-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2024 a las 13:56:27
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `yocapos`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `telefono`, `id_comercio`) VALUES
(2, 'Guille', '264588', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comercios`
--

INSERT INTO `comercios` (`id_comercio`, `comercio`, `domicilio`, `telefono`, `correo`, `logo`) VALUES
(1, 'yoca', 'rawson', '264444', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_pedido`, `id_producto`, `cantidad`) VALUES
(1, 8, 1),
(2, 8, 1),
(3, 8, 1),
(4, 8, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_comercio`, `id_usuario`, `monto_pagado`, `estado`, `fecha`) VALUES
(1, 1, 1, 0, 'anulado', '2024-10-21'),
(2, 1, 1, 0.01, 'cobrado', '2024-10-21'),
(3, 1, 1, 0, 'anulado', '2024-10-21'),
(4, 1, 1, 0, 'cobrado', '2024-10-21');

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
  `precio` float NOT NULL DEFAULT 0,
  `id_comercio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `producto`, `cod_barra`, `cantidad`, `medida`, `precio`, `id_comercio`, `id_usuario`, `fecha`) VALUES
(8, 'quimico', '1234567', 13, '', 0, 1, 1, '2024-10-14');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_comercio`, `nombre`, `usuario`, `clave`, `imagen`, `tipo_usuario`, `fecha`) VALUES
(1, 1, 'Admin', 'admin', '$2y$10$PT.ENEw//4NSyGx7dWFs7e2OAdmSAvnTMzLF5X8Omv8zIAUmhiOSq', 'img/usuarios/1.jpg', 1, '2024-10-13');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comercios`
--
ALTER TABLE `comercios`
  MODIFY `id_comercio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
