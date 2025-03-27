-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2025 at 09:45 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `telepomba`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversas`
--

DROP TABLE IF EXISTS `conversas`;
CREATE TABLE IF NOT EXISTS `conversas` (
  `id_conversa` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo` enum('privada','grupo') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'privada',
  `criado_por` bigint UNSIGNED NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem_perfil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'ficheiros/media/index/default.png',
  PRIMARY KEY (`id_conversa`),
  KEY `criado_por` (`criado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversas`
--

INSERT INTO `conversas` (`id_conversa`, `nome`, `tipo`, `criado_por`, `criado_em`, `imagem_perfil`) VALUES
(1, NULL, 'privada', 5, '2025-03-25 20:53:13', 'ficheiros/media/index/default.png'),
(2, 'Group Test', 'grupo', 6, '2025-03-25 21:16:05', 'ficheiros/media/index/default.png'),
(3, 'Welcome!', 'grupo', 5, '2025-03-25 23:36:13', 'ficheiros/media/groups/group_67e33ded6a975.jpg'),
(4, 'abc', 'grupo', 5, '2025-03-25 23:38:20', 'ficheiros/media/groups/group_67e33e6c248a3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
CREATE TABLE IF NOT EXISTS `mensagens` (
  `id_mensagem` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_conversa` bigint UNSIGNED NOT NULL,
  `id_remetente` bigint UNSIGNED NOT NULL,
  `conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `enviado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensagem`),
  KEY `id_conversa` (`id_conversa`),
  KEY `id_remetente` (`id_remetente`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensagens`
--

INSERT INTO `mensagens` (`id_mensagem`, `id_conversa`, `id_remetente`, `conteudo`, `enviado_em`) VALUES
(1, 1, 5, 'ola', '2025-03-25 21:37:03'),
(2, 1, 6, 'oi', '2025-03-25 21:37:19'),
(3, 1, 5, 'tudo bem?', '2025-03-25 21:38:27'),
(4, 1, 6, 'sim', '2025-03-25 21:38:34'),
(5, 1, 5, 'boa', '2025-03-25 21:46:04'),
(6, 1, 5, 'queres ouvir uma piada?', '2025-03-25 21:47:57'),
(7, 1, 6, 'nao, po crl', '2025-03-25 21:48:08'),
(8, 1, 5, 'bruh', '2025-03-25 21:49:27'),
(9, 1, 6, ';)', '2025-03-25 21:49:38'),
(10, 1, 5, 'a', '2025-03-25 22:08:18'),
(11, 1, 6, 'b', '2025-03-25 22:08:23'),
(12, 2, 5, 'a', '2025-03-25 22:08:34'),
(13, 2, 6, 'c', '2025-03-25 22:08:39'),
(14, 1, 5, 'HI', '2025-03-25 22:14:11'),
(15, 1, 6, 'HELLO', '2025-03-25 22:14:19'),
(16, 1, 5, 'AYO', '2025-03-25 22:16:21'),
(17, 1, 6, 'AYOOO', '2025-03-25 22:16:28'),
(18, 1, 5, 'A', '2025-03-25 22:17:13'),
(19, 1, 6, 'BB', '2025-03-25 22:17:20'),
(20, 1, 5, 'fack', '2025-03-25 22:19:34'),
(21, 1, 6, 'naaa', '2025-03-25 22:19:40'),
(22, 1, 5, 'bro', '2025-03-25 22:30:28'),
(23, 1, 6, 'yes?', '2025-03-25 22:30:39'),
(24, 1, 6, 'sdf', '2025-03-25 23:04:54'),
(25, 1, 5, 'sdf', '2025-03-25 23:05:58'),
(26, 1, 5, 'ss', '2025-03-25 23:05:58'),
(27, 1, 5, 'hi', '2025-03-25 23:06:26'),
(28, 1, 6, 'sup', '2025-03-25 23:06:29'),
(29, 1, 5, 'wtf', '2025-03-25 23:10:06'),
(30, 1, 6, 'yeah wtf', '2025-03-25 23:10:13'),
(31, 1, 5, 'yo', '2025-03-25 23:11:38'),
(32, 1, 6, 'sup dude', '2025-03-25 23:11:42'),
(33, 1, 5, 'wazzup', '2025-03-25 23:12:43'),
(34, 1, 6, 'wazuuuuppp!', '2025-03-25 23:12:48'),
(35, 2, 5, 'sup doods', '2025-03-25 23:15:38'),
(36, 2, 6, 'o meu nome e???', '2025-03-25 23:15:44'),
(37, 2, 5, 'WUANT', '2025-03-25 23:15:46'),
(38, 3, 5, 'AHH!', '2025-03-25 23:36:27'),
(39, 3, 6, 'WHAT?', '2025-03-25 23:36:37'),
(40, 1, 6, 'AS', '2025-03-26 08:36:02'),
(41, 1, 6, 'FDS', '2025-03-26 08:43:20'),
(42, 1, 5, 'BRO', '2025-03-26 08:43:25'),
(43, 1, 6, 'A', '2025-03-26 08:43:46'),
(44, 1, 6, 'B', '2025-03-26 08:43:46'),
(45, 1, 6, 'C', '2025-03-26 08:43:47'),
(46, 1, 6, 'D', '2025-03-26 08:43:47'),
(47, 1, 6, 'E', '2025-03-26 08:43:48'),
(48, 1, 6, 'F', '2025-03-26 08:43:48'),
(49, 1, 6, 'G', '2025-03-26 08:43:48'),
(50, 1, 6, 'HAHAHA', '2025-03-26 08:43:50'),
(51, 1, 6, 'YES!', '2025-03-26 08:43:52'),
(52, 1, 6, 'pluh', '2025-03-26 08:48:56'),
(53, 1, 5, 'pluh!', '2025-03-26 08:49:03'),
(54, 4, 5, 'sup', '2025-03-26 09:03:56'),
(55, 4, 6, 'hey', '2025-03-26 09:04:12'),
(56, 1, 5, 'some', '2025-03-26 10:41:07'),
(57, 1, 6, 'body', '2025-03-26 10:41:22'),
(58, 1, 5, 'OLA', '2025-03-26 11:03:39'),
(59, 1, 6, 'BOASSS', '2025-03-26 11:03:46'),
(60, 1, 6, 'oi?', '2025-03-26 11:15:14'),
(61, 1, 5, 'ent', '2025-03-26 11:15:27'),
(62, 1, 6, 'fixe', '2025-03-26 11:15:32'),
(63, 1, 6, 'oi', '2025-03-26 12:42:27'),
(64, 1, 6, 'ewew', '2025-03-26 12:42:28'),
(65, 1, 6, 'we', '2025-03-26 12:42:28'),
(66, 1, 6, 'ew', '2025-03-26 12:42:28'),
(67, 1, 6, 'we', '2025-03-26 12:42:29'),
(68, 1, 6, 'ew', '2025-03-26 12:42:29'),
(69, 1, 6, 'ew', '2025-03-26 12:42:29'),
(70, 1, 5, 'sadsda', '2025-03-26 12:42:31'),
(71, 1, 5, 'AOARPARA', '2025-03-26 12:42:35'),
(72, 1, 5, 'WHO LET THE DOGS OUTTT', '2025-03-26 12:42:52'),
(73, 1, 6, 'WOOF', '2025-03-26 12:42:55'),
(74, 1, 5, 'hi', '2025-03-26 14:24:28'),
(75, 1, 5, 'hii!', '2025-03-26 14:24:51'),
(76, 1, 5, 'a', '2025-03-26 14:26:29'),
(77, 1, 6, 'hey', '2025-03-27 08:37:54'),
(78, 1, 6, 'pluh', '2025-03-27 08:49:59'),
(79, 1, 5, 'bruh', '2025-03-27 08:50:03'),
(80, 1, 6, 'WAZAAAAAAAAAAAAAAAAAAP', '2025-03-27 08:59:28'),
(81, 1, 5, 'YOOOOOOOOOOOo', '2025-03-27 08:59:33'),
(82, 1, 6, 'a', '2025-03-27 09:20:21'),
(83, 1, 6, 'a', '2025-03-27 09:20:23'),
(84, 1, 6, 'a', '2025-03-27 09:21:21'),
(85, 1, 5, 'ya', '2025-03-27 09:21:28'),
(86, 1, 5, 'tranquilo', '2025-03-27 09:21:31'),
(87, 1, 6, 'ta favoravel', '2025-03-27 09:21:36'),
(88, 1, 5, 'asd', '2025-03-27 09:43:40'),
(89, 1, 6, 'heheh', '2025-03-27 09:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `participantes_conversa`
--

DROP TABLE IF EXISTS `participantes_conversa`;
CREATE TABLE IF NOT EXISTS `participantes_conversa` (
  `id_participante` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_conversa` bigint UNSIGNED NOT NULL,
  `id_utilizador` bigint UNSIGNED NOT NULL,
  `entrou_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_participante`),
  KEY `id_conversa` (`id_conversa`),
  KEY `id_utilizador` (`id_utilizador`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participantes_conversa`
--

INSERT INTO `participantes_conversa` (`id_participante`, `id_conversa`, `id_utilizador`, `entrou_em`) VALUES
(1, 1, 5, '2025-03-25 20:53:13'),
(2, 1, 6, '2025-03-25 20:53:13'),
(3, 2, 6, '2025-03-25 21:16:05'),
(4, 2, 5, '2025-03-25 21:16:05'),
(5, 3, 5, '2025-03-25 23:36:13'),
(6, 3, 6, '2025-03-25 23:36:13'),
(7, 4, 5, '2025-03-25 23:38:20'),
(8, 4, 6, '2025-03-25 23:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id_utilizador` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_utilizador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `palavra_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imagem_perfil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'ficheiros/media/profiles/default_profile_image.jpg',
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `estado` enum('online','ausente','ocupado','offline') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'offline',
  `ultima_atividade` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilizador`),
  UNIQUE KEY `id_utilizador` (`id_utilizador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome_utilizador`, `palavra_passe`, `email`, `imagem_perfil`, `descricao`, `estado`, `ultima_atividade`, `criado_em`, `atualizado_em`) VALUES
(5, 'Thomaz123', '$2y$10$f59.KOEoneJ3xqYuJaPEH.7XjdUhGjW3m8.d.IdSL7gXaVukOOzj6', 'thomazbarrago@gmail.com', 'ficheiros/media/profiles/ultrakill.jpg', NULL, 'offline', '2025-03-27 08:54:46', '2025-03-25 20:51:30', '2025-03-25 20:51:30'),
(6, 'Cortez123', '$2y$10$HTwHwJSFrvaSqx/PI70LBO55py4GvWoF5hkvrLY7STubYWVkESfQe', 'cortez123@gmail.com', 'ficheiros/media/profiles/67e317969bacf.png', NULL, 'offline', '2025-03-25 20:52:38', '2025-03-25 20:52:38', '2025-03-25 20:52:38'),
(7, 'Faria123', '$2y$10$h7uzcOJzZYGlgSXNL/5Z3.8w7b3VG3Vhs8fBUR2.JshjIJWa.yyzO', 'Faria123@gmail.com', 'ficheiros/media/index/default.png', NULL, 'offline', '2025-03-26 15:03:00', '2025-03-26 15:03:00', '2025-03-26 15:03:00'),
(10, 'Barrago123', '$2y$10$8wKuGosMbam/XXPoBgk21.mcI8XiOoeDCfWmY8Ti3drBDHULQpAm2', 'barrago123@gmail.com', 'ficheiros/media/index/default.png', NULL, 'offline', '2025-03-26 15:38:16', '2025-03-26 15:38:16', '2025-03-26 15:38:16');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conversas`
--
ALTER TABLE `conversas`
  ADD CONSTRAINT `conversas_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Constraints for table `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_remetente`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Constraints for table `participantes_conversa`
--
ALTER TABLE `participantes_conversa`
  ADD CONSTRAINT `participantes_conversa_ibfk_1` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  ADD CONSTRAINT `participantes_conversa_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
