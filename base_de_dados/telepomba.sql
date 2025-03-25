-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 25-Mar-2025 às 09:39
-- Versão do servidor: 8.0.31
-- versão do PHP: 8.2.0

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
-- Estrutura da tabela `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id_utilizador` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_utilizador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `palavra_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imagem_perfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `estado` enum('online','ausente','ocupado','offline') COLLATE utf8mb4_general_ci DEFAULT 'offline',
  `ultima_atividade` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilizador`),
  UNIQUE KEY `id_utilizador` (`id_utilizador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome_utilizador`, `palavra_passe`, `email`, `imagem_perfil`, `descricao`, `estado`, `ultima_atividade`, `criado_em`, `atualizado_em`) VALUES
(2, 'pomb', '123', '123@gmail.com', NULL, 'o lourenço é goei', 'online', NULL, '2025-03-21 11:14:24', '2025-03-21 11:14:24'),
(3, '123', '$2y$10$QMioboOm5lCEjUgR39RpD.98mRmR1y/DFHIRsnJPHllasSuNG.Xyy', 'pomba@gmail.com', NULL, NULL, 'online', NULL, '2025-03-21 11:16:37', '2025-03-21 11:16:37'),
(4, 'joe', '$2y$10$1YVxllR6OCkgfU2LzTwrB.AmSmVpY8tOl3HJo7qm63ru2jhc./qzO', 'joe@gmail.com', NULL, NULL, 'online', NULL, '2025-03-24 08:51:30', '2025-03-24 08:51:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
