-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/05/2026 às 06:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mapa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_horario`
--

CREATE TABLE `tbl_horario` (
  `codigo` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT '',
  `hora_ini` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `dtcria` datetime DEFAULT current_timestamp(),
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_mapa`
--

CREATE TABLE `tbl_mapa` (
  `codigo` int(11) NOT NULL,
  `datareserva` date DEFAULT NULL,
  `sala` int(11) DEFAULT 0,
  `codigo_horario` int(11) DEFAULT 0,
  `codigo_turma` int(11) DEFAULT 0,
  `codigo_professor` int(11) DEFAULT 0,
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_professor`
--

CREATE TABLE `tbl_professor` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT '',
  `cpf` varchar(11) DEFAULT '',
  `tipo` char(1) DEFAULT 'F',
  `dtcria` datetime DEFAULT current_timestamp(),
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_sala`
--

CREATE TABLE `tbl_sala` (
  `codigo` int(11) NOT NULL,
  `descricao` varchar(30) DEFAULT '',
  `andar` int(11) DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `dtcria` datetime DEFAULT current_timestamp(),
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_turma`
--

CREATE TABLE `tbl_turma` (
  `codigo` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT '',
  `capacidade` int(11) DEFAULT 0,
  `dataInicio` date DEFAULT NULL,
  `dtcria` datetime DEFAULT current_timestamp(),
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `dtcria` datetime DEFAULT current_timestamp(),
  `estatus` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_horario`
--
ALTER TABLE `tbl_horario`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `tbl_mapa`
--
ALTER TABLE `tbl_mapa`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `sala` (`sala`),
  ADD KEY `codigo_horario` (`codigo_horario`),
  ADD KEY `codigo_turma` (`codigo_turma`),
  ADD KEY `codigo_professor` (`codigo_professor`);

--
-- Índices de tabela `tbl_professor`
--
ALTER TABLE `tbl_professor`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `tbl_sala`
--
ALTER TABLE `tbl_sala`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `tbl_turma`
--
ALTER TABLE `tbl_turma`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_horario`
--
ALTER TABLE `tbl_horario`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_mapa`
--
ALTER TABLE `tbl_mapa`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_professor`
--
ALTER TABLE `tbl_professor`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_turma`
--
ALTER TABLE `tbl_turma`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_mapa`
--
ALTER TABLE `tbl_mapa`
  ADD CONSTRAINT `tbl_mapa_ibfk_1` FOREIGN KEY (`sala`) REFERENCES `tbl_sala` (`codigo`),
  ADD CONSTRAINT `tbl_mapa_ibfk_2` FOREIGN KEY (`codigo_horario`) REFERENCES `tbl_horario` (`codigo`),
  ADD CONSTRAINT `tbl_mapa_ibfk_3` FOREIGN KEY (`codigo_turma`) REFERENCES `tbl_turma` (`codigo`),
  ADD CONSTRAINT `tbl_mapa_ibfk_4` FOREIGN KEY (`codigo_professor`) REFERENCES `tbl_professor` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
