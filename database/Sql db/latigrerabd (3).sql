-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2026 at 03:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latigrerabd`
--

-- --------------------------------------------------------

--
-- Table structure for table `bombonas`
--

CREATE TABLE `bombonas` (
  `id` bigint(20) NOT NULL,
  `tamanno` int(11) NOT NULL,
  `precio` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `bombonas`
--

INSERT INTO `bombonas` (`id`, `tamanno`, `precio`) VALUES
(1, 10, 100.00),
(2, 18, 1.00),
(3, 27, 2.00),
(4, 43, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `calles`
--

CREATE TABLE `calles` (
  `id` bigint(20) NOT NULL,
  `calle` varchar(255) DEFAULT NULL,
  `sector_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `calles`
--

INSERT INTO `calles` (`id`, `calle`, `sector_id`) VALUES
(1, 'La Urdaneta', 1),
(2, 'Sucre', 1),
(3, 'Bolivar', 2),
(4, 'Z', 2);

-- --------------------------------------------------------

--
-- Table structure for table `despachos`
--

CREATE TABLE `despachos` (
  `id` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `precio_dolar` decimal(10,0) DEFAULT NULL,
  `precio_caleteros` decimal(15,2) DEFAULT NULL,
  `jefe_sector_id` bigint(20) DEFAULT NULL,
  `estado_sistema_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `despachos`
--

INSERT INTO `despachos` (`id`, `fecha`, `precio_dolar`, `precio_caleteros`, `jefe_sector_id`, `estado_sistema_id`) VALUES
(18, '2025-02-03', 54, 0.00, 2, 2),
(27, '2025-02-07', 65, 0.00, 1, 2),
(28, '2025-02-07', 98, 0.00, 1, 2),
(29, '2025-02-07', 54, 0.00, 1, 2),
(30, '2025-02-07', 60, 0.00, 1, 2),
(31, '2025-02-07', 55, 0.00, 1, 2),
(32, '2025-02-08', 55, 0.00, 1, 2),
(33, '2025-02-10', 56, 0.00, 1, 2),
(34, '2025-02-10', 90, 0.00, 1, 2),
(35, '2025-02-10', 60, 5.00, 1, 2),
(36, '2025-09-20', NULL, 5.00, 1, 2),
(37, '2025-09-22', NULL, 5.00, NULL, 2),
(38, '2025-09-26', NULL, 4.00, 1, 2),
(39, '2026-04-10', NULL, 110.00, NULL, 2),
(40, '2026-04-10', NULL, 4.00, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `estados_geografico`
--

CREATE TABLE `estados_geografico` (
  `id` bigint(20) NOT NULL,
  `Estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `estados_geografico`
--

INSERT INTO `estados_geografico` (`id`, `Estado`) VALUES
(1, 'Carabobo');

-- --------------------------------------------------------

--
-- Table structure for table `estados_sistema`
--

CREATE TABLE `estados_sistema` (
  `id` bigint(20) NOT NULL,
  `estado_sistema` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `estados_sistema`
--

INSERT INTO `estados_sistema` (`id`, `estado_sistema`) VALUES
(1, 'Habilitado'),
(2, 'Inhabilitado');

-- --------------------------------------------------------

--
-- Table structure for table `inventario_cilindros`
--

CREATE TABLE `inventario_cilindros` (
  `id` bigint(20) NOT NULL,
  `cantidad_bombona_10kg` decimal(10,0) DEFAULT NULL,
  `cantidad_bombona_18kg` decimal(10,0) DEFAULT NULL,
  `cantidad_bombona_27kg` decimal(10,0) DEFAULT NULL,
  `cantidad_bombona_43kg` decimal(10,0) DEFAULT NULL,
  `jefe_familia_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `inventario_cilindros`
--

INSERT INTO `inventario_cilindros` (`id`, `cantidad_bombona_10kg`, `cantidad_bombona_18kg`, `cantidad_bombona_27kg`, `cantidad_bombona_43kg`, `jefe_familia_id`) VALUES
(1, 2, 81, 5, 5, 5),
(2, 1, 1, 1, 1, 6),
(3, 1, 1, 1, 1, 8),
(4, 5, 7, 7000, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `jefes_calles`
--

CREATE TABLE `jefes_calles` (
  `id` bigint(20) NOT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `codigo_jefe_calle` bigint(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `calle_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `jefes_calles`
--

INSERT INTO `jefes_calles` (`id`, `documento`, `nombre`, `apellido`, `telefono`, `email`, `codigo_jefe_calle`, `fecha_registro`, `calle_id`, `usuario_id`) VALUES
(1, '2104124', 'jhostin', 'Arcila', '04144007299', 'jhostin@jhostin.com', 1, NULL, 2, 5),
(2, '1234567', 'Carmen', 'Carzorla', '04125945040', 'carmen@gmail.com', 2, NULL, 2, 6),
(3, '8976576', 'Ariagna', 'lee', '04125238472', 'ari@gmail.com', 31, NULL, 2, 7),
(4, '2346545', 'Gaby2020', 'Gaby2020', '2020202022', 'Gaby2020@gmail.com.', 5, '2025-09-04 20:26:53', 2, 8),
(5, '0000013', 'JHOSTINE', 'ARCILO', '04125238777', 'arcilajhostin@gmail.com', 38, NULL, 3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `jefes_familia`
--

CREATE TABLE `jefes_familia` (
  `id` bigint(20) NOT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `calle` varchar(255) NOT NULL,
  `numero_casa` varchar(255) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `jefe_calle_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `jefes_familia`
--

INSERT INTO `jefes_familia` (`id`, `documento`, `nombre`, `apellido`, `telefono`, `email`, `calle`, `numero_casa`, `fecha_registro`, `jefe_calle_id`) VALUES
(5, '8976576', 'Jhostino', 'Arcila', '04125238472', 'ari@gmail.com', 'La Urdaneta', '2-A', '2025-09-25', 1),
(6, '1456789', 'Rujano', 'Idelmaro', '04141234569', 'rujano@rujano.com', 'La Urdaneta', '2-A', '2026-04-11', 1),
(8, '0000014', 'Evelyn', 'Ramirez', '04125238479', 'arcilajhostin@gmail.com', 'Sucre', '2-B', '2026-04-11', 3),
(9, '0000012', 'JHOSTIN', 'ARCILA', '04144007299', 'arcilajhostin@gmail.com', 'Bolivar', '2-c', '2026-04-11', 5);

-- --------------------------------------------------------

--
-- Table structure for table `jefes_sectores`
--

CREATE TABLE `jefes_sectores` (
  `id` bigint(20) NOT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `sector_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `jefes_sectores`
--

INSERT INTO `jefes_sectores` (`id`, `documento`, `nombre`, `apellido`, `telefono`, `email`, `fecha_registro`, `usuario_id`, `sector_id`) VALUES
(1, '1843752', 'Sandra', 'Zambrano', '04141234567', 'sandra@gmail.com', '2025-02-07', 2, 2),
(2, '2910421', 'Carlos', 'Perez', '04125945040', 'carlos@gmail.com', '2025-02-07', 3, 1),
(3, '4892022', 'Juan', 'Soto', '04125843958', 'paco@paco.com', '2025-02-07', 4, 1),
(4, '1843751', 'Jhostin', 'lee', '04125843958', 'comunidad1@gmail.com', '2025-09-26', 9, 1),
(5, '4892021', 'Rujano', 'Idelmaro', '04141234569', 'arcilajhostin@gmail.com', '2026-04-11', 10, 1),
(6, '0000047', 'JHOSTIN', 'ARCILA', '04141234569', 'arcilajhostin@gmail.com', '2026-04-11', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `municipios`
--

CREATE TABLE `municipios` (
  `id` bigint(20) NOT NULL,
  `municipio` varchar(255) DEFAULT NULL,
  `estado_geografico_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `municipios`
--

INSERT INTO `municipios` (`id`, `municipio`, `estado_geografico_id`) VALUES
(1, 'Guacara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `precio` float(10,0) NOT NULL,
  `cantidad_caleteros` int(255) NOT NULL,
  `referencia_pago` int(255) NOT NULL,
  `bombona_id` bigint(20) DEFAULT NULL,
  `despacho_id` bigint(20) DEFAULT NULL,
  `jefe_calle_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `cantidad`, `fecha`, `precio`, `cantidad_caleteros`, `referencia_pago`, `bombona_id`, `despacho_id`, `jefe_calle_id`) VALUES
(242, 5, '2025-09-20', 12, 0, 11111, 1, 36, 1),
(243, 2, '2025-09-20', 15, 0, 11111, 2, 36, 1),
(244, 3, '2025-09-20', 25, 0, 11111, 3, 36, 1),
(245, 8, '2025-09-20', 40, 0, 11111, 4, 36, 1),
(246, 0, '2025-09-25', 12, 0, 12487, 1, 37, 1),
(247, 0, '2025-09-25', 15, 0, 12487, 2, 37, 1),
(248, 0, '2025-09-25', 25, 0, 12487, 3, 37, 1),
(249, 3, '2025-09-25', 40, 0, 12487, 4, 37, 1),
(250, 0, '2025-09-26', 12, 0, 15245, 1, 37, 2),
(251, 5, '2025-09-26', 15, 0, 15245, 2, 37, 2),
(252, 5, '2025-09-26', 25, 0, 15245, 3, 37, 2),
(253, 1, '2025-09-26', 40, 0, 15245, 4, 37, 2),
(254, 8, '2025-09-26', 12, 0, 54545, 1, 37, 3),
(255, 7, '2025-09-26', 15, 0, 54545, 2, 37, 3),
(256, 7, '2025-09-26', 25, 0, 54545, 3, 37, 3),
(257, 5, '2025-09-26', 40, 0, 54545, 4, 37, 3),
(258, 0, '2025-09-26', 12, 8, 12485, 1, 37, 4),
(259, 5, '2025-09-26', 15, 8, 12485, 2, 37, 4),
(260, 5, '2025-09-26', 25, 8, 12485, 3, 37, 4),
(261, 4, '2025-09-26', 40, 8, 12485, 4, 37, 4),
(262, 7, '2025-09-26', 1, 36, 98998, 1, 38, 1),
(263, 8, '2025-09-26', 2, 36, 98998, 2, 38, 1),
(264, 7, '2025-09-26', 3, 36, 98998, 3, 38, 1),
(265, 14, '2025-09-26', 5, 36, 98998, 4, 38, 1),
(266, 1, '2025-09-26', 1, 19, 74757, 1, 38, 4),
(267, 8, '2025-09-26', 2, 19, 74757, 2, 38, 4),
(268, 4, '2025-09-26', 3, 19, 74757, 3, 38, 4),
(269, 6, '2025-09-26', 5, 19, 74757, 4, 38, 4),
(270, 1, '2025-09-26', 1, 4, 45674, 1, 38, 2),
(271, 1, '2025-09-26', 2, 4, 45674, 2, 38, 2),
(272, 1, '2025-09-26', 3, 4, 45674, 3, 38, 2),
(273, 1, '2025-09-26', 5, 4, 45674, 4, 38, 2),
(274, 2, '2025-09-26', 1, 6, 59687, 1, 38, 3),
(275, 3, '2025-09-26', 2, 6, 59687, 2, 38, 3),
(276, 0, '2025-09-26', 3, 6, 59687, 3, 38, 3),
(277, 1, '2025-09-26', 5, 6, 59687, 4, 38, 3),
(278, 1, '2026-04-10', 100, 4, 12345, 1, 40, 2),
(279, 1, '2026-04-10', 1, 4, 12345, 2, 40, 2),
(280, 1, '2026-04-10', 2, 4, 12345, 3, 40, 2),
(281, 1, '2026-04-10', 3, 4, 12345, 4, 40, 2);

-- --------------------------------------------------------

--
-- Table structure for table `parroquias`
--

CREATE TABLE `parroquias` (
  `id` bigint(20) NOT NULL,
  `parroquia` varchar(255) DEFAULT NULL,
  `municipio_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `parroquias`
--

INSERT INTO `parroquias` (`id`, `parroquia`, `municipio_id`) VALUES
(1, 'Guacara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Jefe de Sector'),
(3, 'Jefe de Calle');

-- --------------------------------------------------------

--
-- Table structure for table `sectores`
--

CREATE TABLE `sectores` (
  `id` bigint(20) NOT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `parroquia_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `sectores`
--

INSERT INTO `sectores` (`id`, `sector`, `parroquia_id`) VALUES
(1, 'La Tigrera', 1),
(2, 'La Guajira', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `estado_sistema_id` bigint(20) DEFAULT NULL,
  `rol_id` bigint(20) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `estado_sistema_id`, `rol_id`, `fecha_registro`) VALUES
(1, 'Administrador', 'Admin123456', 1, 1, '2025-02-07'),
(2, 'Sandra123', 'Sandra123', 1, 2, '2025-02-07'),
(3, 'Carlos1234', 'Carlos1234', 2, 2, '2025-02-07'),
(4, 'Juan1234', 'Juan1234', 2, 2, '2025-02-07'),
(5, 'jhostin123', 'Jhostin123', 1, 3, '2025-02-07'),
(6, 'Carmen12', 'Carmen12', 1, 3, '2025-02-07'),
(7, 'Ari25', 'Ari123456', 1, 3, '2025-09-21'),
(8, 'Gaby2020', 'Gaby2020', 1, 3, '2025-09-25'),
(9, 'jhostinarcila', 'Jhostin123', 2, 2, '2025-09-26'),
(10, 'Rujano', '74894561aA', 1, 2, '2026-04-11'),
(11, 'jhostin127', 'jhostin123aA', 1, 3, '2026-04-11'),
(12, 'jhostin321', 'Jhostin321', 2, 2, '2026-04-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bombonas`
--
ALTER TABLE `bombonas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calles`
--
ALTER TABLE `calles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- Indexes for table `despachos`
--
ALTER TABLE `despachos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jefe_sector_id` (`jefe_sector_id`),
  ADD KEY `estado_sistema_id` (`estado_sistema_id`);

--
-- Indexes for table `estados_geografico`
--
ALTER TABLE `estados_geografico`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estados_sistema`
--
ALTER TABLE `estados_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventario_cilindros`
--
ALTER TABLE `inventario_cilindros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jefe_familia_id` (`jefe_familia_id`);

--
-- Indexes for table `jefes_calles`
--
ALTER TABLE `jefes_calles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `calle_id` (`calle_id`);

--
-- Indexes for table `jefes_familia`
--
ALTER TABLE `jefes_familia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jefe_calle_id` (`jefe_calle_id`);

--
-- Indexes for table `jefes_sectores`
--
ALTER TABLE `jefes_sectores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- Indexes for table `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_geografico_id` (`estado_geografico_id`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bombona_id` (`bombona_id`),
  ADD KEY `despacho_id` (`despacho_id`),
  ADD KEY `jefe_calle_id` (`jefe_calle_id`);

--
-- Indexes for table `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipio_id` (`municipio_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parroquia_id` (`parroquia_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_sistema_id` (`estado_sistema_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bombonas`
--
ALTER TABLE `bombonas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `calles`
--
ALTER TABLE `calles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `despachos`
--
ALTER TABLE `despachos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `estados_geografico`
--
ALTER TABLE `estados_geografico`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `estados_sistema`
--
ALTER TABLE `estados_sistema`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventario_cilindros`
--
ALTER TABLE `inventario_cilindros`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jefes_calles`
--
ALTER TABLE `jefes_calles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jefes_familia`
--
ALTER TABLE `jefes_familia`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jefes_sectores`
--
ALTER TABLE `jefes_sectores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calles`
--
ALTER TABLE `calles`
  ADD CONSTRAINT `calles_ibfk_1` FOREIGN KEY (`sector_id`) REFERENCES `sectores` (`id`);

--
-- Constraints for table `despachos`
--
ALTER TABLE `despachos`
  ADD CONSTRAINT `despachos_ibfk_1` FOREIGN KEY (`jefe_sector_id`) REFERENCES `jefes_sectores` (`id`),
  ADD CONSTRAINT `despachos_ibfk_2` FOREIGN KEY (`estado_sistema_id`) REFERENCES `estados_sistema` (`id`);

--
-- Constraints for table `inventario_cilindros`
--
ALTER TABLE `inventario_cilindros`
  ADD CONSTRAINT `inventario_cilindros_ibfk_1` FOREIGN KEY (`jefe_familia_id`) REFERENCES `jefes_familia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jefes_calles`
--
ALTER TABLE `jefes_calles`
  ADD CONSTRAINT `jefes_calles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `jefes_calles_ibfk_2` FOREIGN KEY (`calle_id`) REFERENCES `calles` (`id`);

--
-- Constraints for table `jefes_familia`
--
ALTER TABLE `jefes_familia`
  ADD CONSTRAINT `jefes_familia_ibfk_1` FOREIGN KEY (`jefe_calle_id`) REFERENCES `jefes_calles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jefes_sectores`
--
ALTER TABLE `jefes_sectores`
  ADD CONSTRAINT `jefes_sectores_ibfk_1` FOREIGN KEY (`sector_id`) REFERENCES `sectores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jefes_sectores_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`estado_geografico_id`) REFERENCES `estados_geografico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`bombona_id`) REFERENCES `bombonas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`despacho_id`) REFERENCES `despachos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_3` FOREIGN KEY (`jefe_calle_id`) REFERENCES `jefes_calles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parroquias`
--
ALTER TABLE `parroquias`
  ADD CONSTRAINT `parroquias_ibfk_1` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sectores`
--
ALTER TABLE `sectores`
  ADD CONSTRAINT `sectores_ibfk_1` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`estado_sistema_id`) REFERENCES `estados_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
