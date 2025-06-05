-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2025 a las 15:40:54
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `guardiashop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Blusas', 'Prendas superiores femeninas, variedad de estilos y tejidos.'),
(2, 'Shorts', 'Pantalones cortos para hombre y mujer, casuales.'),
(3, 'Gorras', 'Accesorios para la cabeza con visera, protección y estilo.'),
(4, 'Camisas', 'Prendas superiores con cuello y botones, formales y casuales.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color_productos`
--

CREATE TABLE `color_productos` (
  `id_color` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `codigo_hexadecimal` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `color_productos`
--

INSERT INTO `color_productos` (`id_color`, `nombre`, `codigo_hexadecimal`) VALUES
(1, 'Negro', '#000000'),
(2, 'Blanco', '#FFFFFF'),
(3, 'Caqui', '#C3B091'),
(4, 'Rosado', '#FFC0CB'),
(5, 'Azul Marino', '#000080'),
(6, 'Verde', '#008000'),
(7, 'Marrón', '#8f4c25'),
(8, 'Café', '#694c2f'),
(9, 'Beige', '#F5F5DC'),
(10, 'Crema', '#FFFDD0'),
(11, 'Gris', '#808080');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `Número_de_factura` varchar(50) DEFAULT NULL,
  `total_compra` decimal(10,2) DEFAULT NULL,
  `estado_compra` enum('solicitada','confirmada','enviada','recibida_parcial','recibida_completa','cancelada') NOT NULL DEFAULT 'solicitada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_proveedor`, `fecha_compra`, `Número_de_factura`, `total_compra`, `estado_compra`) VALUES
(1, 1, '2025-06-04 08:07:30', 'COMPRA-683F9C5230C27', 1250000.00, 'recibida_completa'),
(2, 1, '2025-06-04 08:26:27', 'COMPRA-683FA0C386BFC', 3900000.00, 'recibida_completa'),
(3, 4, '2025-06-04 08:27:00', 'COMPRA-683FA0E452656', 3100000.00, 'recibida_completa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactanos`
--

CREATE TABLE `contactanos` (
  `id_contacto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `correo` varchar(50) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('Nuevo','Leído','Respondido','Cerrado') NOT NULL,
  `respuesta_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `copias_seguridad`
--

CREATE TABLE `copias_seguridad` (
  `id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `tamano` int(11) NOT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `copias_seguridad`
--

INSERT INTO `copias_seguridad` (`id`, `nombre_archivo`, `fecha`, `tamano`, `frecuencia`, `observaciones`) VALUES
(0, 'backup_guardiashop_2025-06-02_20-10-54.sql', '2025-06-02 20:10:54', 50255, 'manual', NULL),
(6, 'backup_guardiashop_2025-06-01_22-02-28.sql', '2025-06-01 22:02:29', 49321, 'manual', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_detalles_productos` int(11) NOT NULL,
  `cantidad_comprada` int(11) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad_comprada` * `costo_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compra`
--

INSERT INTO `detalles_compra` (`id_detalle_compra`, `id_compra`, `id_detalles_productos`, `cantidad_comprada`, `costo_unitario`) VALUES
(1, 1, 5, 10, 125000.00),
(2, 2, 13, 30, 130000.00),
(3, 3, 85, 20, 155000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura_f`
--

CREATE TABLE `detalles_factura_f` (
  `id_detalle_factura_f` int(11) NOT NULL,
  `id_factura_f` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_factura_f`
--

INSERT INTO `detalles_factura_f` (`id_detalle_factura_f`, `id_factura_f`, `id_producto`, `cantidad`, `precio`, `subtotal`) VALUES
(1, 3, 3, 10, 145000.00, 1450000.00),
(2, 4, 3, 6, 145000.00, 870000.00),
(3, 5, 3, 1, 155000.00, 155000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id_detalles_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_detalles_productos` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id_detalles_pedido`, `id_pedido`, `id_detalles_productos`, `cantidad`, `precio_unitario`) VALUES
(105, 64, 38, 3, 138000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_productos`
--

CREATE TABLE `detalles_productos` (
  `id_detalles_productos` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `id_tallas` int(11) NOT NULL,
  `precio_producto` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_minimo_alerta` int(11) DEFAULT 5 COMMENT 'Umbral para alerta de bajo stock (NULL si no aplica)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_productos`
--

INSERT INTO `detalles_productos` (`id_detalles_productos`, `id_producto`, `id_color`, `id_tallas`, `precio_producto`, `stock`, `stock_minimo_alerta`) VALUES
(1, 1, 1, 1, 125000.00, 15, 5),
(2, 1, 1, 2, 125000.00, 20, 5),
(3, 1, 1, 3, 135000.00, 18, 5),
(4, 1, 1, 4, 135000.00, 10, 5),
(5, 1, 2, 1, 125000.00, 28, 5),
(6, 1, 2, 2, 125000.00, 22, 5),
(7, 1, 2, 3, 135000.00, 18, 5),
(8, 1, 2, 4, 135000.00, 12, 5),
(9, 1, 3, 1, 125000.00, 12, 5),
(10, 1, 3, 2, 125000.00, 15, 5),
(11, 1, 3, 3, 135000.00, 14, 5),
(12, 1, 3, 4, 135000.00, 8, 5),
(13, 2, 2, 1, 130000.00, 50, 5),
(14, 2, 2, 2, 130000.00, 25, 5),
(15, 2, 2, 3, 140000.00, 22, 5),
(16, 2, 2, 4, 140000.00, 15, 5),
(17, 2, 1, 1, 130000.00, 17, 5),
(18, 2, 1, 2, 130000.00, 20, 5),
(19, 2, 1, 3, 140000.00, 17, 5),
(20, 2, 1, 4, 140000.00, 11, 5),
(21, 2, 4, 1, 130000.00, 14, 5),
(22, 2, 4, 2, 130000.00, 18, 5),
(23, 2, 4, 3, 140000.00, 15, 5),
(24, 2, 4, 4, 140000.00, 9, 5),
(25, 3, 2, 1, 145000.00, 20, 5),
(26, 3, 2, 2, 145000.00, 19, 5),
(27, 3, 2, 3, 155000.00, 16, 5),
(28, 3, 2, 4, 155000.00, 10, 5),
(29, 3, 1, 1, 145000.00, 15, 5),
(30, 3, 1, 2, 145000.00, 0, 5),
(31, 3, 1, 3, 155000.00, 15, 5),
(32, 3, 1, 4, 155000.00, 9, 5),
(33, 3, 3, 1, 145000.00, 11, 5),
(34, 3, 3, 2, 145000.00, 13, 5),
(35, 3, 3, 3, 155000.00, 11, 5),
(36, 3, 3, 4, 155000.00, 7, 5),
(37, 4, 1, 1, 138000.00, 18, 5),
(38, 4, 1, 2, 138000.00, 18, 5),
(39, 4, 1, 3, 148000.00, 19, 5),
(40, 4, 1, 4, 148000.00, 12, 5),
(41, 4, 2, 1, 138000.00, 20, 5),
(42, 4, 2, 2, 138000.00, 2, 5),
(43, 4, 2, 3, 148000.00, 19, 5),
(44, 4, 2, 4, 148000.00, 14, 5),
(45, 4, 5, 1, 138000.00, 13, 5),
(46, 4, 5, 2, 138000.00, 16, 5),
(47, 4, 5, 3, 148000.00, 10, 5),
(48, 4, 5, 4, 148000.00, 8, 5),
(49, 5, 1, 5, 75000.00, 30, 5),
(50, 5, 2, 5, 75000.00, 35, 5),
(51, 5, 6, 5, 75000.00, 25, 5),
(52, 6, 1, 5, 80000.00, 28, 5),
(53, 6, 2, 5, 80000.00, 33, 5),
(54, 6, 9, 5, 80000.00, 22, 5),
(55, 7, 1, 5, 70000.00, 32, 5),
(56, 7, 2, 5, 70000.00, 38, 5),
(57, 7, 3, 5, 70000.00, 26, 5),
(58, 8, 1, 5, 85000.00, 29, 5),
(59, 8, 2, 5, 85000.00, 34, 5),
(60, 8, 8, 5, 85000.00, 24, 5),
(61, 9, 2, 1, 150000.00, 15, 5),
(62, 9, 2, 2, 150000.00, 20, 5),
(63, 9, 2, 3, 160000.00, 18, 5),
(64, 9, 2, 4, 160000.00, 10, 5),
(65, 9, 1, 1, 150000.00, 18, 5),
(66, 9, 1, 2, 150000.00, 22, 5),
(67, 9, 1, 3, 160000.00, 20, 5),
(68, 9, 1, 4, 160000.00, 12, 5),
(69, 9, 3, 1, 150000.00, 12, 5),
(70, 9, 3, 2, 150000.00, 15, 5),
(71, 9, 3, 3, 160000.00, 14, 5),
(72, 9, 3, 4, 160000.00, 8, 5),
(73, 10, 3, 1, 165000.00, 14, 5),
(74, 10, 3, 2, 165000.00, 17, 5),
(75, 10, 3, 3, 175000.00, 15, 5),
(76, 10, 3, 4, 175000.00, 9, 5),
(77, 10, 2, 1, 165000.00, 16, 5),
(78, 10, 2, 2, 165000.00, 20, 5),
(79, 10, 2, 3, 175000.00, 18, 5),
(80, 10, 2, 4, 175000.00, 11, 5),
(81, 10, 1, 1, 165000.00, 17, 5),
(82, 10, 1, 2, 165000.00, 21, 5),
(83, 10, 1, 3, 175000.00, 19, 5),
(84, 10, 1, 4, 175000.00, 13, 5),
(85, 11, 7, 1, 155000.00, 31, 5),
(86, 11, 7, 2, 155000.00, 14, 5),
(87, 11, 7, 3, 165000.00, 12, 5),
(88, 11, 7, 4, 165000.00, 7, 5),
(89, 11, 1, 1, 155000.00, 19, 5),
(90, 11, 1, 2, 155000.00, 23, 5),
(91, 11, 1, 3, 165000.00, 20, 5),
(92, 11, 1, 4, 165000.00, 14, 5),
(93, 11, 2, 1, 155000.00, 16, 5),
(94, 11, 2, 2, 155000.00, 20, 5),
(95, 11, 2, 3, 165000.00, 17, 5),
(96, 11, 2, 4, 165000.00, 10, 5),
(97, 12, 8, 1, 170000.00, 13, 5),
(98, 12, 8, 2, 170000.00, 16, 5),
(99, 12, 8, 3, 180000.00, 14, 5),
(100, 12, 8, 4, 180000.00, 8, 5),
(101, 12, 1, 1, 170000.00, 17, 5),
(102, 12, 1, 2, 170000.00, 21, 5),
(103, 12, 1, 3, 180000.00, 19, 5),
(104, 12, 1, 4, 180000.00, 12, 5),
(105, 12, 2, 1, 170000.00, 18, 5),
(106, 12, 2, 2, 170000.00, 22, 5),
(107, 12, 2, 3, 180000.00, 20, 5),
(108, 12, 2, 4, 180000.00, 13, 5),
(109, 13, 1, 1, 115000.00, 20, 5),
(110, 13, 1, 2, 115000.00, 25, 5),
(111, 13, 1, 3, 125000.00, 22, 5),
(112, 13, 1, 4, 125000.00, 15, 5),
(113, 13, 3, 1, 115000.00, 15, 5),
(114, 13, 3, 2, 115000.00, 18, 5),
(115, 13, 3, 3, 125000.00, 16, 5),
(116, 13, 3, 4, 125000.00, 10, 5),
(117, 13, 5, 1, 115000.00, 12, 5),
(118, 13, 5, 2, 115000.00, 15, 5),
(119, 13, 5, 3, 125000.00, 13, 5),
(120, 13, 5, 4, 125000.00, 8, 5),
(121, 14, 1, 1, 120000.00, 22, 5),
(122, 14, 1, 2, 120000.00, 28, 5),
(123, 14, 1, 3, 130000.00, 25, 5),
(124, 14, 1, 4, 130000.00, 18, 5),
(125, 14, 3, 1, 120000.00, 17, 5),
(126, 14, 3, 2, 120000.00, 20, 5),
(127, 14, 3, 3, 130000.00, 18, 5),
(128, 14, 3, 4, 130000.00, 12, 5),
(129, 14, 2, 1, 120000.00, 18, 5),
(130, 14, 2, 2, 120000.00, 22, 5),
(131, 14, 2, 3, 130000.00, 19, 5),
(132, 14, 2, 4, 130000.00, 13, 5),
(133, 15, 1, 1, 128000.00, 19, 5),
(134, 15, 1, 2, 128000.00, 24, 5),
(135, 15, 1, 3, 138000.00, 21, 5),
(136, 15, 1, 4, 138000.00, 14, 5),
(137, 15, 3, 1, 128000.00, 14, 5),
(138, 15, 3, 2, 128000.00, 17, 5),
(139, 15, 3, 3, 138000.00, 15, 5),
(140, 15, 3, 4, 138000.00, 9, 5),
(141, 15, 5, 1, 128000.00, 16, 5),
(142, 15, 5, 2, 128000.00, 19, 5),
(143, 15, 5, 3, 138000.00, 17, 5),
(144, 15, 5, 4, 138000.00, 11, 5),
(145, 16, 1, 1, 140000.00, 21, 5),
(146, 16, 1, 2, 140000.00, 26, 5),
(147, 16, 1, 3, 150000.00, 23, 5),
(148, 16, 1, 4, 150000.00, 16, 5),
(149, 16, 3, 1, 140000.00, 16, 5),
(150, 16, 3, 2, 140000.00, 19, 5),
(151, 16, 3, 3, 150000.00, 17, 5),
(152, 16, 3, 4, 150000.00, 11, 5),
(153, 16, 5, 1, 140000.00, 18, 5),
(154, 16, 5, 2, 140000.00, 21, 5),
(155, 16, 5, 3, 150000.00, 19, 5),
(156, 16, 5, 4, 150000.00, 13, 5),
(157, 17, 1, 1, 105000.00, 15, 5),
(158, 17, 1, 2, 105000.00, 20, 5),
(159, 17, 1, 3, 115000.00, 18, 5),
(160, 17, 1, 4, 115000.00, 10, 5),
(161, 17, 2, 1, 105000.00, 18, 5),
(162, 17, 2, 2, 105000.00, 22, 5),
(163, 17, 2, 3, 115000.00, 20, 5),
(164, 17, 2, 4, 115000.00, 12, 5),
(165, 17, 6, 1, 105000.00, 12, 5),
(166, 17, 6, 2, 105000.00, 15, 5),
(167, 17, 6, 3, 115000.00, 14, 5),
(168, 17, 6, 4, 115000.00, 8, 5),
(169, 18, 2, 1, 110000.00, 20, 5),
(170, 18, 2, 2, 110000.00, 25, 5),
(171, 18, 2, 3, 120000.00, 22, 5),
(172, 18, 2, 4, 120000.00, 15, 5),
(173, 18, 1, 1, 110000.00, 17, 5),
(174, 18, 1, 2, 110000.00, 20, 5),
(175, 18, 1, 3, 120000.00, 18, 5),
(176, 18, 1, 4, 120000.00, 11, 5),
(177, 18, 8, 1, 110000.00, 10, 5),
(178, 18, 8, 2, 110000.00, 13, 5),
(179, 18, 8, 3, 120000.00, 11, 5),
(180, 18, 8, 4, 120000.00, 7, 5),
(181, 19, 2, 1, 130000.00, 16, 5),
(182, 19, 2, 2, 130000.00, 19, 5),
(183, 19, 2, 3, 140000.00, 16, 5),
(184, 19, 2, 4, 140000.00, 10, 5),
(185, 19, 1, 1, 130000.00, 15, 5),
(186, 19, 1, 2, 130000.00, 18, 5),
(187, 19, 1, 3, 140000.00, 15, 5),
(188, 19, 1, 4, 140000.00, 9, 5),
(189, 19, 3, 1, 130000.00, 11, 5),
(190, 19, 3, 2, 130000.00, 13, 5),
(191, 19, 3, 3, 140000.00, 12, 5),
(192, 19, 3, 4, 140000.00, 7, 5),
(193, 20, 2, 1, 98000.00, 22, 5),
(194, 20, 2, 2, 98000.00, 28, 5),
(195, 20, 2, 3, 108000.00, 25, 5),
(196, 20, 2, 4, 108000.00, 18, 5),
(197, 20, 1, 1, 98000.00, 20, 5),
(198, 20, 1, 2, 98000.00, 25, 5),
(199, 20, 1, 3, 108000.00, 23, 5),
(200, 20, 1, 4, 108000.00, 15, 5),
(201, 20, 3, 1, 98000.00, 18, 5),
(202, 20, 3, 2, 98000.00, 21, 5),
(203, 20, 3, 3, 108000.00, 19, 5),
(204, 20, 3, 4, 108000.00, 12, 5),
(205, 21, 2, 1, 160000.00, 12, 5),
(206, 21, 2, 2, 160000.00, 15, 5),
(207, 21, 2, 3, 170000.00, 13, 5),
(208, 21, 2, 4, 170000.00, 8, 5),
(209, 21, 1, 1, 160000.00, 14, 5),
(210, 21, 1, 2, 160000.00, 18, 5),
(211, 21, 1, 3, 170000.00, 16, 5),
(212, 21, 1, 4, 170000.00, 10, 5),
(213, 21, 3, 1, 160000.00, 9, 5),
(214, 21, 3, 2, 160000.00, 11, 5),
(215, 21, 3, 3, 170000.00, 10, 5),
(216, 21, 3, 4, 170000.00, 6, 5),
(217, 22, 4, 1, 140000.00, 11, 5),
(218, 22, 4, 2, 140000.00, 14, 5),
(219, 22, 4, 3, 150000.00, 12, 5),
(220, 22, 4, 4, 150000.00, 7, 5),
(221, 22, 2, 1, 140000.00, 16, 5),
(222, 22, 2, 2, 140000.00, 20, 5),
(223, 22, 2, 3, 150000.00, 17, 5),
(224, 22, 2, 4, 150000.00, 10, 5),
(225, 22, 1, 1, 140000.00, 15, 5),
(226, 22, 1, 2, 140000.00, 18, 5),
(227, 22, 1, 3, 150000.00, 15, 5),
(228, 22, 1, 4, 150000.00, 9, 5),
(229, 23, 8, 1, 148000.00, 13, 5),
(230, 23, 8, 2, 148000.00, 16, 5),
(231, 23, 8, 3, 158000.00, 14, 5),
(232, 23, 8, 4, 158000.00, 8, 5),
(233, 23, 2, 1, 148000.00, 17, 5),
(234, 23, 2, 2, 148000.00, 21, 5),
(235, 23, 2, 3, 158000.00, 19, 5),
(236, 23, 2, 4, 158000.00, 12, 5),
(237, 23, 1, 1, 148000.00, 16, 5),
(238, 23, 1, 2, 148000.00, 20, 5),
(239, 23, 1, 3, 158000.00, 18, 5),
(240, 23, 1, 4, 158000.00, 11, 5),
(241, 24, 2, 1, 155000.00, 14, 5),
(242, 24, 2, 2, 155000.00, 17, 5),
(243, 24, 2, 3, 165000.00, 15, 5),
(244, 24, 2, 4, 165000.00, 9, 5),
(245, 24, 1, 1, 155000.00, 18, 5),
(246, 24, 1, 2, 155000.00, 22, 5),
(247, 24, 1, 3, 165000.00, 20, 5),
(248, 24, 1, 4, 165000.00, 13, 5),
(249, 24, 3, 1, 155000.00, 10, 5),
(250, 24, 3, 2, 155000.00, 13, 5),
(251, 24, 3, 3, 165000.00, 10, 5),
(252, 24, 3, 4, 165000.00, 7, 5),
(285, 25, 2, 5, 85000.00, 14, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `direccion` text NOT NULL,
  `direccion_adiccional` text NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `codigo_postal` text NOT NULL,
  `telefono` int(20) NOT NULL,
  `identificacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_direccion`, `usuario_id`, `pais`, `direccion`, `direccion_adiccional`, `ciudad`, `departamento`, `codigo_postal`, `telefono`, `identificacion`) VALUES
(26, 35, 'colombia', 'buenos aires parte baja', 'san judas despues de la iglesia ', 'quibdo', 'choco', '2700001', 2147483647, '10779222333');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_venta`
--

CREATE TABLE `facturas_venta` (
  `id_factura` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL COMMENT 'FK al pedido que se está facturando',
  `numero_factura` varchar(50) NOT NULL COMMENT 'Número legal y secuencial de la factura (Generado por la aplicación)',
  `fecha_emision` date NOT NULL COMMENT 'Fecha en que se emite la factura',
  `fecha_vencimiento` date DEFAULT NULL COMMENT 'Fecha límite de pago (si aplica)',
  `cliente_nombre_completo` varchar(150) DEFAULT NULL COMMENT 'Nombre del cliente en la factura',
  `cliente_direccion_fiscal` text DEFAULT NULL COMMENT 'Dirección fiscal del cliente en la factura',
  `cliente_identificacion_fiscal` varchar(50) DEFAULT NULL COMMENT 'NIF/CIF/RUC/NIT del cliente en la factura',
  `subtotal_base` decimal(12,2) NOT NULL COMMENT 'Suma de precios de productos ANTES de impuestos',
  `total_impuestos` decimal(12,2) NOT NULL COMMENT 'Suma de todos los impuestos (IVA, etc.)',
  `total_factura` decimal(12,2) NOT NULL COMMENT 'Monto final a pagar (subtotal_base + total_impuestos)',
  `estado_factura` enum('Emitida','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Emitida' COMMENT 'Estado actual de la factura',
  `metodo_pago_registrado` varchar(50) DEFAULT NULL COMMENT 'Método con el que se pagó (si aplica)',
  `notas_factura` text DEFAULT NULL COMMENT 'Notas o comentarios adicionales en la factura',
  `fecha_creacion_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Almacena los datos maestros de las facturas de venta emitidas';

--
-- Volcado de datos para la tabla `facturas_venta`
--

INSERT INTO `facturas_venta` (`id_factura`, `id_pedido`, `numero_factura`, `fecha_emision`, `fecha_vencimiento`, `cliente_nombre_completo`, `cliente_direccion_fiscal`, `cliente_identificacion_fiscal`, `subtotal_base`, `total_impuestos`, `total_factura`, `estado_factura`, `metodo_pago_registrado`, `notas_factura`, `fecha_creacion_registro`) VALUES
(39, 64, 'FAC-000064', '2025-06-04', '2025-07-04', 'Cliente', 'buenos aires parte baja, san judas despues de la iglesia ', '10779222333', 414000.00, 0.00, 414000.00, 'Pagada', 'paypal', '', '2025-06-04 13:05:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta_f`
--

CREATE TABLE `factura_venta_f` (
  `id_factura` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `cliente_nombre_completo` varchar(255) NOT NULL,
  `cliente_direccion_fiscal` text DEFAULT NULL,
  `cliente_identificacion_fiscal` varchar(100) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `subtotal_base` decimal(10,2) NOT NULL,
  `total_impuestos` decimal(10,2) NOT NULL,
  `total_factura` decimal(10,2) NOT NULL,
  `estado_factura` varchar(50) DEFAULT NULL,
  `metodo_pago_registrado` varchar(100) DEFAULT NULL,
  `notas_factura` text DEFAULT NULL,
  `fecha_creacion_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura_venta_f`
--

INSERT INTO `factura_venta_f` (`id_factura`, `numero_factura`, `fecha_emision`, `fecha_vencimiento`, `cliente_nombre_completo`, `cliente_direccion_fiscal`, `cliente_identificacion_fiscal`, `correo`, `subtotal_base`, `total_impuestos`, `total_factura`, `estado_factura`, `metodo_pago_registrado`, `notas_factura`, `fecha_creacion_registro`) VALUES
(3, 'FAC-683f85704351d', '2025-06-04', '2025-07-04', 'luis', 'buenos aires', '2233333', 'david@gmial.com', 1450000.00, 231512.61, 1450000.00, 'Pagada', 'Efectivo', '', '2025-06-04 01:29:52'),
(4, 'FAC-683f86385db5a', '2025-06-04', '2025-07-04', 'santiago', 'cabi', '34667', 'santi@gmail.com', 870000.00, 138907.56, 870000.00, 'Pagada', 'Efectivo', '', '2025-06-04 01:33:12'),
(5, 'FAC-684046a722407', '2025-06-04', '2025-07-04', 'lerhy', 'buenos aires', '2233333', 'lerhyalexander711@gmail.com', 155000.00, 24747.90, 155000.00, 'Pagada', 'Efectivo', '', '2025-06-04 15:14:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(11) NOT NULL,
  `id_detalles_productos` int(11) NOT NULL COMMENT 'FK a la variante específica (producto+color+talla)',
  `stock_inicial` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('Compra','Venta','Devolucion_Cliente','Devolucion_Proveedor','Ajuste_Manual_Positivo','Ajuste_Manual_Negativo','Inicial') NOT NULL COMMENT 'Razón del cambio de stock',
  `cantidad_cambio` int(11) NOT NULL COMMENT 'Positivo para entradas (+), Negativo para salidas (-)',
  `stock_resultante` int(11) DEFAULT NULL COMMENT 'Opcional: Stock total de la variante DESPUÉS de este movimiento',
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Momento exacto del movimiento',
  `referencia_origen` varchar(50) DEFAULT NULL COMMENT 'ID Origen: id_detalle_compra, id_detalles_pedido, id_devolucion, etc.',
  `id_admin` int(11) DEFAULT NULL COMMENT 'FK a Admin: Quién hizo el ajuste manual (si aplica)',
  `costo_unitario_movimiento` decimal(10,2) DEFAULT NULL COMMENT 'Costo unitario asociado a ESTE lote (importante para FIFO/LIFO/Promedio)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Historial detallado de todos los cambios de inventario';

--
-- Volcado de datos para la tabla `movimientos_inventario`
--

INSERT INTO `movimientos_inventario` (`id_movimiento`, `id_detalles_productos`, `stock_inicial`, `tipo_movimiento`, `cantidad_cambio`, `stock_resultante`, `fecha_hora`, `referencia_origen`, `id_admin`, `costo_unitario_movimiento`) VALUES
(3, 23, 16, 'Venta', -1, 15, '2025-06-03 18:00:48', '61', NULL, NULL),
(4, 47, 12, 'Venta', -2, 10, '2025-06-03 21:20:24', '62', NULL, NULL),
(5, 25, 6, 'Venta', -10, -4, '2025-06-03 23:29:52', '3', NULL, NULL),
(6, 25, 0, 'Venta', -6, -6, '2025-06-03 23:33:12', '4', NULL, NULL),
(7, 25, 0, 'Compra', 20, 20, '2025-06-04 00:50:44', 'Compra manual', NULL, 145000.00),
(8, 5, 18, 'Compra', 10, 28, '2025-06-04 01:07:30', 'COMPRA-683F9C5230C27', NULL, 125000.00),
(9, 13, 20, 'Compra', 30, 50, '2025-06-04 01:26:27', 'COMPRA-683FA0C386BFC', NULL, 130000.00),
(10, 85, 11, 'Compra', 20, 31, '2025-06-04 01:27:00', 'COMPRA-683FA0E452656', NULL, 155000.00),
(11, 38, 21, 'Venta', -3, 18, '2025-06-04 13:05:41', '64', NULL, NULL),
(12, 27, 16, 'Venta', -1, 15, '2025-06-04 13:14:15', '5', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo_pago` enum('efectivo','tarjeta','paypal','transferencia') NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `estado_pago` enum('pendiente','completado','fallido') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `banco` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `fecha_pago`, `metodo_pago`, `id_pedido`, `estado_pago`, `monto`, `banco`) VALUES
(52, '2025-06-04 13:05:40', 'paypal', 64, 'completado', 414000.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_orden` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `usuario_id`, `fecha_orden`, `total`, `estado`) VALUES
(64, 35, '2025-06-04 13:05:40', 414000.00, 'confirmado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_historial`
--

CREATE TABLE `pedido_historial` (
  `id_historial` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') DEFAULT NULL COMMENT 'Estado antes del cambio (NULL si es el estado inicial del pedido)',
  `estado_nuevo` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') DEFAULT NULL COMMENT 'Estado al que cambió el pedido',
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_admin_cambio` int(11) DEFAULT NULL COMMENT 'FK opcional al admin que realizó el cambio (NULL si fue automático o por el cliente)',
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Historial de los cambios de estado por los que pasa un pedido';

--
-- Volcado de datos para la tabla `pedido_historial`
--

INSERT INTO `pedido_historial` (`id_historial`, `id_pedido`, `estado_anterior`, `estado_nuevo`, `fecha_cambio`, `id_admin_cambio`, `notas`) VALUES
(84, 64, NULL, 'pendiente', '2025-06-04 13:05:28', NULL, NULL),
(85, 64, 'pendiente', 'confirmado', '2025-06-04 13:05:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_sesion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `id_sesion`, `nombre`, `descripcion`, `codigo`, `fecha_registro`) VALUES
(1, 1, 2, 'Blusa “Encanto Volante”', 'Blusa Encanto Volante para mujer.', 'BL001', '2025-06-02 18:19:44'),
(2, 1, 2, 'Blusa “Asimétrica Chic”', 'Blusa Asimétrica Chic para mujer.', 'BL002', '2025-06-02 18:19:45'),
(3, 1, 2, 'Blusa “Línea Elegante”', 'Blusa Línea Elegante para mujer.', 'BL003', '2025-06-02 18:19:45'),
(4, 1, 2, 'Blusa “Corte Moderno”', 'Blusa Corte Moderno para mujer.', 'BL004', '2025-06-02 18:19:45'),
(5, 3, 3, 'Gorra “Shadow City”', 'Gorra Shadow City, estilo urbano unisex.', 'GO001', '2025-06-02 18:19:45'),
(6, 3, 3, 'Gorra “Estilo Vintage”', 'Gorra Estilo Vintage, look retro unisex.', 'GO002', '2025-06-02 18:19:45'),
(7, 3, 3, 'Gorra “Mood Rebel”', 'Gorra Mood Rebel, actitud desenfadada unisex.', 'GO003', '2025-06-02 18:19:45'),
(8, 3, 3, 'Gorra “Línea Clara”', 'Gorra Línea Clara, diseño minimalista unisex.', 'GO004', '2025-06-02 18:19:45'),
(9, 4, 1, 'Camisa “Botón Relax”', 'Camisa Botón Relax para hombre, comodidad y estilo.', 'CA001', '2025-06-02 18:19:45'),
(10, 4, 1, 'Camisa “Textura Urbana”', 'Camisa Textura Urbana para hombre, look moderno.', 'CA002', '2025-06-02 18:19:45'),
(11, 4, 1, 'Camisa “Estilo Casual”', 'Camisa Estilo Casual para hombre, versátil y cómoda.', 'CA003', '2025-06-02 18:19:45'),
(12, 4, 1, 'Camisa “Textura Serena”', 'Camisa Textura Serena para hombre, elegancia relajada.', 'CA004', '2025-06-02 18:19:45'),
(13, 2, 1, 'Short “Corte Ejecutivo”', 'Short Corte Ejecutivo para hombre, formalidad casual.', 'SH001', '2025-06-02 18:19:45'),
(14, 2, 1, 'Short “Smart Street”', 'Short Smart Street para hombre, estilo urbano inteligente.', 'SH002', '2025-06-02 18:19:45'),
(15, 2, 1, 'Short “Aventura Urbana”', 'Short Aventura Urbana para hombre, listo para la ciudad.', 'SH003', '2025-06-02 18:19:45'),
(16, 2, 1, 'Short “Cargo Urbano”', 'Short Cargo Urbano para hombre, funcionalidad y estilo.', 'SH004', '2025-06-02 18:19:45'),
(17, 2, 2, 'Shorts Estilo Noble', 'Shorts Estilo Noble para mujer, elegancia veraniega.', 'SH005', '2025-06-02 18:19:45'),
(18, 2, 2, 'Shorts Elegancia Suave', 'Shorts Elegancia Suave para mujer, comodidad sofisticada.', 'SH006', '2025-06-02 18:19:45'),
(19, 2, 2, 'Shorts Cintura Alta Belted', 'Shorts Cintura Alta Belted para mujer, define tu estilo.', 'SH007', '2025-06-02 18:19:45'),
(20, 2, 2, 'Shorts Casual Stretch', 'Shorts Casual Stretch para mujer, confort y movimiento.', 'SH008', '2025-06-02 18:19:45'),
(21, 4, 2, 'Camisa Esencia Satinada', 'Camisa Esencia Satinada para mujer, toque de lujo.', 'CM001', '2025-06-02 18:19:45'),
(22, 4, 2, 'Camisa Brisa Vintage', 'Camisa Brisa Vintage para mujer, encanto retro.', 'CM002', '2025-06-02 18:19:45'),
(23, 4, 2, 'Camisa Amanecer Casual', 'Camisa Amanecer Casual para mujer, estilo relajado.', 'CM003', '2025-06-02 18:19:45'),
(24, 4, 2, 'Camisa Estilo Profundo', 'Camisa Estilo Profundo para mujer, elegancia moderna.', 'CM004', '2025-06-02 18:19:45'),
(25, 3, 3, 'santiago', 'www', 'wwww', '2025-06-03 02:41:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagen`
--

CREATE TABLE `producto_imagen` (
  `id_imagen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_color_asociado` int(11) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_imagen`
--

INSERT INTO `producto_imagen` (`id_imagen`, `id_producto`, `id_color_asociado`, `imagen`) VALUES
(16, 1, 1, 'images/blusas/lu1.png'),
(17, 1, 2, 'images/blusas/lu1-1.png'),
(18, 1, 3, 'images/blusas/lu1-2.png'),
(19, 2, 2, 'images/blusas/lu2.png'),
(20, 2, 1, 'images/blusas/lu2-1.png'),
(21, 2, 4, 'images/blusas/lu2-2.png'),
(22, 3, 2, 'images/blusas/lu3.png'),
(23, 3, 1, 'images/blusas/lu3-1.png'),
(24, 3, 3, 'images/blusas/lu3-2.png'),
(25, 4, 1, 'images/blusas/lu4.png'),
(26, 4, 2, 'images/blusas/lu4-1.png'),
(27, 4, 5, 'images/blusas/lu4-2.png'),
(28, 21, 2, 'images/camisa_damas/cda1.png'),
(29, 21, 1, 'images/camisa_damas/cda1-1.png'),
(30, 21, 3, 'images/camisa_damas/cda1-2.png'),
(31, 22, 4, 'images/camisa_damas/cda2.png'),
(32, 22, 2, 'images/camisa_damas/cda2-1.png'),
(33, 22, 1, 'images/camisa_damas/cda2-2.png'),
(34, 23, 8, 'images/camisa_damas/cda3.png'),
(35, 23, 2, 'images/camisa_damas/cda3-1.png'),
(36, 23, 1, 'images/camisa_damas/cda3-2.png'),
(37, 24, 2, 'images/camisa_damas/cda4.png'),
(38, 24, 1, 'images/camisa_damas/cda4-1.png'),
(39, 24, 3, 'images/camisa_damas/cda4-2.png'),
(40, 9, 2, 'images/camisa_hombre/sa1.png'),
(41, 9, 1, 'images/camisa_hombre/sa1-1.png'),
(42, 9, 3, 'images/camisa_hombre/sa1-2.png'),
(43, 10, 3, 'images/camisa_hombre/sa2.png'),
(44, 10, 2, 'images/camisa_hombre/sa2-1.png'),
(45, 10, 1, 'images/camisa_hombre/sa2-2.png'),
(46, 11, 7, 'images/camisa_hombre/sa3.png'),
(47, 11, 1, 'images/camisa_hombre/sa3-1.png'),
(48, 11, 2, 'images/camisa_hombre/sa3-2.png'),
(49, 12, 8, 'images/camisa_hombre/sa4.png'),
(50, 12, 1, 'images/camisa_hombre/sa4-1.png'),
(51, 12, 2, 'images/camisa_hombre/sa4-2.png'),
(52, 5, 1, 'images/gorras/rra1.png'),
(53, 5, 2, 'images/gorras/rra1-1.png'),
(54, 5, 6, 'images/gorras/rra1-2.png'),
(55, 6, 1, 'images/gorras/rra2.png'),
(56, 6, 2, 'images/gorras/rra2-1.png'),
(57, 6, 9, 'images/gorras/rra2-2.png'),
(58, 7, 1, 'images/gorras/rra3.png'),
(59, 7, 2, 'images/gorras/rra3-1.png'),
(60, 7, 3, 'images/gorras/rra3-2.png'),
(61, 8, 1, 'images/gorras/rra4.png'),
(62, 8, 2, 'images/gorras/rra4-1.png'),
(63, 8, 8, 'images/gorras/rra4-2.png'),
(64, 17, 1, 'images/short_damas/sda1.png'),
(65, 17, 2, 'images/short_damas/sda1-1.png'),
(66, 17, 6, 'images/short_damas/sda1-2.png'),
(67, 18, 2, 'images/short_damas/sda2.png'),
(68, 18, 1, 'images/short_damas/sda2-1.png'),
(69, 18, 8, 'images/short_damas/sda2-2.png'),
(70, 19, 2, 'images/short_damas/sda3.png'),
(71, 19, 1, 'images/short_damas/sda3-1.png'),
(72, 19, 3, 'images/short_damas/sda3-2.png'),
(73, 20, 2, 'images/short_damas/sda4.png'),
(74, 20, 1, 'images/short_damas/sda4-1.png'),
(75, 20, 3, 'images/short_damas/sda4-2.png'),
(76, 13, 3, 'images/shorts_hombre/sh1.jpg'),
(77, 13, 1, 'images/shorts_hombre/sh1-1.jpg'),
(78, 13, 5, 'images/shorts_hombre/sh1-2.jpg'),
(79, 14, 1, 'images/shorts_hombre/sh2.jpg'),
(80, 14, 3, 'images/shorts_hombre/sh2-1.jpg'),
(81, 14, 2, 'images/shorts_hombre/sh2-2.jpg'),
(82, 15, 3, 'images/shorts_hombre/sh3.jpg'),
(83, 15, 1, 'images/shorts_hombre/sh3-1.jpg'),
(84, 15, 5, 'images/shorts_hombre/sh3-2.jpg'),
(85, 16, 3, 'images/shorts_hombre/sh4.jpg'),
(86, 16, 1, 'images/shorts_hombre/sh4-1.jpg'),
(87, 16, 5, 'images/shorts_hombre/sh4-2.jpg'),
(88, 25, 2, 'images/gorras/rra6.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_empresa` varchar(100) NOT NULL,
  `nombre_contacto` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `nit_o_ruc` varchar(50) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_empresa`, `nombre_contacto`, `correo`, `telefono`, `direccion`, `ciudad`, `pais`, `nit_o_ruc`, `fecha_registro`, `activo`) VALUES
(1, 'laloshop', 'laloshop-blusas', 'laloshop@gmail.com', '3298080376', 'barrio silencio', 'quibdo', 'colombia', '122334', '2025-06-04 05:00:00', 1),
(2, 'goshop', 'gosho-gorras', 'goshop@gmail.com', '3155148934', 'Minuto de Dios parte baja', 'quibdo', 'colombia', '122434', '2025-06-04 05:00:00', 1),
(3, 'shoper', 'shoper-shorts', 'shoper@gmail.com', '3247483647', 'san judas', 'quibdo', 'colombia', '14563', '2025-06-04 05:00:00', 1),
(4, 'camishop', 'camishop-camisas', 'camishop@gmail.com', '3298080376', 'jardin / orquidea parte baja', 'quibdo', 'colombia', '8221117', '2025-06-04 05:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id_sesion` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id_sesion`, `nombre`) VALUES
(1, 'Hombre'),
(2, 'Mujer'),
(3, 'Unisex');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla_productos`
--

CREATE TABLE `talla_productos` (
  `id_talla` int(11) NOT NULL,
  `nombre_talla` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talla_productos`
--

INSERT INTO `talla_productos` (`id_talla`, `nombre_talla`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'Única');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contraseña` varchar(280) NOT NULL,
  `fecha_de_cumpleaños` date NOT NULL,
  `proveedor_registro` varchar(50) NOT NULL,
  `proveedor_id` int(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rol` enum('cliente','admin','vendedor','super_admin') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `token_expira` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `correo`, `contraseña`, `fecha_de_cumpleaños`, `proveedor_registro`, `proveedor_id`, `fecha_registro`, `rol`, `foto_perfil`, `token`, `token_expira`) VALUES
(32, 'melany ', 'carmen', 'moreno', 'carmen', 'morenomelany10@gmail.com', '$2y$10$fXuONB69LLP490NDlX4UI.kB9Ae5ofcGo/LVOVAVBVFfw6JI8AICe', '2005-09-25', '', 0, '2025-06-04 12:22:33', 'admin', NULL, '', NULL),
(33, 'lerhy', 'alexander', 'jordan', 'serna', 'lerhyalexander711@gmail.com', '$2y$10$tdHB.23upCmG7Wu40/Vp9.ZZXYkgcTVY3voRpwrM/Ll91G/MOkKoi', '2003-11-12', '', 0, '2025-06-04 12:22:29', 'super_admin', NULL, '', NULL),
(34, 'yurleni', 'danessa', 'moreno', 'olaya', 'yurle@gmail.com', '$2y$10$OxHqso6iA1j0u0IOUi6mI.8WhEbw8cAvcpb/QVmdOwjQOJyYj2mYC', '2005-05-23', '', 0, '2025-06-04 12:22:17', 'vendedor', NULL, '', NULL),
(35, 'Melany', '', 'Moreno', '', 'melanymorenom@gmail.com', '', '0000-00-00', 'google', 2147483647, '2025-06-04 12:59:33', 'cliente', NULL, '', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `color_productos`
--
ALTER TABLE `color_productos`
  ADD PRIMARY KEY (`id_color`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `contactanos`
--
ALTER TABLE `contactanos`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `contactanos_ibfk_1` (`id_usuario`);

--
-- Indices de la tabla `copias_seguridad`
--
ALTER TABLE `copias_seguridad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_detalles_productos` (`id_detalles_productos`);

--
-- Indices de la tabla `detalles_factura_f`
--
ALTER TABLE `detalles_factura_f`
  ADD PRIMARY KEY (`id_detalle_factura_f`),
  ADD KEY `id_factura_f` (`id_factura_f`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id_detalles_pedido`),
  ADD KEY `id_detalles_productos` (`id_detalles_productos`),
  ADD KEY `detalles_pedido_ibfk_2` (`id_pedido`);

--
-- Indices de la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  ADD PRIMARY KEY (`id_detalles_productos`),
  ADD KEY `id_color` (`id_color`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_tallas` (`id_tallas`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `direccion_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `facturas_venta`
--
ALTER TABLE `facturas_venta`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `idx_id_pedido_factura` (`id_pedido`),
  ADD KEY `idx_fecha_emision` (`fecha_emision`),
  ADD KEY `idx_estado_factura` (`estado_factura`);

--
-- Indices de la tabla `factura_venta_f`
--
ALTER TABLE `factura_venta_f`
  ADD PRIMARY KEY (`id_factura`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `idx_id_detalles_productos` (`id_detalles_productos`),
  ADD KEY `idx_fecha_hora` (`fecha_hora`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `pago_ibfk_2` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `pedido_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `pedido_historial`
--
ALTER TABLE `pedido_historial`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `idx_id_pedido_fecha` (`id_pedido`,`fecha_cambio`),
  ADD KEY `id_admin_cambio` (`id_admin_cambio`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_sesion` (`id_sesion`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `producto_imagen`
--
ALTER TABLE `producto_imagen`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `producto_imagen_ibfk_1` (`id_producto`),
  ADD KEY `id_color_asociado` (`id_color_asociado`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `nit_o_ruc` (`nit_o_ruc`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesion`);

--
-- Indices de la tabla `talla_productos`
--
ALTER TABLE `talla_productos`
  ADD PRIMARY KEY (`id_talla`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `color_productos`
--
ALTER TABLE `color_productos`
  MODIFY `id_color` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `contactanos`
--
ALTER TABLE `contactanos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_factura_f`
--
ALTER TABLE `detalles_factura_f`
  MODIFY `id_detalle_factura_f` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detalles_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  MODIFY `id_detalles_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `facturas_venta`
--
ALTER TABLE `facturas_venta`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `factura_venta_f`
--
ALTER TABLE `factura_venta_f`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `pedido_historial`
--
ALTER TABLE `pedido_historial`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `producto_imagen`
--
ALTER TABLE `producto_imagen`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `talla_productos`
--
ALTER TABLE `talla_productos`
  MODIFY `id_talla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `contactanos`
--
ALTER TABLE `contactanos`
  ADD CONSTRAINT `contactanos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`);

--
-- Filtros para la tabla `detalles_factura_f`
--
ALTER TABLE `detalles_factura_f`
  ADD CONSTRAINT `detalles_factura_f_ibfk_1` FOREIGN KEY (`id_factura_f`) REFERENCES `factura_venta_f` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_factura_f_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`),
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  ADD CONSTRAINT `detalles_productos_ibfk_1` FOREIGN KEY (`id_color`) REFERENCES `color_productos` (`id_color`),
  ADD CONSTRAINT `detalles_productos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `detalles_productos_ibfk_3` FOREIGN KEY (`id_tallas`) REFERENCES `talla_productos` (`id_talla`);

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas_venta`
--
ALTER TABLE `facturas_venta`
  ADD CONSTRAINT `facturas_venta_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_historial`
--
ALTER TABLE `pedido_historial`
  ADD CONSTRAINT `pedido_historial_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `producto_imagen`
--
ALTER TABLE `producto_imagen`
  ADD CONSTRAINT `producto_imagen_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `producto_imagen_ibfk_2` FOREIGN KEY (`id_color_asociado`) REFERENCES `color_productos` (`id_color`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
