-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2025 a las 02:41:32
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
  `arquitectura` varchar(50) DEFAULT NULL,
  `estado` enum('Buen Funcionamiento','Operativo','Sin Funcionar') DEFAULT 'Buen Funcionamiento',
  `fecha_instalacion` year(4) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `capacidad` varchar(50) DEFAULT NULL,
  `consumo` varchar(50) DEFAULT NULL,
  `rgb_led` varchar(50) DEFAULT NULL,
  `ranuras_expansion` varchar(500) DEFAULT NULL,
  `conectores_alimentacion` varchar(500) DEFAULT NULL,
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
  `puertos_internos` varchar(500) DEFAULT NULL,
  `puertos_externos` varchar(500) DEFAULT NULL,
  `cantidad_slot_memoria` int(11) DEFAULT NULL,
  `slot_memoria` varchar(20) DEFAULT NULL,
  `memoria_maxima` varchar(25) DEFAULT NULL COMMENT 'Capacidad máxima de RAM que soporta la placa (en GB)',
  `frecuencias_memoria` varchar(255) DEFAULT NULL COMMENT 'Frecuencias soportadas, puede ser string o lista separada por comas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id_componente`, `id_equipo`, `tipo_componente`, `marca`, `modelo`, `arquitectura`, `estado`, `fecha_instalacion`, `ubicacion`, `tipo`, `frecuencia`, `capacidad`, `consumo`, `rgb_led`, `ranuras_expansion`, `conectores_alimentacion`, `bios_uefi`, `potencia`, `voltajes_fuente`, `nucleos`, `socket`, `soporte_memoria`, `tipo_conector`, `conectividad_soporte`, `salidas_video`, `soporte_apis`, `fabricante_controlador`, `modelo_red`, `velocidad_transferencia`, `tipo_conector_fisico`, `mac_address`, `drivers_sistema`, `compatibilidad_sistema`, `tipos_discos`, `interfaz_conexion`, `tipo_cooler`, `consumo_electrico`, `detalles`, `estadoElim`, `puertos_internos`, `puertos_externos`, `cantidad_slot_memoria`, `slot_memoria`, `memoria_maxima`, `frecuencias_memoria`) VALUES
(51, 18, 'Tarjeta Madre', 'HEWLETT-PACKARD', '2820h', NULL, 'Operativo', '2008', NULL, 'DDR2', NULL, NULL, NULL, NULL, 'PCI,PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', 'HEWLETT-PACKARD', NULL, NULL, NULL, 'LGA 775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LENTA', 'Activo', 'SATA,IDE (PATA),USB 2.0 header,Audio HD header,Fan header (3/4 pines),Paralelo (LPT),Serial (COM),Panel frontal (power/reset/LEDs)', 'VGA,USB 2.0,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio),PS/2 (Teclado),PS/2 (Mouse),Puerto Serie,Puerto Paralelo', 4, NULL, '4GB', '667,800,800,800'),
(52, 18, 'Procesador', 'INTEL', 'CORE 2 DUO E7200', 'X86', 'Operativo', NULL, NULL, NULL, '2.53GHZ', NULL, '65W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lento', 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 18, 'Memoria RAM', 'MICRON TECHNOLOGY', NULL, NULL, 'Operativo', NULL, NULL, 'DDR2', '800MHZ', '1GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LENTA', 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(55, 18, 'Fuente de Poder', 'HP', 'PS-6241-7', NULL, 'Operativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '240W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 18, 'Disco Duro', 'WESTER DIGITAL', NULL, NULL, 'Operativo', NULL, NULL, 'HDD', NULL, '256GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 18, 'Tarjeta Grafica', 'INTEL', 'INTEL(R) Q33 EXPRESS CHIPSET', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, '256MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 18, 'Tarjeta Red', 'INTEL', '82566 DM-2', NULL, 'Buen Funcionamiento', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 18, 'Unidad Optica', 'LIGHTSCRIBE', NULL, NULL, 'Operativo', NULL, NULL, 'DVD-RW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DESCONECTADO', 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 18, 'Fan Cooler', 'MASTER COOLER', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR', NULL, NULL, '2W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 20, 'Tarjeta Madre', 'DELL', '05XGC8', NULL, 'Buen Funcionamiento', '2017', NULL, 'DDR3L', NULL, NULL, NULL, NULL, 'PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', 'DELL', NULL, NULL, NULL, 'LGA 1151', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,USB 2.0 header,USB 3.0 header,Audio HD header,TPM header,Fan header (3/4 pines),Panel frontal (power/reset/LEDs)', 'HDMI,DisplayPort,USB 2.0,USB 3.0/3.1,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio)', 2, NULL, '16GB', '1600,1600'),
(62, 20, 'Memoria RAM', 'SAMSUNG', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3L', '1600MHZ', '2GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(63, 19, 'Tarjeta Madre', 'VIT', 'M2100', NULL, 'Buen Funcionamiento', '2016', NULL, 'DDR3L', NULL, NULL, NULL, NULL, NULL, 'Adaptador externo 19V', 'AMERICAN MEGRATRENDC', NULL, NULL, NULL, 'BGA1170', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,Fan header (3/4 pines),Panel frontal (power/reset/LEDs)', 'HDMI,VGA,USB 2.0,USB 3.0/3.1,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio)', 2, NULL, '8GB', '1600,1600'),
(64, 19, 'Procesador', 'INTEL', 'PENTIUM N3710', 'x64 (BRASWELL, 14NM)', 'Buen Funcionamiento', NULL, NULL, NULL, '1.60GHZ', NULL, '6W', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'BGA1170', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 19, 'Memoria RAM', 'UNIC SEMI', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3', '1660MHZ', '2GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(66, 19, 'Memoria RAM', 'UNIC SEMI', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3L', '3200MHZ', '2GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Inactivo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(67, 19, 'Memoria RAM', 'UNIC SEMI', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3L', '1600MHZ', '2GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(68, 19, 'Disco Duro', 'WESTER DIGITAL', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'HDD', NULL, '500GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 19, 'Fuente de Poder', 'VIT', 'HKA03619021-8C', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19.0V', '19V DC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 19, 'Tarjeta Grafica', 'INTEL', 'HD GRAPHICS 405', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, '128MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA,HDMI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 19, 'Tarjeta Red', 'REALTEK', 'PCIe GbE', NULL, 'Buen Funcionamiento', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1 GBPS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 19, 'Fan Cooler', 'VIT (OEM GENERICO)', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR', NULL, NULL, '6W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 21, 'Tarjeta Madre', 'LENOVO', '948IAD6', NULL, 'Operativo', '2008', NULL, 'DDR2', NULL, NULL, NULL, NULL, 'PCI,PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', '51KT37AU5', NULL, NULL, NULL, 'LGA 775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,IDE (PATA),USB 2.0 header,Audio HD header,Fan header (3/4 pines),Paralelo (LPT),Serial (COM),Chassis Intrusion (Detector),Panel frontal (power/reset/LEDs)', 'VGA,USB 2.0,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio),PS/2 (Teclado),PS/2 (Mouse),Puerto Serie,Puerto Paralelo', 4, NULL, '4GB', '533,667'),
(74, 21, 'Procesador', 'INTEL', 'CORE 2 DUO E4600', 'INTEL CORE (CONROE)', 'Operativo', NULL, NULL, NULL, '2.4GHz', NULL, '65W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 21, 'Memoria RAM', 'HP', NULL, NULL, 'Operativo', NULL, NULL, 'DDR2', '667MHz', '1GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(76, 21, 'Disco Duro', 'HITACHI', NULL, NULL, 'Operativo', NULL, NULL, 'HDD', NULL, '80GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 21, 'Fuente de Poder', 'AcBel', 'API5PC58', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '240W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 21, 'Tarjeta Grafica', 'INTEL', 'GMA 3000 GRAPHICS', NULL, 'Operativo', NULL, NULL, NULL, NULL, '256MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 21, 'Tarjeta Red', 'MARVELL YUKON', '88E8056', NULL, 'Operativo', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 21, 'Unidad Optica', 'HP', NULL, NULL, 'Sin Funcionar', NULL, NULL, 'DVD-ROM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DESCONECTADA', 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 21, 'Fan Cooler', 'FRU', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR', NULL, NULL, '3W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 22, 'Tarjeta Madre', 'HEWLETT', '18E9', NULL, 'Buen Funcionamiento', '2014', NULL, 'DDR3', NULL, NULL, NULL, NULL, 'PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', 'HEWLETT', NULL, NULL, NULL, 'LGA 1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,USB 2.0 header,USB 3.0 header,Audio HD header,Fan header (3/4 pines),Chassis Intrusion (Detector),Panel frontal (power/reset/LEDs)', 'DVI,VGA,USB 2.0,USB 3.0/3.1,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio),PS/2 (Teclado),PS/2 (Mouse)', 2, NULL, '8GB', '1333,1600'),
(83, 22, 'Memoria RAM', 'MICRON TECHNOLOGY', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3', '1600MHz', '4GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(84, 22, 'Procesador', 'INTEL', 'I3-4130', 'X64 (Haswell)', 'Buen Funcionamiento', NULL, NULL, NULL, '3.40GHz', NULL, '54W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 22, 'Disco Duro', 'PNY', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'SSD', NULL, '1TB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 22, 'Fuente de Poder', 'HP', 'D12-240P3B', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '240W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 22, 'Tarjeta Grafica', 'INTEL', 'HD GRAPHICS 4400', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, '112MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA,DVI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 22, 'Tarjeta Red', 'REALTEK', 'PCIe GbE', NULL, 'Buen Funcionamiento', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 22, 'Unidad Optica', 'HP', NULL, NULL, 'Sin Funcionar', NULL, NULL, 'DVD-ROM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DESCONECTADA', 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 22, 'Fan Cooler', 'HP', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR DE ALUMINIO', NULL, NULL, '3.6W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 20, 'Procesador', 'INTEL', 'CORE I3-6130', 'x64 (Skylake, 14 nm)', 'Buen Funcionamiento', NULL, NULL, NULL, '3.70GHz', NULL, '51W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA1151', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 20, 'Disco Duro', 'KINGSTON', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'SSD', NULL, '500GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 20, 'Fuente de Poder', 'DELL', 'H180AS-00', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 20, 'Tarjeta Red', 'REALTEK', 'PCIe GbE', NULL, 'Buen Funcionamiento', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 20, 'Tarjeta Grafica', 'INTEL', 'HD GRAPHICS 530', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, '128MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA,HDMI,DisplayPort', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 20, 'Unidad Optica', 'KINGTON', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DVD-ROM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 20, 'Fan Cooler', 'OEM', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR', NULL, NULL, '2.5W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 23, 'Tarjeta Madre', 'HEWLETT', '18E9', NULL, 'Buen Funcionamiento', '2014', NULL, 'DDR3', NULL, NULL, NULL, NULL, 'PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', 'HEWLETT', NULL, NULL, NULL, 'LGA 1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,USB 2.0 header,USB 3.0 header,Audio HD header,Fan header (3/4 pines),Chassis Intrusion (Detector),Panel frontal (power/reset/LEDs)', 'DVI,VGA,USB 2.0,USB 3.0/3.1,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio),PS/2 (Teclado),PS/2 (Mouse)', 2, NULL, '8GB', '1600'),
(99, 23, 'Memoria RAM', 'MICRON TECHNOLOGY', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DDR3', '1600MHZ', '4GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(100, 23, 'Procesador', 'INTEL', 'CORE I3-4130', 'x64 (Haswell)', 'Buen Funcionamiento', NULL, NULL, NULL, '3.4GHz', NULL, '54W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA1150', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 23, 'Disco Duro', 'KINGSTON', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'SSD', NULL, '500GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(102, 23, 'Fuente de Poder', 'HP', 'D12-240P3B', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '240W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(103, 23, 'Tarjeta Grafica', 'INTEL', 'HD GRAPHICS 4400', NULL, 'Buen Funcionamiento', NULL, NULL, NULL, NULL, '112MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA,DVI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 23, 'Tarjeta Red', 'REALTEK', 'PCIe GbE', NULL, 'Buen Funcionamiento', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(105, 23, 'Unidad Optica', 'HP', NULL, NULL, 'Buen Funcionamiento', NULL, NULL, 'DVD-ROM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 23, 'Fan Cooler', 'HP', NULL, NULL, 'Buen Funcionamiento', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR DE ALUMINIO', NULL, NULL, '3.6W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(107, 24, 'Tarjeta Madre', 'HEWLETT-PACKARD', '2820h', NULL, 'Operativo', '2008', NULL, 'DDR2', NULL, NULL, NULL, NULL, 'PCI,PCIe x1,PCIe x16', 'ATX 24 pines,EPS 4 pines,4 pines Molex,SATA Power,Berg (Floppy)', 'HEWLETT-PACKARD', NULL, NULL, NULL, 'LGA 775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', 'SATA,IDE (PATA),USB 2.0 header,Audio HD header,Fan header (3/4 pines),Paralelo (LPT),Serial (COM),Panel frontal (power/reset/LEDs)', 'DVI,VGA,USB 2.0,RJ-45 Ethernet,Jack 3.5 mm (Sonido),Jack 3.5 mm (Audio),PS/2 (Teclado),PS/2 (Mouse),Puerto Serie,Puerto Paralelo', 4, NULL, '4GB', '667,800,800,800'),
(108, 24, 'Memoria RAM', 'SAMSUNG', NULL, NULL, 'Operativo', NULL, NULL, 'DDR2', '800MHZ', '1GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, 'Slot 1', NULL, NULL),
(109, 24, 'Procesador', 'INTEL', 'CORE 2 DUO E7200', 'X86', 'Operativo', NULL, NULL, NULL, '2.53GHZ', NULL, '65W', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'LGA775', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 24, 'Disco Duro', 'WESTER DIGITAL', NULL, NULL, 'Operativo', NULL, NULL, 'HDD', NULL, '500GB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 24, 'Fuente de Poder', 'HP', 'PS-6241-7', NULL, 'Operativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '240W', '+12V,+5V,+3.3V,-12V,+5VSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 24, 'Tarjeta Grafica', 'INTEL', 'INTEL(R) Q33 EXPRESS CHIPSET', NULL, 'Operativo', NULL, NULL, NULL, NULL, '256MB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 24, 'Tarjeta Red', 'INTEL', '82566 DM-2', NULL, 'Operativo', NULL, NULL, 'Ethernet (LAN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000Mbs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 24, 'Unidad Optica', 'HITASHI D.L DATA STORAGE', NULL, NULL, 'Sin Funcionar', NULL, NULL, 'DVD-ROM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DESCONECTADA', 'Activo', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 24, 'Fan Cooler', 'HP', NULL, NULL, 'Operativo', NULL, 'SOBRE EL PROCESADOR', 'DISIPADOR DE CALOR DE ALUMINIO', NULL, NULL, '3.6W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Activo', NULL, NULL, NULL, NULL, NULL, NULL);

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
(39, 18, 'Memoria Ram', 'MICRON TECHNOLOGY', NULL, '1 GB', '800MHZ', 'DDR2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Operativo', NULL, 'Activo', 'Slot 2'),
(40, 18, 'Memoria Ram', 'SAMSUNG', NULL, '1 GB', '800MHZ', 'DDR2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Buen Funcionamiento', NULL, 'Activo', 'Slot 3'),
(41, 19, 'Tarjeta WiFi', 'MICROSOFT WIFI', 'WIFI 802.11 G/N', '', '2.4GHz', 'Mini PCIe', '', '', '', '', NULL, 'https://www.vit.gob.ve/controladores', 'Si', '150Mbps', 'WEP, WPA, WPA2-PSK', 'No', NULL, '', NULL, 'Operativo', '', 'Activo', ''),
(42, 20, 'Memoria Ram', 'MICRON TECHNOLOGY', NULL, '2 GB', '1600MHZ', 'DDR3L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Buen Funcionamiento', NULL, 'Activo', 'Slot 2');

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
('Memoria RAM', 'DDR3L', 8, 2, 2013),
('Memoria RAM', 'DDR4', 10, 2, 2014),
('Memoria RAM', 'DDR5', 12, 2, 2020),
('Memoria RAM', 'DDR6', 14, 2, 2025),
('Socket CPU', 'AM1', 8, 4, 2014),
('Socket CPU', 'BGA1170', 6, 3, 2015),
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
(3, 'Direccion de Soporte al Usuario', 'Activo'),
(4, 'Dirección General de la Oficina de Tecnologia de Informacion y Telecomunicaciones', 'Activo'),
(5, 'Dirección de Telecomunicaciones', 'Activo');

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
(5, 3, 'Division de soluciones e innovaciones electronicas', 'Activo'),
(6, 3, 'Division de Soporte de Hardware y Software', 'Activo'),
(7, 5, 'Division de Infraestructura de Red', 'Activo');

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
  `estado_gabinete` enum('Nuevo','Deteriorado','Dañado','Buen Estado') DEFAULT 'Nuevo',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `marca`, `modelo`, `serial`, `numero_bien`, `tipo_gabinete`, `id_direccion`, `id_division`, `id_coordinacion`, `estado_funcional`, `estado_tecnologico`, `estado_gabinete`, `estado`) VALUES
(18, 'HP', 'Compaq dc5800 small', 'MXJ848025L', '98487', 'METAL COLOR GRIS Y PLASTICO COLOR NEGRO', 3, 5, NULL, 'Operativo', 'Obsoleto', 'Buen Estado', 'Activo'),
(19, 'MINI VIT', 'M2100-01-01', 'A001246424', '108391', 'PLASTICO COLOR NEGRO', 3, 6, NULL, 'Operativo', 'Obsoleto', 'Nuevo', 'Activo'),
(20, 'DELL', 'OPTIPLEX 3040', '1PGBJH', '113837', 'METAL COLOR NEGRO', 3, 6, NULL, 'Operativo', 'Obsoleto', 'Buen Estado', 'Activo'),
(21, 'LENOVO', 'THINKPAKCENTRE M55', '45R6681', '99212', 'METAL COLOR GRIS Y PLASTICO COLOR NEGRO', 4, NULL, NULL, 'Operativo', 'Obsoleto', 'Buen Estado', 'Activo'),
(22, 'HP', 'PRODESK 400G1', 'MXL4371MQB', '105835', 'METAL COLOR NEGRO', 5, 7, NULL, 'Buen Funcionamiento', 'Obsoleto', 'Nuevo', 'Activo'),
(23, 'HP', 'PRODESK 400G1', 'MXL4371MPF', '105795', 'METAL COLOR NEGRO', 5, NULL, NULL, 'Buen Funcionamiento', 'Obsoleto', 'Nuevo', 'Activo'),
(24, 'HP', 'Compaq dc5800 small', 'MXJ8480253', '98491', 'METAL COLOR GRIS Y PLASTICO COLOR NEGRO', 4, NULL, NULL, 'Operativo', 'Obsoleto', 'Deteriorado', 'Activo');

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
(425, 'admin', 'Agregó dirección: Direccion de Soporte al Usuario', '2025-12-01 13:06:52'),
(426, 'admin', 'Agregó división: Division de soluciones e innovaciones electronicas', '2025-12-01 13:07:35'),
(427, 'admin', 'Creado equipo: HP dc5800 small', '2025-12-01 13:11:48'),
(429, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-01 14:12:44'),
(430, 'admin', 'Creado el componente ID: Procesador', '2025-12-01 14:30:25'),
(431, 'admin', 'Actualizó equipo: HP dc5800 small', '2025-12-01 14:33:23'),
(432, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-01 14:39:31'),
(433, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-01 15:32:09'),
(434, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-01 15:37:17'),
(435, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-01 15:39:46'),
(436, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-01 15:40:48'),
(437, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-01 15:41:18'),
(438, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-01 15:42:56'),
(439, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-01 15:44:36'),
(440, 'admin', 'Agregó división: Division de Soporte de Hardware y Software', '2025-12-01 18:07:29'),
(441, 'admin', 'Creado equipo: MINI VIT M2100-01-01', '2025-12-01 18:08:57'),
(442, 'admin', 'Creado equipo: DELL OPTIPLEX 3040', '2025-12-01 18:20:29'),
(443, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-01 18:35:08'),
(444, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-01 18:35:47'),
(445, 'admin', 'Agregado RAM opcional para equipo: HP dc5800 small', '2025-12-01 21:24:58'),
(446, 'admin', 'Agregado RAM opcional para equipo: HP dc5800 small', '2025-12-01 21:25:40'),
(447, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-01 23:04:10'),
(448, 'admin', 'Creado el componente ID: Procesador', '2025-12-01 23:13:55'),
(449, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 12:59:27'),
(450, 'admin', 'Eliminado el componente ID: Memoria RAM', '2025-12-02 13:01:02'),
(451, 'admin', 'Actualizado el componente ID: Tarjeta Madre', '2025-12-02 14:12:32'),
(452, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 14:12:52'),
(453, 'admin', 'Eliminado el componente ID: Memoria RAM', '2025-12-02 14:14:22'),
(454, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 14:14:55'),
(455, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 14:27:46'),
(456, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 14:29:59'),
(457, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 14:33:40'),
(458, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 14:50:04'),
(459, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 14:51:43'),
(460, 'admin', 'Agregado componente opcional: Tarjeta WiFi para equipo: MINI VIT M2100-01-01', '2025-12-02 15:23:52'),
(461, 'admin', 'Agregó dirección: Dirección General de la Oficina de Tecnologia de Informacion y Telecomunicaciones', '2025-12-02 15:40:21'),
(462, 'admin', 'Creado equipo: LENOVO THINKPAKCENTRE M55', '2025-12-02 15:41:39'),
(463, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-02 17:03:24'),
(464, 'admin', 'Actualizó equipo: LENOVO THINKPAKCENTRE M55', '2025-12-02 17:07:46'),
(465, 'admin', 'Creado el componente ID: Procesador', '2025-12-02 17:16:13'),
(466, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 17:24:00'),
(467, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 17:27:30'),
(468, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 17:28:34'),
(469, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 17:29:11'),
(470, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 17:30:18'),
(471, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-02 17:31:43'),
(472, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 17:32:51'),
(473, 'admin', 'Agregó dirección: Dirección de Telecomunicaciones', '2025-12-02 17:44:07'),
(474, 'admin', 'Agregó división: Division de Infraestructura de Red', '2025-12-02 17:44:55'),
(475, 'admin', 'Creado equipo: HP PRODESK 400G1', '2025-12-02 17:46:22'),
(476, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-02 17:52:31'),
(477, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 17:54:05'),
(478, 'admin', 'Creado el componente ID: Procesador', '2025-12-02 17:57:59'),
(479, 'admin', 'Actualizado el componente ID: Procesador', '2025-12-02 17:58:58'),
(480, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 18:00:45'),
(481, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 18:02:34'),
(482, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 18:05:22'),
(483, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 18:06:23'),
(484, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-02 18:07:21'),
(485, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 18:07:56'),
(486, 'admin', 'Creado el componente ID: Procesador', '2025-12-02 18:12:28'),
(487, 'admin', 'Actualizado el componente ID: Tarjeta Madre', '2025-12-02 18:15:59'),
(488, 'admin', 'Actualizado el componente ID: Memoria RAM', '2025-12-02 18:16:08'),
(489, 'admin', 'Actualizado el componente ID: Memoria RAM', '2025-12-02 18:16:18'),
(490, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 18:16:48'),
(491, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 18:17:52'),
(492, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 18:19:20'),
(493, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 18:19:53'),
(494, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-02 18:20:16'),
(495, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 18:20:45'),
(496, 'admin', 'Creado equipo: HP PRODESK 400G1', '2025-12-02 18:40:11'),
(497, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-02 18:44:36'),
(498, 'admin', 'Agregado RAM opcional para equipo: DELL OPTIPLEX 3040', '2025-12-02 18:45:28'),
(499, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 18:48:39'),
(500, 'admin', 'Creado el componente ID: Procesador', '2025-12-02 18:55:40'),
(501, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 18:57:08'),
(502, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 18:58:44'),
(503, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 18:59:25'),
(504, 'admin', 'Actualizado el componente ID: Tarjeta Grafica', '2025-12-02 18:59:42'),
(505, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 19:00:48'),
(506, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-02 19:01:35'),
(507, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 19:01:59'),
(508, 'admin', 'Actualizó equipo: HP Compaq dc5800 small', '2025-12-02 19:06:01'),
(509, 'admin', 'Creado equipo: HP Compaq dc5800 small', '2025-12-02 19:07:28'),
(510, 'admin', 'Creado el componente ID: Tarjeta Madre', '2025-12-02 19:14:49'),
(511, 'admin', 'Actualizado el componente ID: Tarjeta Madre', '2025-12-02 19:15:39'),
(512, 'admin', 'Actualizado el componente ID: Tarjeta Madre', '2025-12-02 19:15:50'),
(513, 'admin', 'Actualizado el componente ID: Tarjeta Madre', '2025-12-02 19:16:10'),
(514, 'admin', 'Creado el componente ID: Memoria RAM', '2025-12-02 19:17:44'),
(515, 'admin', 'Creado el componente ID: Procesador', '2025-12-02 19:18:30'),
(516, 'admin', 'Creado el componente ID: Disco Duro', '2025-12-02 19:19:10'),
(517, 'admin', 'Creado el componente ID: Fuente de Poder', '2025-12-02 19:19:29'),
(518, 'admin', 'Creado el componente ID: Tarjeta Grafica', '2025-12-02 19:19:54'),
(519, 'admin', 'Creado el componente ID: Tarjeta Red', '2025-12-02 19:20:23'),
(520, 'admin', 'Actualizado el componente ID: Tarjeta Grafica', '2025-12-02 19:20:30'),
(521, 'admin', 'Actualizado el componente ID: Fuente de Poder', '2025-12-02 19:20:38'),
(522, 'admin', 'Actualizado el componente ID: Disco Duro', '2025-12-02 19:20:44'),
(523, 'admin', 'Actualizado el componente ID: Procesador', '2025-12-02 19:20:51'),
(524, 'admin', 'Actualizado el componente ID: Memoria RAM', '2025-12-02 19:20:59'),
(525, 'admin', 'Creado el componente ID: Unidad Optica', '2025-12-02 19:21:45'),
(526, 'admin', 'Creado el componente ID: Fan Cooler', '2025-12-02 19:22:01');

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
  `bits` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `software`
--

INSERT INTO `software` (`id_software`, `id_equipo`, `nombre`, `version`, `tipo`, `bits`) VALUES
(143, 19, 'Windows', '10 PRO', 'Sistema Operativo', '32 BITS'),
(144, 19, 'Microsoft Office', '2010', 'Ofimática', '32 BITS'),
(145, 19, 'Chrome', '', 'Navegador', NULL),
(146, 19, 'Edge', '', 'Navegador', NULL),
(147, 20, 'Windows', '10', 'Sistema Operativo', '64 BITS'),
(148, 20, 'Microsoft Office', '2019', 'Ofimática', '64 BITS'),
(149, 20, 'Chrome', '', 'Navegador', NULL),
(150, 20, 'Edge', '', 'Navegador', NULL),
(155, 21, 'Windows', '7 ULTIMATE', 'Sistema Operativo', '32 BITS'),
(156, 21, 'Microsoft Office', '2007', 'Ofimática', '32 BITS'),
(157, 21, 'Chrome', '', 'Navegador', NULL),
(158, 21, 'Internet Explore', '', 'Navegador', NULL),
(159, 22, 'Windows', '10', 'Sistema Operativo', '64 BITS'),
(160, 22, 'Microsoft Office', '2016', 'Ofimática', '64 BITS'),
(161, 22, 'Chrome', '', 'Navegador', NULL),
(162, 22, 'Firefox', '', 'Navegador', NULL),
(163, 22, 'Edge', '', 'Navegador', NULL),
(164, 23, 'Windows', '10', 'Sistema Operativo', '64 BITS'),
(165, 23, 'Microsoft Office', '2016', 'Ofimática', '64 BITS'),
(166, 23, 'Chrome', '', 'Navegador', NULL),
(167, 23, 'Firefox', '', 'Navegador', NULL),
(168, 23, 'Edge', '', 'Navegador', NULL),
(169, 18, 'Windows', '10', 'Sistema Operativo', '32 BITS'),
(170, 18, 'Microsoft Office', '2016', 'Ofimática', '32 BITS'),
(171, 18, 'Chrome', '', 'Navegador', NULL),
(172, 18, 'Firefox', '', 'Navegador', NULL),
(173, 18, 'Internet Explore', '', 'Navegador', NULL),
(174, 24, 'Windows', '10', 'Sistema Operativo', '32 BITS'),
(175, 24, 'Microsoft Office', '2016', 'Ofimática', '32 BITS'),
(176, 24, 'Chrome', '', 'Navegador', NULL),
(177, 24, 'Firefox', '', 'Navegador', NULL),
(178, 24, 'Internet Explore', '', 'Navegador', NULL);

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
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de la tabla `componentes_opcionales`
--
ALTER TABLE `componentes_opcionales`
  MODIFY `id_opcional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `coordinaciones`
--
ALTER TABLE `coordinaciones`
  MODIFY `id_coordinacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `divisiones`
--
ALTER TABLE `divisiones`
  MODIFY `id_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=527;

--
-- AUTO_INCREMENT de la tabla `software`
--
ALTER TABLE `software`
  MODIFY `id_software` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

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
