-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-11-2025 a las 14:58:01
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `piso5_inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes`
--

CREATE TABLE `componentes` (
  `id_componente` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `tipo_componente` varchar(50) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `arquitectura` varchar(10) DEFAULT NULL,
  `estado` enum('Buen Funcionamiento','Operativo','Sin Funcionar') DEFAULT 'Buen Funcionamiento',
  `fecha_instalacion` year(4) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `velocidad` varchar(50) DEFAULT NULL,
  `capacidad` varchar(50) DEFAULT NULL,
  `consumo` varchar(50) DEFAULT NULL,
  `rgb_led` varchar(50) DEFAULT NULL,
  `ranuras_expansion` varchar(100) DEFAULT NULL,
  `conectores_alimentacion` varchar(100) DEFAULT NULL,
  `bios_uefi` varchar(100) DEFAULT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `voltajes_fuente` varchar(255) DEFAULT NULL,
  `nucleos` int(11) DEFAULT NULL,
  `socket` varchar(20) DEFAULT NULL,
  `soporte_memoria` varchar(20) DEFAULT NULL,
  `tipo_conector` varchar(50) DEFAULT NULL,
  `conectividad_soporte` varchar(100) DEFAULT NULL,
  `salidas_video` varchar(100) DEFAULT NULL,
  `soporte_apis` varchar(100) DEFAULT NULL,
  `fabricante_controlador` varchar(100) DEFAULT NULL,
  `modelo_red` varchar(100) DEFAULT NULL,
  `velocidad_transferencia` varchar(50) DEFAULT NULL,
  `tipo_conector_fisico` varchar(50) DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `drivers_sistema` varchar(100) DEFAULT NULL,
  `compatibilidad_sistema` enum('Sí','Parcialmente','No') DEFAULT NULL,
  `tipos_discos` varchar(150) DEFAULT NULL,
  `interfaz_conexion` varchar(50) DEFAULT NULL,
  `tipo_cooler` varchar(50) DEFAULT NULL,
  `consumo_electrico` varchar(50) DEFAULT NULL,
  `detalles` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `estadoElim` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `puertos_internos` varchar(150) DEFAULT NULL,
  `puertos_externos` varchar(150) DEFAULT NULL,
  `cantidad_slot_memoria` int(11) DEFAULT NULL,
  `slot_memoria` varchar(20) DEFAULT NULL,
  `memoria_maxima` varchar(25) DEFAULT NULL COMMENT 'Capacidad máxima de RAM que soporta la placa (en GB)',
  `frecuencias_memoria` varchar(255) DEFAULT NULL COMMENT 'Frecuencias soportadas, puede ser string o lista separada por comas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id_componente`, `id_equipo`, `tipo_componente`, `marca`, `modelo`, `arquitectura`, `estado`, `fecha_instalacion`, `ubicacion`, `tipo`, `frecuencia`, `velocidad`, `capacidad`, `consumo`, `rgb_led`, `ranuras_expansion`, `conectores_alimentacion`, `bios_uefi`, `potencia`, `voltajes_fuente`, `nucleos`, `socket`, `soporte_memoria`, `tipo_conector`, `conectividad_soporte`, `salidas_video`, `soporte_apis`, `fabricante_controlador`, `modelo_red`, `velocidad_transferencia`, `tipo_conector_fisico`, `mac_address`, `drivers_sistema`, `compatibilidad_sistema`, `tipos_discos`, `interfaz_conexion`, `tipo_cooler`, `consumo_electrico`, `detalles`, `estadoElim`, `puertos_internos`, `puertos_externos`, `cantidad_slot_memoria`, `slot_memoria`, `memoria_maxima`, `frecuencias_memoria`) VALUES
(33, 12, 'Procesador', 'intel', 'Corei7', 'x64', 'Operativo', NULL, NULL, NULL, '3.6GHz', NULL, NULL, '125w', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'LGA1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 12, 'Tarjeta Madre', 'Intel', '18E9', NULL, 'Buen Funcionamiento', '2018', NULL, 'DDR3', NULL, NULL, NULL, NULL, NULL, 'ISA, PCI, PCIe x1, PCIe x4, PCIe x8', '24 pines, 6 pines PCIe', 'UEFI', NULL, NULL, NULL, 'LGA 1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Inactivo', 'SATA, IDE (PATA)', 'HDMI, VGA, USB 2.0, USB 3.0/3.1 Gen1, USB 3.2 Gen2, Jack 3.5 mm, PS/2', 2, NULL, NULL, NULL),
(37, 12, 'Disco Duro', 'Wester Digital', NULL, NULL, 'Operativo', NULL, NULL, 'SSD', NULL, NULL, '512 GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 12, 'Tarjeta Grafica', 'AMD', 'AMD 750', NULL, 'Operativo', NULL, NULL, NULL, NULL, NULL, '2GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA, DVI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 12, 'Memoria RAM', 'ADATA', NULL, NULL, 'Operativo', NULL, NULL, 'DDR3', '3200MHZ', NULL, '8GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, 'Slot 2', NULL, NULL),
(40, 12, 'Memoria RAM', 'ADATA', NULL, NULL, 'Operativo', NULL, NULL, 'DDR3', '3200MHZ', NULL, '8GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(41, 12, 'Tarjeta Madre', 'Intel', '18E9', NULL, 'Buen Funcionamiento', '2015', NULL, 'DDR3', NULL, NULL, NULL, NULL, NULL, 'ISA, PCIe x1, PCIe x2, PCIe x4', 'ATX 24 pines, EPS 4 pines, SATA Power', 'UEFI', NULL, NULL, NULL, 'LGA 1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Activo', 'SATA, USB 2.0 header, USB 3.0 header', 'HDMI, DisplayPort, DVI, VGA, USB 2.0, USB 3.0/3.1 Gen1, USB 3.2 Gen2, RJ-45 Ethernet', 2, NULL, '8GB', '800, 1066'),
(42, 12, 'Memoria RAM', 'ADATA', NULL, NULL, 'Operativo', NULL, NULL, 'DDR3', '1066MHZ', NULL, '4GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(43, 12, 'Memoria RAM', 'Corsai', NULL, NULL, 'Operativo', NULL, NULL, 'DDR3', '1066MHZ', NULL, '4GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes_opcionales`
--

CREATE TABLE `componentes_opcionales` (
  `id_opcional` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `tipo_opcional` enum('Memoria Ram','Disco Duro','Fan Cooler','Tarjeta Grafica','Tarjeta de Red','Tarjeta WiFi','Tarjeta de Sonido') NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `capacidad` varchar(50) DEFAULT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `consumo` varchar(50) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `salidas_video` varchar(100) DEFAULT NULL,
  `salidas_audio` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `vrm` varchar(50) DEFAULT NULL,
  `drivers` varchar(255) DEFAULT NULL,
  `compatibilidad` varchar(100) DEFAULT NULL,
  `velocidad` varchar(50) DEFAULT NULL,
  `seguridad` varchar(50) DEFAULT NULL,
  `bluetooth` enum('Sí','No') DEFAULT NULL,
  `protocolos` varchar(100) DEFAULT NULL,
  `canales` varchar(50) DEFAULT NULL,
  `resolucion_audio` varchar(50) DEFAULT NULL,
  `estado` enum('Buen Funcionamiento','Operativo','Sin Funcionar') NOT NULL DEFAULT 'Buen Funcionamiento',
  `detalles` text DEFAULT NULL,
  `estadoElim` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `slot_memoria` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes_opcionales`
--

INSERT INTO `componentes_opcionales` (`id_opcional`, `id_equipo`, `tipo_opcional`, `marca`, `modelo`, `capacidad`, `frecuencia`, `tipo`, `consumo`, `ubicacion`, `salidas_video`, `salidas_audio`, `vrm`, `drivers`, `compatibilidad`, `velocidad`, `seguridad`, `bluetooth`, `protocolos`, `canales`, `resolucion_audio`, `estado`, `detalles`, `estadoElim`, `slot_memoria`) VALUES
(18, 12, 'Tarjeta WiFi', 'TP-LINK', 'TP-LINK', '', '2400MHz', 'USB', '', NULL, '', '', NULL, 'Windows 10', 'Si', '100Mbs', 'WEP, WPA, WPA2-PSK, WPA2-Enterprise', 'Sí', NULL, '', NULL, 'Operativo', '', 'Activo', NULL),
(22, 12, 'Tarjeta WiFi', 'TP-LINK', 'TP-LINK', '', '2400MHz', 'USB', '', NULL, '', '', NULL, 'Windows 10', 'Si', '100Mbs', 'WEP, WPA, WPA2-PSK', 'No', NULL, '', NULL, 'Operativo', '', 'Inactivo', ''),
(28, 12, 'Tarjeta de Sonido', 'Realtek', 'Xonar', '', '', '', '', NULL, '', 'Jack 3.5mm (analógico), RCA', NULL, 'Windows 7', 'Si', '', '', NULL, NULL, 'Estéreo (2.0), Surround 7.1 (8 canales)', '16-bit / 44.1 kHz (CD)', 'Operativo', '', 'Activo', ''),
(29, 12, 'Memoria Ram', 'Kington', '', '8GB', '3200MHZ', 'DDR3', '', NULL, '', '', NULL, NULL, '', '', '', NULL, NULL, '', NULL, 'Operativo', '', 'Inactivo', 'Slot 1'),
(30, 12, 'Memoria Ram', 'Kington', '', '4 GB', '800MHZ', 'DDR3', '', NULL, '', '', NULL, NULL, '', '', '', NULL, NULL, '', NULL, 'Operativo', '', 'Activo', 'Slot 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes_tecnologia`
--

CREATE TABLE `componentes_tecnologia` (
  `tipo_componente` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `vida_util_anios` int(11) NOT NULL,
  `peso_importancia` int(11) NOT NULL DEFAULT 1,
  `anio_lanzamiento` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes_tecnologia`
--

INSERT INTO `componentes_tecnologia` (`tipo_componente`, `tipo`, `vida_util_anios`, `peso_importancia`, `anio_lanzamiento`) VALUES
('Memoria RAM', 'DDR', 12, 2, 2000),
('Memoria RAM', 'DDR2', 10, 2, 2003),
('Memoria RAM', 'DDR3', 8, 2, 2007),
('Memoria RAM', 'DDR4', 10, 2, 2014),
('Memoria RAM', 'DDR5', 12, 2, 2020),
('Memoria RAM', 'DDR6', 14, 2, 2025),
('Socket CPU', 'AM1', 8, 4, 2014),
('Socket CPU', 'BGA1440', 6, 3, 2013),
('Socket CPU', 'BGA1526', 6, 3, 2015),
('Socket CPU', 'FM1', 8, 4, 2011),
('Socket CPU', 'FM2', 8, 4, 2012),
('Socket CPU', 'FM2+', 8, 4, 2014),
('Socket CPU', 'LGA 1150', 10, 4, 2013),
('Socket CPU', 'LGA 1151', 9, 4, 2015),
('Socket CPU', 'LGA 1155', 11, 4, 2011),
('Socket CPU', 'LGA 1156', 12, 4, 2009),
('Socket CPU', 'LGA 1200', 8, 4, 2020),
('Socket CPU', 'LGA 1366', 12, 4, 2008),
('Socket CPU', 'LGA 1700', 6, 4, 2021),
('Socket CPU', 'LGA 2011-v3', 8, 4, 2014),
('Socket CPU', 'LGA 2066', 8, 4, 2017),
('Socket CPU', 'LGA 3647', 8, 4, 2016),
('Socket CPU', 'LGA 4189', 7, 4, 2021),
('Socket CPU', 'LGA 4677', 6, 4, 2022),
('Socket CPU', 'LGA 775', 14, 4, 2004),
('Socket CPU', 'LM753', 10, 4, 2010),
('Socket CPU', 'Socket 370', 18, 4, 1999),
('Socket CPU', 'Socket 423', 18, 4, 2001),
('Socket CPU', 'Socket 478', 15, 4, 2002),
('Socket CPU', 'Socket 754', 14, 4, 2003),
('Socket CPU', 'Socket 939', 14, 4, 2004),
('Socket CPU', 'Socket 940', 14, 4, 2004),
('Socket CPU', 'Socket A (462)', 15, 4, 2003),
('Socket CPU', 'Socket AM2', 12, 4, 2006),
('Socket CPU', 'Socket AM2+', 12, 4, 2007),
('Socket CPU', 'Socket AM3', 11, 4, 2009),
('Socket CPU', 'Socket AM3+', 10, 4, 2011),
('Socket CPU', 'Socket AM4', 9, 4, 2017),
('Socket CPU', 'Socket AM5', 6, 4, 2022),
('Socket CPU', 'Socket SP5', 6, 4, 2023),
('Socket CPU', 'Socket SP6', 6, 4, 2023),
('Socket CPU', 'Socket sTR4', 8, 4, 2017),
('Socket CPU', 'Socket sTR5', 6, 4, 2022),
('Socket CPU', 'Socket sTRX4', 8, 4, 2019),
('Socket CPU', 'Socket sWRX8', 8, 4, 2020),
('Socket CPU', 'sWRX8 v2', 6, 4, 2023);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinaciones`
--

CREATE TABLE `coordinaciones` (
  `id_coordinacion` int(11) NOT NULL,
  `id_division` int(11) NOT NULL,
  `nombre_coordinacion` varchar(100) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coordinaciones`
--

INSERT INTO `coordinaciones` (`id_coordinacion`, `id_division`, `nombre_coordinacion`, `estado`) VALUES
(1, 4, 'Coordinación 1 de División 2', 'Activo'),
(2, 4, 'Coordinación 2 de División 3', 'Activo'),
(3, 1, 'Coordinación 4', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL,
  `nombre_direccion` varchar(100) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `nombre_direccion`, `estado`) VALUES
(1, 'Dirección General de la Oficina de Ti y Telecomunicaciones', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisiones`
--

CREATE TABLE `divisiones` (
  `id_division` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `nombre_division` varchar(100) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `divisiones`
--

INSERT INTO `divisiones` (`id_division`, `id_direccion`, `nombre_division`, `estado`) VALUES
(1, 1, 'Division 1', 'Activo'),
(2, 1, 'Division 2', 'Activo'),
(3, 1, 'Dirección 2', 'Activo'),
(4, 1, 'Dirección 5', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id_equipo` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `serial` varchar(50) DEFAULT NULL,
  `numero_bien` varchar(50) DEFAULT NULL,
  `tipo_gabinete` varchar(50) DEFAULT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `id_division` int(11) DEFAULT NULL,
  `id_coordinacion` int(11) DEFAULT NULL,
  `estado_funcional` enum('Buen Funcionamiento','Operativo','Sin Funcionar') DEFAULT 'Buen Funcionamiento',
  `estado_tecnologico` enum('Nuevo','Actualizable','Obsoleto') DEFAULT 'Nuevo',
  `estado_gabinete` enum('Nuevo','Semi nuevo','Deteriorado','Dañado') DEFAULT 'Nuevo',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `marca`, `modelo`, `serial`, `numero_bien`, `tipo_gabinete`, `id_direccion`, `id_division`, `id_coordinacion`, `estado_funcional`, `estado_tecnologico`, `estado_gabinete`, `estado`) VALUES
(12, 'LENOVO', 'LM753', 'N456', '232343', 'De acero', 1, NULL, NULL, 'Buen Funcionamiento', 'Actualizable', 'Semi nuevo', 'Activo');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `estado_tecnologico_actual`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `estado_tecnologico_actual` (
`tipo_componente` varchar(50)
,`tipo` varchar(50)
,`vida_util_anios` int(11)
,`peso_importancia` int(11)
,`anio_lanzamiento` int(4)
,`edad` bigint(12)
,`estado_tecnologico` varchar(12)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_log`, `usuario`, `accion`, `fecha`) VALUES
(72, 'admin', 'Acción sobre componente ID: 9', '2025-11-03 23:58:23'),
(73, 'admin', 'Acción sobre componente ID: 9', '2025-11-03 23:59:03'),
(74, 'admin', 'Agregado componente opcional ID: ', '2025-11-04 00:10:18'),
(75, 'admin', 'Actualizado componente opcional ID: 1', '2025-11-04 00:46:01'),
(76, 'admin', 'Editó división ID: 1', '2025-11-04 01:10:32'),
(77, 'admin', 'Editó coordinación ID: 1', '2025-11-04 01:44:34'),
(78, 'admin', 'Editó coordinación ID: 1', '2025-11-04 01:50:13'),
(79, 'admin', 'Actualizado el equipo ID: ', '2025-11-05 02:32:58'),
(80, 'admin', 'Agregado el equipo ID: ', '2025-11-05 21:00:49'),
(81, 'admin', 'Agregado el equipo ID: ', '2025-11-05 21:01:41'),
(82, 'admin', 'Actualizado el equipo ID: ', '2025-11-05 21:01:59'),
(83, 'admin', 'Actualizado el equipo ID: ', '2025-11-05 21:02:06'),
(84, 'admin', 'Eliminado el equipo ID: ', '2025-11-05 21:07:56'),
(85, 'admin', 'Eliminado el equipo ID: 6', '2025-11-05 21:08:46'),
(86, 'admin', 'Agregado el equipo ID: 8', '2025-11-05 21:10:08'),
(87, 'admin', 'Actualizado el equipo ID: 8', '2025-11-05 21:45:28'),
(88, 'admin', 'Creado el componente ID: 13', '2025-11-05 22:01:31'),
(89, 'admin', 'Eliminado el componente ID: 5', '2025-11-05 22:01:35'),
(90, 'admin', 'Eliminado el componente ID: 9', '2025-11-05 22:01:38'),
(91, 'admin', 'Agregado componente opcional ID: 3', '2025-11-05 22:22:55'),
(92, 'admin', 'Eliminado componente opcional ID: 3', '2025-11-05 22:23:37'),
(93, 'admin', 'Agregado componente opcional ID: 4', '2025-11-05 22:23:52'),
(94, 'admin', 'Eliminado componente opcional ID: 4', '2025-11-05 22:24:49'),
(95, 'admin', 'Agregado componente opcional ID: 5', '2025-11-05 22:25:11'),
(96, 'admin', 'Eliminado componente opcional ID: 5', '2025-11-05 22:29:10'),
(97, 'admin', 'Agregado componente opcional ID: 6', '2025-11-05 22:29:20'),
(98, 'admin', 'Editó dirección ID: 1', '2025-11-05 22:32:33'),
(99, 'admin', 'Editó división ID: 1', '2025-11-05 22:32:44'),
(100, 'admin', 'Editó división ID: 2', '2025-11-05 22:32:52'),
(101, 'admin', 'Editó coordinación ID: 1', '2025-11-05 22:33:02'),
(102, 'admin', 'Eliminado el componente ID: 7', '2025-11-05 22:33:15'),
(103, 'admin', 'Agregado componente opcional ID: 7', '2025-11-05 22:47:02'),
(104, 'admin', 'Creado el componente ID: 14', '2025-11-05 23:03:29'),
(105, 'admin', 'Eliminado el componente ID: 14', '2025-11-05 23:13:01'),
(106, 'admin', 'Creado el componente ID: 15', '2025-11-05 23:13:21'),
(107, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:19:00'),
(108, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:22:20'),
(109, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:22:31'),
(110, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:25:50'),
(111, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:27:43'),
(112, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:31:53'),
(113, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:32:24'),
(114, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:41:10'),
(115, 'admin', 'Actualziado el componente ID: 15', '2025-11-05 23:42:18'),
(116, 'admin', 'Creado el componente ID: 16', '2025-11-05 23:52:41'),
(117, 'admin', 'Actualizado componente opcional ID: 7', '2025-11-06 00:10:57'),
(118, 'sistema', 'Editó usuario ID: 4', '2025-11-06 00:12:38'),
(119, 'sistema', 'Editó usuario ID: 4', '2025-11-06 00:12:57'),
(120, 'sistema', 'Editó usuario ID: 4', '2025-11-06 00:13:15'),
(121, 'usuario', 'Creado el componente ID: 17', '2025-11-06 00:27:29'),
(122, 'usuario', 'Eliminado el componente ID: 17', '2025-11-06 00:32:07'),
(123, 'usuario', 'Creado el componente ID: 18', '2025-11-06 00:35:18'),
(124, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 00:50:00'),
(125, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 01:28:54'),
(126, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 01:31:23'),
(127, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 01:31:38'),
(128, 'usuario', 'Creado el componente ID: 19', '2025-11-06 01:52:59'),
(129, 'usuario', 'Eliminado el componente ID: 19', '2025-11-06 02:04:17'),
(130, 'usuario', 'Creado el componente ID: 20', '2025-11-06 02:04:53'),
(131, 'usuario', 'Actualziado el componente ID: 20', '2025-11-06 02:05:15'),
(132, 'usuario', 'Actualziado el componente ID: 20', '2025-11-06 02:05:20'),
(133, 'usuario', 'Actualziado el componente ID: 20', '2025-11-06 02:10:20'),
(134, 'usuario', 'Creado el componente ID: 21', '2025-11-06 02:11:21'),
(135, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 02:11:48'),
(136, 'usuario', 'Actualziado el componente ID: 13', '2025-11-06 02:13:35'),
(137, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 02:16:05'),
(138, 'usuario', 'Actualziado el componente ID: 18', '2025-11-06 02:17:56'),
(139, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 13:07:33'),
(140, 'admin', 'Actualziado el componente ID: 20', '2025-11-06 13:08:02'),
(141, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 13:09:11'),
(142, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 13:21:00'),
(143, 'admin', 'Actualziado el componente ID: 20', '2025-11-06 13:22:06'),
(144, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 13:22:32'),
(145, 'admin', 'Actualziado el componente ID: 20', '2025-11-06 13:25:18'),
(146, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 15:01:53'),
(147, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:05:34'),
(148, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:06:36'),
(149, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 15:11:06'),
(150, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:14:09'),
(151, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 15:16:58'),
(152, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:17:17'),
(153, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:17:30'),
(154, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:18:26'),
(155, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:36:03'),
(156, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:53:23'),
(157, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 15:53:34'),
(158, 'admin', 'Actualziado el componente ID: 13', '2025-11-06 15:53:47'),
(159, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 15:54:08'),
(160, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 15:57:28'),
(161, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 16:04:45'),
(162, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 16:19:36'),
(163, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:22:10'),
(164, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:26:40'),
(165, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:26:54'),
(166, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:28:05'),
(167, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:28:10'),
(168, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:29:58'),
(169, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:30:03'),
(170, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:31:08'),
(171, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:31:22'),
(172, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:38:43'),
(173, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:38:52'),
(174, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:40:23'),
(175, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:40:35'),
(176, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:44:33'),
(177, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:46:19'),
(178, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:46:28'),
(179, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:46:45'),
(180, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:49:54'),
(181, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 16:50:05'),
(182, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:50:25'),
(183, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:50:38'),
(184, 'admin', 'Creado el componente ID: 22', '2025-11-06 16:51:18'),
(185, 'admin', 'Eliminado el componente ID: 22', '2025-11-06 16:52:57'),
(186, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:53:37'),
(187, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-06 16:56:31'),
(188, 'admin', 'Agregado RAM opcional para equipo ID: 3', '2025-11-06 17:11:06'),
(189, 'admin', 'Eliminado componente opcional ID: 8', '2025-11-06 17:12:59'),
(190, 'admin', 'Agregado RAM opcional para equipo ID: 3', '2025-11-06 17:14:17'),
(191, 'admin', 'Eliminado el componente ID: 20', '2025-11-06 18:13:17'),
(192, 'admin', 'Creado el componente ID: 23', '2025-11-06 18:14:00'),
(193, 'admin', 'Eliminado el componente ID: 10', '2025-11-06 18:17:36'),
(194, 'admin', 'Creado el componente ID: 24', '2025-11-06 18:17:55'),
(195, 'admin', 'Actualziado el componente ID: 23', '2025-11-06 19:23:50'),
(196, 'admin', 'Actualziado el componente ID: 18', '2025-11-06 19:24:11'),
(197, 'admin', 'Creado el componente ID: 25', '2025-11-06 19:33:03'),
(198, 'admin', 'Actualizado el equipo ID: 3', '2025-11-06 20:43:47'),
(199, 'admin', 'Actualizado equipo ID: 3', '2025-11-06 20:57:32'),
(200, 'admin', 'Actualizado equipo ID: 3', '2025-11-06 20:59:03'),
(201, 'admin', 'Actualizado equipo ID: 3', '2025-11-06 21:01:35'),
(202, 'admin', 'Creado equipo ID: 9', '2025-11-06 21:02:29'),
(203, 'admin', 'Actualizado equipo ID: 3', '2025-11-06 21:05:43'),
(204, 'admin', 'Actualizado equipo ID: 9', '2025-11-06 21:05:55'),
(205, 'admin', 'Eliminado equipo ID: 7', '2025-11-06 21:16:25'),
(206, 'admin', 'Eliminado equipo ID: 7', '2025-11-06 21:25:55'),
(207, 'admin', 'Actualziado el componente ID: 23', '2025-11-06 21:37:18'),
(208, 'admin', 'Actualziado el componente ID: 23', '2025-11-06 21:37:29'),
(209, 'admin', 'Actualizado el componente ID: 23', '2025-11-06 21:43:37'),
(210, 'admin', 'Actualizado el componente ID: 13', '2025-11-06 22:29:06'),
(211, 'admin', 'Actualizado el componente ID: 13', '2025-11-06 22:32:24'),
(212, 'admin', 'Actualizado el componente ID: 18', '2025-11-07 02:01:11'),
(213, 'admin', 'Actualizado el componente ID: 18', '2025-11-07 02:17:31'),
(214, 'admin', 'Actualizado el componente ID: 18', '2025-11-07 02:18:02'),
(215, 'admin', 'Actualizado el componente ID: 18', '2025-11-07 02:53:49'),
(216, 'admin', 'Actualizado el componente ID: 21', '2025-11-07 18:44:19'),
(217, 'admin', 'Actualizado el componente ID: 21', '2025-11-07 18:44:26'),
(218, 'admin', 'Creado el componente ID: 26', '2025-11-07 18:44:53'),
(219, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-07 19:01:41'),
(220, 'admin', 'Actualizado componente opcional ID: 7', '2025-11-07 19:08:05'),
(221, 'admin', 'Actualizado componente opcional ID: 7', '2025-11-07 19:08:43'),
(222, 'admin', 'Actualizado componente opcional ID: 9', '2025-11-07 19:08:50'),
(223, 'admin', 'Actualizado componente opcional ID: 9', '2025-11-07 19:09:01'),
(224, 'admin', 'Actualizado componente opcional ID: 6', '2025-11-07 19:11:16'),
(225, 'admin', 'Actualizado el componente ID: 16', '2025-11-07 19:21:51'),
(226, 'admin', 'Eliminado el componente ID: 16', '2025-11-07 19:24:27'),
(227, 'admin', 'Creado el componente ID: 27', '2025-11-07 19:36:01'),
(228, 'admin', 'Actualizado el componente ID: 18', '2025-11-07 19:51:03'),
(229, 'admin', 'Actualizado el componente ID: 15', '2025-11-07 21:31:15'),
(230, 'admin', 'Creado el componente ID: 28', '2025-11-07 23:45:39'),
(231, 'admin', 'Eliminado el componente ID: 28', '2025-11-07 23:45:53'),
(232, 'admin', 'Creado el componente ID: 29', '2025-11-07 23:46:15'),
(233, 'admin', 'Eliminado el componente ID: 25', '2025-11-08 00:08:57'),
(234, 'admin', 'Creado el componente ID: 30', '2025-11-08 00:09:10'),
(235, 'admin', 'Agregado componente opcional: Tarjeta Grafica para equipo ID: 3', '2025-11-08 00:22:10'),
(236, 'admin', 'Eliminado componente opcional ID: 10', '2025-11-08 00:33:59'),
(237, 'admin', 'Agregado componente opcional: Tarjeta Grafica para equipo ID: 3', '2025-11-08 00:34:16'),
(238, 'admin', 'Eliminado componente opcional ID: 11', '2025-11-08 00:45:56'),
(239, 'admin', 'Agregado componente opcional: Tarjeta Grafica para equipo ID: 3', '2025-11-08 00:49:04'),
(240, 'admin', 'Actualizado componente opcional ID: 12', '2025-11-08 00:49:20'),
(241, 'admin', 'Actualizado componente opcional ID: 12', '2025-11-08 00:49:27'),
(242, 'admin', 'Actualizado componente opcional ID: 12', '2025-11-08 00:49:31'),
(243, 'admin', 'Eliminado el componente ID: 29', '2025-11-08 01:06:05'),
(244, 'admin', 'Creado el componente ID: 31', '2025-11-08 01:12:00'),
(245, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 3', '2025-11-08 01:34:26'),
(246, 'admin', 'Eliminado componente opcional ID: 13', '2025-11-08 01:39:06'),
(247, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 3', '2025-11-08 01:39:33'),
(248, 'admin', 'Eliminado componente opcional ID: 14', '2025-11-08 01:42:28'),
(249, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 3', '2025-11-08 01:43:10'),
(250, 'admin', 'Actualizado componente opcional ID: 15', '2025-11-08 01:43:23'),
(251, 'admin', 'Eliminado componente opcional ID: 15', '2025-11-08 01:44:27'),
(252, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 3', '2025-11-08 01:45:34'),
(253, 'admin', 'Creado equipo ID: 10', '2025-11-08 01:57:28'),
(254, 'admin', 'Actualizado equipo ID: 10', '2025-11-08 01:58:26'),
(255, 'admin', 'Eliminado equipo ID: 10', '2025-11-08 02:01:44'),
(256, 'admin', 'Creado equipo ID: 11', '2025-11-08 02:02:24'),
(257, 'admin', 'Actualizado equipo ID: 11', '2025-11-08 02:04:07'),
(258, 'admin', 'Actualizado equipo ID: 11', '2025-11-08 02:04:15'),
(259, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 3', '2025-11-08 14:31:22'),
(260, 'sistema', 'Editó usuario ID: 3', '2025-11-08 16:21:57'),
(261, 'sistema', 'Editó usuario ID: 3', '2025-11-08 16:22:46'),
(262, 'sistema', 'Editó usuario ID: 3', '2025-11-08 16:24:41'),
(263, 'sistema', 'Editó usuario ID: 3', '2025-11-08 16:26:44'),
(264, 'sistema', 'Agregó usuario: tecnico', '2025-11-08 16:27:41'),
(265, 'admin', 'Eliminado componente opcional ID: 1', '2025-11-08 16:44:13'),
(266, 'admin', 'Eliminado componente opcional ID: 2', '2025-11-08 16:44:16'),
(267, 'admin', 'Agregó coordinación ID: 3', '2025-11-08 17:29:47'),
(268, 'admin', 'Editó coordinación ID: 3', '2025-11-08 17:29:54'),
(269, 'admin', 'Creado equipo ID: 12', '2025-11-08 18:28:52'),
(270, 'admin', 'Creado el componente ID: 32', '2025-11-08 18:43:18'),
(271, 'admin', 'Creado el componente ID: 33', '2025-11-08 18:48:54'),
(272, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 18:53:17'),
(273, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:04:27'),
(274, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:06:08'),
(275, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:08:53'),
(276, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:11:21'),
(277, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:12:46'),
(278, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:18:51'),
(279, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:22:18'),
(280, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:23:01'),
(281, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:27:56'),
(282, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 19:28:04'),
(283, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 12', '2025-11-08 19:50:24'),
(284, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 12', '2025-11-08 19:54:09'),
(285, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 12', '2025-11-08 19:56:34'),
(286, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 12', '2025-11-08 20:04:46'),
(287, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo ID: 12', '2025-11-08 20:07:06'),
(288, 'admin', 'Creado el componente ID: 34', '2025-11-08 20:11:02'),
(289, 'admin', 'Actualizado el componente ID: 32', '2025-11-08 20:17:02'),
(290, 'admin', 'Eliminado componente opcional ID: 18', '2025-11-08 20:17:22'),
(291, 'admin', 'Eliminado componente opcional ID: 19', '2025-11-08 20:17:37'),
(292, 'admin', 'Eliminado componente opcional ID: 22', '2025-11-08 20:17:42'),
(293, 'admin', 'Eliminado componente opcional ID: 21', '2025-11-08 20:17:44'),
(294, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 20:20:02'),
(295, 'admin', 'Eliminado componente opcional ID: 23', '2025-11-08 20:24:39'),
(296, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 20:25:00'),
(297, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 20:35:05'),
(298, 'admin', 'Eliminado componente opcional ID: 25', '2025-11-08 20:36:17'),
(299, 'admin', 'Eliminado componente opcional ID: 24', '2025-11-08 20:36:21'),
(300, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 20:39:43'),
(301, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 20:46:49'),
(302, 'admin', 'Eliminado componente opcional ID: 27', '2025-11-08 21:01:07'),
(303, 'admin', 'Actualizado componente opcional ID: 26', '2025-11-08 21:01:25'),
(304, 'admin', 'Actualizado componente opcional ID: 26', '2025-11-08 21:01:48'),
(305, 'admin', 'Eliminado componente opcional ID: 27', '2025-11-08 21:02:41'),
(306, 'admin', 'Eliminado componente opcional ID: 26', '2025-11-08 21:02:50'),
(307, 'admin', 'Agregado componente opcional: Tarjeta de Sonido para equipo ID: 12', '2025-11-08 21:03:49'),
(308, 'admin', 'Actualizado componente opcional ID: 28', '2025-11-08 21:03:58'),
(309, 'admin', 'Actualizado componente opcional ID: 28', '2025-11-08 21:08:21'),
(310, 'admin', 'Actualizado componente opcional ID: 28', '2025-11-08 21:08:29'),
(311, 'admin', 'Eliminado componente opcional ID: 20', '2025-11-08 21:49:47'),
(312, 'admin', 'Eliminado componente opcional ID: 18', '2025-11-08 21:52:35'),
(313, 'admin', 'Eliminado componente opcional ID: 18', '2025-11-08 21:53:29'),
(314, 'admin', 'Eliminado el componente ID: 34', '2025-11-08 21:55:45'),
(315, 'admin', 'Eliminado el componente ID: 32', '2025-11-08 21:55:52'),
(316, 'admin', 'Creado el componente ID: 35', '2025-11-08 21:56:28'),
(317, 'admin', 'Eliminado el componente ID: 35', '2025-11-08 21:56:36'),
(318, 'admin', 'Creado el componente ID: 36', '2025-11-08 22:07:41'),
(319, 'admin', 'Creado el componente ID: 37', '2025-11-08 22:14:05'),
(320, 'admin', 'Creado el componente ID: 38', '2025-11-08 22:40:32'),
(321, 'admin', 'Eliminado el componente ID: 34', '2025-11-08 22:53:48'),
(322, 'admin', 'Creado el componente ID: 39', '2025-11-08 23:37:17'),
(323, 'admin', 'Creado el componente ID: 40', '2025-11-08 23:56:57'),
(324, 'admin', 'Actualizado el componente ID: 40', '2025-11-08 23:57:06'),
(325, 'admin', 'Actualizado el componente ID: 40', '2025-11-09 00:03:18'),
(326, 'admin', 'Actualizado el componente ID: 39', '2025-11-09 00:06:48'),
(327, 'admin', 'Eliminado el componente ID: 40', '2025-11-09 00:12:35'),
(328, 'admin', 'Actualizado el componente ID: 39', '2025-11-09 00:12:44'),
(329, 'admin', 'Agregado RAM opcional para equipo ID: 12', '2025-11-09 00:17:27'),
(330, 'admin', 'Actualizado componente opcional ID: 29', '2025-11-09 00:30:48'),
(331, 'admin', 'Actualizado componente opcional ID: 29', '2025-11-09 00:31:10'),
(332, 'admin', 'Actualizado componente opcional ID: 29', '2025-11-09 00:33:52'),
(333, 'admin', 'Eliminado el componente ID: 36', '2025-11-10 23:37:24'),
(334, 'admin', 'Actualizado componente opcional ID: 18', '2025-11-11 00:19:27'),
(335, 'admin', 'Actualizado componente opcional ID: 18', '2025-11-11 00:21:17'),
(336, 'admin', 'Actualizado componente opcional ID: 18', '2025-11-11 00:21:25'),
(337, 'admin', 'Eliminado el componente ID: 39', '2025-11-11 00:23:25'),
(338, 'admin', 'Eliminado el componente ID: 33', '2025-11-11 00:23:41'),
(339, 'admin', 'Eliminado componente opcional ID: 29', '2025-11-11 00:26:06'),
(340, 'admin', 'Creado el componente ID: 41', '2025-11-11 00:27:28'),
(341, 'admin', 'Actualizado el componente ID: 41', '2025-11-11 00:29:54'),
(342, 'admin', 'Actualizado el componente ID: 41', '2025-11-11 00:30:47'),
(343, 'admin', 'Actualizado el componente ID: 41', '2025-11-11 00:30:58'),
(344, 'admin', 'Actualizado componente opcional ID: 28', '2025-11-11 00:32:08'),
(345, 'admin', 'Actualizado componente opcional ID: 28', '2025-11-11 00:32:16'),
(346, 'admin', 'Actualizado el componente ID: 41', '2025-11-11 00:36:45'),
(347, 'admin', 'Actualizado el componente ID: 41', '2025-11-11 01:13:12'),
(348, 'admin', 'Creado el componente ID: 42', '2025-11-11 01:27:06'),
(349, 'admin', 'Actualizado el componente ID: 42', '2025-11-11 01:59:47'),
(350, 'admin', 'Actualizado el componente ID: 42', '2025-11-11 22:05:12'),
(351, 'admin', 'Eliminado el componente ID: 42', '2025-11-11 22:55:27'),
(352, 'admin', 'Creado el componente ID: 43', '2025-11-11 22:55:42'),
(353, 'admin', 'Agregado RAM opcional para equipo ID: 12', '2025-11-11 23:31:38'),
(354, 'admin', 'Actualizado componente opcional ID: 30', '2025-11-11 23:34:44'),
(355, 'admin', 'Actualizado componente opcional ID: 30', '2025-11-11 23:37:55'),
(356, 'admin', 'Actualizado componente opcional: Memoria Ram', '2025-11-11 23:39:40'),
(357, 'admin', 'Editó dirección ID: 1', '2025-11-11 23:48:42'),
(358, 'admin', 'Editó dirección ID: Dirección General de la Oficina de Ti y Telecomunicaciones', '2025-11-11 23:49:15'),
(359, 'admin', 'Editó división: Dirección 5', '2025-11-12 00:43:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `software`
--

CREATE TABLE `software` (
  `id_software` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `bits` enum('32','64','','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `software`
--

INSERT INTO `software` (`id_software`, `id_equipo`, `nombre`, `version`, `tipo`, `bits`) VALUES
(66, 12, 'Windows', '10', 'Sistema Operativo', '64'),
(67, 12, 'Microsoft Office', '2016', 'Ofimática', '64'),
(68, 12, 'Chrome', '', 'Navegador', NULL),
(69, 12, 'Firefox', '', 'Navegador', NULL),
(70, 12, 'Edge', '', 'Navegador', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','Usuario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `password`, `rol`) VALUES
(3, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Administrador'),
(4, 'usuario', 'e10adc3949ba59abbe56e057f20f883e', 'Usuario'),
(5, 'tecnico', 'e10adc3949ba59abbe56e057f20f883e', 'Usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD PRIMARY KEY (`id_componente`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `componentes_opcionales`
--
ALTER TABLE `componentes_opcionales`
  ADD PRIMARY KEY (`id_opcional`);

--
-- Indices de la tabla `componentes_tecnologia`
--
ALTER TABLE `componentes_tecnologia`
  ADD PRIMARY KEY (`tipo_componente`,`tipo`);

--
-- Indices de la tabla `coordinaciones`
--
ALTER TABLE `coordinaciones`
  ADD PRIMARY KEY (`id_coordinacion`),
  ADD KEY `id_division` (`id_division`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indices de la tabla `divisiones`
--
ALTER TABLE `divisiones`
  ADD PRIMARY KEY (`id_division`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `id_direccion` (`id_direccion`),
  ADD KEY `id_division` (`id_division`),
  ADD KEY `id_coordinacion` (`id_coordinacion`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `software`
--
ALTER TABLE `software`
  ADD PRIMARY KEY (`id_software`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `componentes_opcionales`
--
ALTER TABLE `componentes_opcionales`
  MODIFY `id_opcional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `coordinaciones`
--
ALTER TABLE `coordinaciones`
  MODIFY `id_coordinacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `divisiones`
--
ALTER TABLE `divisiones`
  MODIFY `id_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT de la tabla `software`
--
ALTER TABLE `software`
  MODIFY `id_software` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- --------------------------------------------------------

--
-- Estructura para la vista `estado_tecnologico_actual`
--
DROP TABLE IF EXISTS `estado_tecnologico_actual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estado_tecnologico_actual`  AS SELECT `componentes_tecnologia`.`tipo_componente` AS `tipo_componente`, `componentes_tecnologia`.`tipo` AS `tipo`, `componentes_tecnologia`.`vida_util_anios` AS `vida_util_anios`, `componentes_tecnologia`.`peso_importancia` AS `peso_importancia`, `componentes_tecnologia`.`anio_lanzamiento` AS `anio_lanzamiento`, year(curdate()) - `componentes_tecnologia`.`anio_lanzamiento` AS `edad`, CASE WHEN year(curdate()) - `componentes_tecnologia`.`anio_lanzamiento` >= `componentes_tecnologia`.`vida_util_anios` THEN 'Obsoleto' WHEN year(curdate()) - `componentes_tecnologia`.`anio_lanzamiento` >= `componentes_tecnologia`.`vida_util_anios` * 0.7 THEN 'Actualizable' ELSE 'Vigente' END AS `estado_tecnologico` FROM `componentes_tecnologia` WHERE `componentes_tecnologia`.`anio_lanzamiento` is not null ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD CONSTRAINT `componentes_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `coordinaciones`
--
ALTER TABLE `coordinaciones`
  ADD CONSTRAINT `coordinaciones_ibfk_1` FOREIGN KEY (`id_division`) REFERENCES `divisiones` (`id_division`) ON DELETE CASCADE;

--
-- Filtros para la tabla `divisiones`
--
ALTER TABLE `divisiones`
  ADD CONSTRAINT `divisiones_ibfk_1` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipos_ibfk_2` FOREIGN KEY (`id_division`) REFERENCES `divisiones` (`id_division`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipos_ibfk_3` FOREIGN KEY (`id_coordinacion`) REFERENCES `coordinaciones` (`id_coordinacion`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
