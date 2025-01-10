-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2024 às 11:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-3:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sismarinas`
--
CREATE DATABASE IF NOT EXISTS `sismarinas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sismarinas`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `embarcacoes`
--

CREATE TABLE `embarcacoes` (
  `id` int(11) NOT NULL,
  `id_marina` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_serie` varchar(50) DEFAULT NULL,
  `tipo` enum('Embarcação','Motoaquática') NOT NULL,
  `observacao` varchar(200) DEFAULT NULL,
  `dt_validade` date DEFAULT NULL,
  `status` enum('REGULAR','IRREGULAR') DEFAULT 'REGULAR',
  `proporcao_motor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `marinas`
--

CREATE TABLE `marinas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `dt_validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `embarcacoes`
--
ALTER TABLE `embarcacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_marina` (`id_marina`);

--
-- Índices de tabela `marinas`
--
ALTER TABLE `marinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_cnpj` (`cnpj`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `embarcacoes`
--
ALTER TABLE `embarcacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `marinas`
--
ALTER TABLE `marinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `embarcacoes`
--
ALTER TABLE `embarcacoes`
  ADD CONSTRAINT `embarcacoes_ibfk_1` FOREIGN KEY (`id_marina`) REFERENCES `marinas` (`id`) ON DELETE CASCADE;

INSERT INTO `marinas` (`id`, `nome`, `endereco`, `contato`, `cnpj`, `dt_validade`) VALUES
(7, 'EMB / MTA NÃO GARAGIADAS', NULL, NULL, NULL, NULL);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
