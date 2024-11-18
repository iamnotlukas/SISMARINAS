SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

CREATE TABLE `marinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL UNIQUE,
  `dt_validade` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `embarcacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_marina` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_serie` varchar(50) NOT NULL UNIQUE,
  `tipo` enum('Embarcação','Motoaquática') NOT NULL,
  `observacao` varchar(200) DEFAULT NULL,
  `dt_validade` date DEFAULT NULL,
  `status` enum('LEGAL','ILEGAL') DEFAULT 'LEGAL',
  `proporcao_motor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_marina` (`id_marina`),
  CONSTRAINT `embarcacoes_ibfk_1` FOREIGN KEY (`id_marina`) REFERENCES `marinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `marinas` (`id`, `nome`, `endereco`, `contato`, `cnpj`, `dt_validade`) VALUES
(7, 'EMB / MTA NÃO GARAGIADAS', NULL, NULL, NULL, NULL);

COMMIT;
