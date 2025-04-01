-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 01-Abr-2025 às 00:55
-- Versão do servidor: 8.3.0
-- versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `telepomba`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `conversas`
--

DROP TABLE IF EXISTS `conversas`;
CREATE TABLE IF NOT EXISTS `conversas` (
  `id_conversa` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo` enum('privada','grupo') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'privada',
  `criado_por` bigint UNSIGNED NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem_perfil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'ficheiros/media/index/default.png',
  `imagem_grupo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_conversa`),
  KEY `criado_por` (`criado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `conversas`
--

INSERT INTO `conversas` (`id_conversa`, `nome`, `tipo`, `criado_por`, `criado_em`, `imagem_perfil`, `imagem_grupo`) VALUES
(1, NULL, 'privada', 5, '2025-03-25 20:53:13', 'ficheiros/media/index/default.png', NULL),
(2, 'Group Test', 'grupo', 6, '2025-03-25 21:16:05', 'ficheiros/media/index/default.png', NULL),
(3, 'Welcome!', 'grupo', 5, '2025-03-25 23:36:13', 'ficheiros/media/groups/group_67e33ded6a975.jpg', NULL),
(4, 'abc', 'grupo', 5, '2025-03-25 23:38:20', 'ficheiros/media/groups/group_67e33e6c248a3.jpg', NULL),
(5, 'Test2', 'grupo', 5, '2025-03-27 23:32:07', 'ficheiros/media/index/default.png', NULL),
(6, NULL, 'privada', 5, '2025-03-28 16:38:59', 'ficheiros/media/index/default.png', NULL),
(8, 'ABC', 'grupo', 5, '2025-03-28 17:08:36', 'ficheiros/media/index/default.png', NULL),
(9, 'Test', 'grupo', 5, '2025-03-30 18:55:38', 'ficheiros/media/index/default.png', NULL),
(10, 'pluh', 'grupo', 5, '2025-03-30 19:42:18', 'ficheiros/media/index/default.png', NULL),
(11, 'Skibidi', 'grupo', 5, '2025-03-30 20:01:35', 'ficheiros/media/groups/67e9a31f1de9d_gfoyUJWR_400x400.jpg', NULL),
(12, 'Coding', 'grupo', 5, '2025-03-30 20:39:07', 'ficheiros/media/groups/67e9abebc22e8_coding-background-9izlympnd0ovmpli.jpg', NULL),
(13, 'Group', 'grupo', 5, '2025-03-31 18:53:01', 'ficheiros/media/groups/default_group_image.jpg', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
CREATE TABLE IF NOT EXISTS `mensagens` (
  `id_mensagem` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_conversa` bigint UNSIGNED NOT NULL,
  `id_remetente` bigint UNSIGNED DEFAULT NULL,
  `conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `enviado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_mensagem` enum('text','image','video','audio','system') COLLATE utf8mb4_general_ci DEFAULT 'text',
  PRIMARY KEY (`id_mensagem`),
  KEY `id_conversa` (`id_conversa`),
  KEY `id_remetente` (`id_remetente`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id_mensagem`, `id_conversa`, `id_remetente`, `conteudo`, `enviado_em`, `tipo_mensagem`) VALUES
(1, 1, 5, 'ola', '2025-03-25 21:37:03', 'text'),
(2, 1, 6, 'oi', '2025-03-25 21:37:19', 'text'),
(3, 1, 5, 'tudo bem?', '2025-03-25 21:38:27', 'text'),
(4, 1, 6, 'sim', '2025-03-25 21:38:34', 'text'),
(5, 1, 5, 'boa', '2025-03-25 21:46:04', 'text'),
(6, 1, 5, 'queres ouvir uma piada?', '2025-03-25 21:47:57', 'text'),
(7, 1, 6, 'nao, po crl', '2025-03-25 21:48:08', 'text'),
(8, 1, 5, 'bruh', '2025-03-25 21:49:27', 'text'),
(9, 1, 6, ';)', '2025-03-25 21:49:38', 'text'),
(10, 1, 5, 'a', '2025-03-25 22:08:18', 'text'),
(11, 1, 6, 'b', '2025-03-25 22:08:23', 'text'),
(12, 2, 5, 'a', '2025-03-25 22:08:34', 'text'),
(13, 2, 6, 'c', '2025-03-25 22:08:39', 'text'),
(14, 1, 5, 'HI', '2025-03-25 22:14:11', 'text'),
(15, 1, 6, 'HELLO', '2025-03-25 22:14:19', 'text'),
(16, 1, 5, 'AYO', '2025-03-25 22:16:21', 'text'),
(17, 1, 6, 'AYOOO', '2025-03-25 22:16:28', 'text'),
(18, 1, 5, 'A', '2025-03-25 22:17:13', 'text'),
(19, 1, 6, 'BB', '2025-03-25 22:17:20', 'text'),
(20, 1, 5, 'fack', '2025-03-25 22:19:34', 'text'),
(21, 1, 6, 'naaa', '2025-03-25 22:19:40', 'text'),
(22, 1, 5, 'bro', '2025-03-25 22:30:28', 'text'),
(23, 1, 6, 'yes?', '2025-03-25 22:30:39', 'text'),
(24, 1, 6, 'sdf', '2025-03-25 23:04:54', 'text'),
(25, 1, 5, 'sdf', '2025-03-25 23:05:58', 'text'),
(26, 1, 5, 'ss', '2025-03-25 23:05:58', 'text'),
(27, 1, 5, 'hi', '2025-03-25 23:06:26', 'text'),
(28, 1, 6, 'sup', '2025-03-25 23:06:29', 'text'),
(29, 1, 5, 'wtf', '2025-03-25 23:10:06', 'text'),
(30, 1, 6, 'yeah wtf', '2025-03-25 23:10:13', 'text'),
(31, 1, 5, 'yo', '2025-03-25 23:11:38', 'text'),
(32, 1, 6, 'sup dude', '2025-03-25 23:11:42', 'text'),
(33, 1, 5, 'wazzup', '2025-03-25 23:12:43', 'text'),
(34, 1, 6, 'wazuuuuppp!', '2025-03-25 23:12:48', 'text'),
(35, 2, 5, 'sup doods', '2025-03-25 23:15:38', 'text'),
(36, 2, 6, 'o meu nome e???', '2025-03-25 23:15:44', 'text'),
(37, 2, 5, 'WUANT', '2025-03-25 23:15:46', 'text'),
(38, 3, 5, 'AHH!', '2025-03-25 23:36:27', 'text'),
(39, 3, 6, 'WHAT?', '2025-03-25 23:36:37', 'text'),
(40, 1, 6, 'AS', '2025-03-26 08:36:02', 'text'),
(41, 1, 6, 'FDS', '2025-03-26 08:43:20', 'text'),
(42, 1, 5, 'BRO', '2025-03-26 08:43:25', 'text'),
(43, 1, 6, 'A', '2025-03-26 08:43:46', 'text'),
(44, 1, 6, 'B', '2025-03-26 08:43:46', 'text'),
(45, 1, 6, 'C', '2025-03-26 08:43:47', 'text'),
(46, 1, 6, 'D', '2025-03-26 08:43:47', 'text'),
(47, 1, 6, 'E', '2025-03-26 08:43:48', 'text'),
(48, 1, 6, 'F', '2025-03-26 08:43:48', 'text'),
(49, 1, 6, 'G', '2025-03-26 08:43:48', 'text'),
(50, 1, 6, 'HAHAHA', '2025-03-26 08:43:50', 'text'),
(51, 1, 6, 'YES!', '2025-03-26 08:43:52', 'text'),
(52, 1, 6, 'pluh', '2025-03-26 08:48:56', 'text'),
(53, 1, 5, 'pluh!', '2025-03-26 08:49:03', 'text'),
(54, 4, 5, 'sup', '2025-03-26 09:03:56', 'text'),
(55, 4, 6, 'hey', '2025-03-26 09:04:12', 'text'),
(56, 1, 5, 'some', '2025-03-26 10:41:07', 'text'),
(57, 1, 6, 'body', '2025-03-26 10:41:22', 'text'),
(58, 1, 5, 'OLA', '2025-03-26 11:03:39', 'text'),
(59, 1, 6, 'BOASSS', '2025-03-26 11:03:46', 'text'),
(60, 1, 6, 'oi?', '2025-03-26 11:15:14', 'text'),
(61, 1, 5, 'ent', '2025-03-26 11:15:27', 'text'),
(62, 1, 6, 'fixe', '2025-03-26 11:15:32', 'text'),
(63, 1, 6, 'oi', '2025-03-26 12:42:27', 'text'),
(64, 1, 6, 'ewew', '2025-03-26 12:42:28', 'text'),
(65, 1, 6, 'we', '2025-03-26 12:42:28', 'text'),
(66, 1, 6, 'ew', '2025-03-26 12:42:28', 'text'),
(67, 1, 6, 'we', '2025-03-26 12:42:29', 'text'),
(68, 1, 6, 'ew', '2025-03-26 12:42:29', 'text'),
(69, 1, 6, 'ew', '2025-03-26 12:42:29', 'text'),
(70, 1, 5, 'sadsda', '2025-03-26 12:42:31', 'text'),
(71, 1, 5, 'AOARPARA', '2025-03-26 12:42:35', 'text'),
(72, 1, 5, 'WHO LET THE DOGS OUTTT', '2025-03-26 12:42:52', 'text'),
(73, 1, 6, 'WOOF', '2025-03-26 12:42:55', 'text'),
(74, 1, 5, 'hi', '2025-03-26 14:24:28', 'text'),
(75, 1, 5, 'hii!', '2025-03-26 14:24:51', 'text'),
(76, 1, 5, 'a', '2025-03-26 14:26:29', 'text'),
(77, 1, 6, 'hey', '2025-03-27 08:37:54', 'text'),
(78, 1, 6, 'pluh', '2025-03-27 08:49:59', 'text'),
(79, 1, 5, 'bruh', '2025-03-27 08:50:03', 'text'),
(80, 1, 6, 'WAZAAAAAAAAAAAAAAAAAAP', '2025-03-27 08:59:28', 'text'),
(81, 1, 5, 'YOOOOOOOOOOOo', '2025-03-27 08:59:33', 'text'),
(82, 1, 6, 'a', '2025-03-27 09:20:21', 'text'),
(83, 1, 6, 'a', '2025-03-27 09:20:23', 'text'),
(84, 1, 6, 'a', '2025-03-27 09:21:21', 'text'),
(85, 1, 5, 'ya', '2025-03-27 09:21:28', 'text'),
(86, 1, 5, 'tranquilo', '2025-03-27 09:21:31', 'text'),
(87, 1, 6, 'ta favoravel', '2025-03-27 09:21:36', 'text'),
(88, 1, 5, 'asd', '2025-03-27 09:43:40', 'text'),
(89, 1, 6, 'heheh', '2025-03-27 09:43:47', 'text'),
(90, 1, 5, 'hi', '2025-03-27 18:29:05', 'text'),
(91, 1, 6, 'bro wtf', '2025-03-27 18:43:27', 'text'),
(92, 1, 6, 'aasddasdasdasdasd', '2025-03-27 18:43:55', 'text'),
(93, 1, 6, 'kaka', '2025-03-27 19:17:55', 'text'),
(94, 1, 6, 'lalalalala elmos worldddddd', '2025-03-27 19:29:51', 'text'),
(95, 1, 6, 'no', '2025-03-27 19:29:56', 'text'),
(96, 1, 5, 'heyyyyy', '2025-03-27 19:48:15', 'text'),
(97, 1, 6, 'hhjjh', '2025-03-27 19:48:21', 'text'),
(98, 4, 5, 'hii!', '2025-03-27 23:01:31', 'text'),
(99, 1, 5, 'hi', '2025-03-27 23:01:45', 'text'),
(100, 1, 5, 'abc', '2025-03-27 23:01:48', 'text'),
(101, 5, 5, 'wazzap!', '2025-03-27 23:32:11', 'text'),
(102, 1, 6, 'a', '2025-03-27 23:55:54', 'text'),
(103, 5, 5, 'a', '2025-03-28 00:03:49', 'text'),
(104, 1, 5, 'b', '2025-03-28 00:17:05', 'text'),
(105, 1, 5, 'a', '2025-03-28 00:17:47', 'text'),
(106, 1, 5, 'c', '2025-03-28 00:17:48', 'text'),
(107, 1, 5, 'asdadsads', '2025-03-28 00:17:50', 'text'),
(108, 1, 5, 'der', '2025-03-28 00:17:57', 'text'),
(109, 1, 5, 'abc', '2025-03-28 00:18:00', 'text'),
(110, 4, 5, 'asd~', '2025-03-28 01:02:02', 'text'),
(111, 4, 5, 'a', '2025-03-28 01:02:04', 'text'),
(112, 6, 5, 'hey', '2025-03-28 16:46:23', 'text'),
(113, 6, 5, 'chicken jockey', '2025-03-28 16:55:18', 'text'),
(114, 6, 5, 'uploads/67e8133705023.png', '2025-03-29 15:35:19', 'image'),
(115, 6, 5, 'uploads/67e813fa30bb3.jpg', '2025-03-29 15:38:34', 'image'),
(116, 6, 6, 'cool images', '2025-03-29 15:54:57', 'text'),
(117, 6, 5, 'thanks dude', '2025-03-29 15:55:06', 'text'),
(118, 6, 6, 'np', '2025-03-29 15:55:11', 'text'),
(119, 6, 6, 'uploads/67e817f4116d6.png', '2025-03-29 15:55:32', 'image'),
(120, 6, 5, 'ahaha', '2025-03-29 15:55:36', 'text'),
(121, 6, 6, 'uploads/67e817fe617b6.png', '2025-03-29 15:55:42', 'image'),
(122, 6, 5, 'uploads/67e8180e17797.jpg', '2025-03-29 15:55:58', 'image'),
(123, 6, 5, 'nice', '2025-03-29 15:56:02', 'text'),
(124, 6, 6, 'yes its nice', '2025-03-29 15:56:08', 'text'),
(125, 6, 6, 'uploads/67e81827b7348.jpg', '2025-03-29 15:56:23', 'image'),
(126, 6, 5, 'LOOOOL', '2025-03-29 15:56:27', 'text'),
(127, 6, 5, 'uploads/67e818b0216a8.mp3', '2025-03-29 15:58:40', 'audio'),
(128, 6, 6, 'uploads/67e818f4f3c2c.mp4', '2025-03-29 15:59:49', 'video'),
(129, 6, 5, 'a', '2025-03-29 16:11:42', 'text'),
(130, 6, 6, 'fds', '2025-03-29 16:12:09', 'text'),
(131, 6, 5, 'e a casa dos youtubers!', '2025-03-29 23:08:06', 'text'),
(132, 6, 5, 'ate ficas a bater mal!', '2025-03-29 23:08:13', 'text'),
(133, 6, 5, 'ola?', '2025-03-30 14:06:29', 'text'),
(134, 6, 5, 'yeejk', '2025-03-30 14:21:18', 'text'),
(135, 1, 6, 'AAAAA', '2025-03-30 14:26:23', 'text'),
(136, 1, 5, 'bar', '2025-03-30 14:30:08', 'text'),
(137, 6, 5, 'hellooo', '2025-03-30 14:37:31', 'text'),
(138, 6, 5, 'tu ccha cha tu tu cha', '2025-03-30 14:37:58', 'text'),
(139, 6, 6, 'lalala. lalala!', '2025-03-30 14:41:03', 'text'),
(140, 6, 5, 'what', '2025-03-30 16:19:34', 'text'),
(141, 6, 6, 'wassap', '2025-03-30 16:20:39', 'text'),
(142, 6, 6, 'OMG', '2025-03-30 16:20:42', 'text'),
(143, 6, 5, 'HAHA', '2025-03-30 16:20:49', 'text'),
(144, 6, 6, 'YESSSSS', '2025-03-30 16:20:53', 'text'),
(145, 6, 5, 'glu', '2025-03-30 16:21:27', 'text'),
(146, 6, 5, 'uploads/67e96f9611b58.jpg', '2025-03-30 16:21:44', 'image'),
(147, 6, 6, 'hugvhfufu', '2025-03-30 16:22:43', 'text'),
(148, 6, 5, 'zzzszzzsszsz', '2025-03-30 16:22:56', 'text'),
(149, 6, 6, 'Hi there', '2025-03-30 18:46:46', 'text'),
(150, 6, 5, 'hey', '2025-03-30 18:48:39', 'text'),
(151, 6, 5, 'wassup dude', '2025-03-30 18:48:44', 'text'),
(152, 6, 6, 'sup', '2025-03-30 18:48:52', 'text'),
(153, 6, 6, 'I\'m good and you?', '2025-03-30 18:48:58', 'text'),
(154, 6, 5, 'im doiing well!', '2025-03-30 18:49:06', 'text'),
(155, 6, 5, 'kumalala', '2025-03-30 18:58:15', 'text'),
(156, 6, 6, 'uploads/67e994545f3af.mp4', '2025-03-30 18:58:30', 'video'),
(157, 9, 6, 'Sup', '2025-03-30 19:03:23', 'text'),
(158, 9, 5, 'Sup!', '2025-03-30 19:03:29', 'text'),
(159, 9, 6, 'Working?', '2025-03-30 19:03:38', 'text'),
(160, 9, 5, 'Yes! Let me refresh...', '2025-03-30 19:03:46', 'text'),
(161, 9, 5, 'Working!', '2025-03-30 19:03:58', 'text'),
(162, 9, 5, 'Now im leaving', '2025-03-30 19:05:46', 'text'),
(163, 9, 6, 'nooo...', '2025-03-30 19:05:51', 'text'),
(164, 9, 6, ':( bye', '2025-03-30 19:06:22', 'text'),
(165, 6, 5, 'WHY U LEAVE THE GROUP MAN', '2025-03-30 19:06:46', 'text'),
(166, 6, 6, '>:)', '2025-03-30 19:07:06', 'text'),
(167, 6, 5, '🩻🩻', '2025-03-30 19:07:51', 'text'),
(168, 6, 6, '💀💀', '2025-03-30 19:08:07', 'text'),
(169, 6, 5, 'como e que ee', '2025-03-30 19:08:53', 'text'),
(170, 6, 5, 'oh no', '2025-03-30 19:09:03', 'text'),
(171, 6, 6, '?', '2025-03-30 19:09:26', 'text'),
(172, 6, 5, 'nothin', '2025-03-30 19:09:31', 'text'),
(173, 6, 6, 'pluh', '2025-03-30 19:19:16', 'text'),
(174, 6, 6, 'wazzaaap!', '2025-03-30 19:53:52', 'text'),
(175, 6, 6, 'uploads/67e9a168aaf0e.mp4', '2025-03-30 19:54:18', 'video'),
(176, 6, 5, 'lol', '2025-03-30 19:54:53', 'text'),
(177, 6, 6, 'uploads/67e9a1d3b7ed6.mp4', '2025-03-30 19:56:05', 'video'),
(178, 11, 6, 'Kazzio?', '2025-03-30 20:10:00', 'text'),
(179, 11, 5, 'n', '2025-03-30 20:10:07', 'text'),
(180, 11, 6, 'Po crl', '2025-03-30 20:22:18', 'text'),
(183, 11, NULL, '🚪 Thomaz123 saiu da conversa', '2025-03-30 20:34:30', 'system'),
(184, 11, NULL, '🚪 Cortez123 saiu da conversa', '2025-03-30 20:35:20', 'system'),
(185, 12, 5, 'we love coding!', '2025-03-30 20:39:18', 'text'),
(186, 6, 5, '💀💀💀💀💀', '2025-03-30 23:00:47', 'text'),
(187, 6, 5, 'yaya', '2025-03-31 18:45:03', 'text'),
(188, 6, 5, 'hmm', '2025-03-31 18:52:38', 'text'),
(189, 13, 5, 'abc', '2025-03-31 19:02:23', 'text'),
(190, 13, 5, 'def', '2025-03-31 19:02:24', 'text'),
(191, 13, 5, 'gasd', '2025-03-31 19:02:25', 'text'),
(192, 13, NULL, 'Thomaz123 saiu da conversa.', '2025-03-31 19:02:29', 'system'),
(193, 13, 7, 'asd', '2025-03-31 19:03:41', 'text'),
(194, 13, 7, 'WHY', '2025-03-31 19:03:43', 'text'),
(195, 13, 7, 'AHHHHHHHHHHHH', '2025-03-31 19:03:45', 'text'),
(196, 13, 7, ':(((((((((((((', '2025-03-31 19:03:47', 'text'),
(197, 13, NULL, 'Faria123 saiu da conversa.', '2025-03-31 19:03:51', 'system'),
(198, 5, 7, 'pluh', '2025-03-31 19:06:46', 'text'),
(199, 5, NULL, 'Faria123 saiu da conversa.', '2025-03-31 19:06:54', 'system'),
(200, 6, 5, 'heyooo', '2025-04-01 00:22:57', 'text');

-- --------------------------------------------------------

--
-- Estrutura da tabela `participantes_conversa`
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `participantes_conversa`
--

INSERT INTO `participantes_conversa` (`id_participante`, `id_conversa`, `id_utilizador`, `entrou_em`) VALUES
(12, 5, 10, '2025-03-27 23:32:07'),
(13, 6, 5, '2025-03-28 16:38:59'),
(14, 6, 6, '2025-03-28 16:38:59'),
(24, 12, 6, '2025-03-30 20:39:07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Token hashado com SHA-256',
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_token` (`token`),
  KEY `idx_expiration` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome_utilizador`, `palavra_passe`, `email`, `imagem_perfil`, `descricao`, `estado`, `ultima_atividade`, `criado_em`, `atualizado_em`) VALUES
(5, 'Thomaz123', '$2y$10$7uREzJO4RmXa6A7a3tlPe.r.PKAA.3JnDSDVjWuiwBL3ITmbGfsFO', 'thomazbarrago@gmail.com', 'ficheiros/media/profiles/profile_67eb31624414d.jpg', 'Heya!', 'online', '2025-04-01 00:24:48', '2025-03-25 20:51:30', '2025-04-01 00:24:48'),
(6, 'Cortez123', '$2y$10$HTwHwJSFrvaSqx/PI70LBO55py4GvWoF5hkvrLY7STubYWVkESfQe', 'cortez123@gmail.com', 'ficheiros/media/profiles/67e317969bacf.png', NULL, 'offline', '2025-03-25 20:52:38', '2025-03-25 20:52:38', '2025-03-25 20:52:38'),
(7, 'Faria123', '$2y$10$h7uzcOJzZYGlgSXNL/5Z3.8w7b3VG3Vhs8fBUR2.JshjIJWa.yyzO', 'Faria123@gmail.com', 'ficheiros/media/index/default.png', NULL, 'offline', '2025-03-26 15:03:00', '2025-03-26 15:03:00', '2025-03-26 15:03:00'),
(10, 'Barrago123', '$2y$10$8wKuGosMbam/XXPoBgk21.mcI8XiOoeDCfWmY8Ti3drBDHULQpAm2', 'barrago123@gmail.com', 'ficheiros/media/index/default.png', NULL, 'offline', '2025-03-26 15:38:16', '2025-03-26 15:38:16', '2025-03-26 15:38:16'),
(11, 'contaio123', '$2y$10$o3oPATpCZVkp6RLGkfs5bOGe9F7NrGTfLBVVLOfdM2.HdhAi3ferC', 'contaio123@gmail.com', 'ficheiros/media/profiles/default_profile_image.jpg', NULL, 'offline', '2025-03-29 22:14:27', '2025-03-29 22:14:27', '2025-03-29 22:14:27'),
(12, 'nigger', '$2y$10$Idzy9/KvANWb/vmbAYH9yey7ngd2f3Z70hlbEa461eO7NLJEnOkxG', 'mvieiras2009@hotmail.com', 'uploads/1743361089_Dado.png', '', 'offline', '2025-03-30 18:58:09', '2025-03-30 18:57:29', '2025-03-30 18:57:29');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `conversas`
--
ALTER TABLE `conversas`
  ADD CONSTRAINT `conversas_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_remetente`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Limitadores para a tabela `participantes_conversa`
--
ALTER TABLE `participantes_conversa`
  ADD CONSTRAINT `participantes_conversa_ibfk_1` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  ADD CONSTRAINT `participantes_conversa_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Limitadores para a tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`email`) REFERENCES `utilizadores` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
