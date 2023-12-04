-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/12/2023 às 08:55
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chat`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nome_grupo` varchar(50) NOT NULL,
  `criado` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `conteudo` text NOT NULL,
  `enviado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(300) NOT NULL,
  `tipo` varchar(10) DEFAULT 'regular',
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `data_registro`, `atualizado`) VALUES
(1, 'Samara', 'samara180305@gmail.com', '$2y$10$yWiTxd1fFOzDvWCNkaxsHehrC.5Y8AgMu4vCfTMX9ozUDyO5GuWBW', 'regular', '2023-12-01 17:26:55', '2023-12-01 17:27:27'),
(3, 'Oliveira', 'oliveira@gmail.com', 'nicolle1712', 'ADM', '2023-12-04 07:19:46', '2023-12-04 07:19:46'),
(4, 'Alex', 'Alex@gmail.com', '$2y$10$1vA0vAD8JSIiu6CfV5l64u5maHBkOYbbn3ZMkdjwWhjhRIx2lSTxG', 'regular', '2023-12-04 07:30:08', '2023-12-04 07:30:08');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensagens_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
