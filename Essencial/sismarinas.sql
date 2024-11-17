

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `embarcacoes` (
  `id` int(11) NOT NULL,
  `id_marina` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_serie` varchar(50) DEFAULT NULL,
  `tipo` enum('Embarcação','Motoaquática') NOT NULL,
  `observacao` varchar(200) DEFAULT NULL,
  `dt_validade` date DEFAULT NULL,
  `status` enum('LEGAL','ILEGAL') DEFAULT 'LEGAL',
  `proporcao_motor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `marinas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `dt_validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `marinas` (`id`, `nome`, `endereco`, `contato`, `cnpj`, `dt_validade`) VALUES

(7, 'EMB / MTA NÃO GARAGIADAS', NULL, NULL, NULL, NULL),
--

--
ALTER TABLE `embarcacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_marina` (`id_marina`);

--
ALTER TABLE `marinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_cnpj` (`cnpj`);

--
ALTER TABLE `embarcacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `marinas`
--
ALTER TABLE `marinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
--
ALTER TABLE `embarcacoes`
  ADD CONSTRAINT `embarcacoes_ibfk_1` FOREIGN KEY (`id_marina`) REFERENCES `marinas` (`id`) ON DELETE CASCADE; --alteração apra quando a marina for exluida suas respectivas embracações também serão excluidas
COMMIT;


