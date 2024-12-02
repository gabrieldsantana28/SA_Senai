-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/12/2024 às 18:18
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
-- Banco de dados: `gerenciador_estoque`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL COMMENT 'Identificador da tabela compra',
  `data_compra` date NOT NULL COMMENT 'Data da compra',
  `hora_compra` varchar(10) NOT NULL COMMENT 'Hora da compra',
  `produto_compra` varchar(30) NOT NULL COMMENT 'Produto comprado',
  `quantidade_compra` int(11) NOT NULL COMMENT 'Quantidade do produto comprado',
  `preco_compra` float NOT NULL COMMENT 'Preço da compra',
  `tipo_pagamento_compra` varchar(30) NOT NULL COMMENT 'Tipo de pagamento da compra',
  `fk_id_fornecedor` int(11) NOT NULL COMMENT 'Chave estrangeira do identificador da tabela de fornecedor\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compra`
--

INSERT INTO `compra` (`id_compra`, `data_compra`, `hora_compra`, `produto_compra`, `quantidade_compra`, `preco_compra`, `tipo_pagamento_compra`, `fk_id_fornecedor`) VALUES
(4, '2024-11-26', '11:30', 'Camisa', 5, 89, 'Parcelado', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id_fornecedor` int(11) NOT NULL COMMENT 'Identificador do fornecedor',
  `nome_fornecedor` varchar(50) NOT NULL COMMENT 'Nome do fornecedor',
  `telefone_fornecedor` varchar(30) NOT NULL COMMENT 'Telefone do fornecedor',
  `material_fornecedor` varchar(30) NOT NULL COMMENT 'Material fornecido pelo fornecedor',
  `endereco_fornecedor` varchar(70) NOT NULL COMMENT 'Endereço do fornecedor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id_fornecedor`, `nome_fornecedor`, `telefone_fornecedor`, `material_fornecedor`, `endereco_fornecedor`) VALUES
(1, 'GABRIEL LINHOS', '47984659021', 'LINHOS', 'Rua 123'),
(2, 'Thauã', '(48) 99999-9999', 'Estampas', 'Rua 321');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL COMMENT 'Identificador de produto',
  `descricao_produto` varchar(100) NOT NULL COMMENT 'Descrição de Produto',
  `tamanho_produto` varchar(5) NOT NULL COMMENT 'Tamanho do produto',
  `cor_produto` varchar(20) NOT NULL COMMENT 'Cor do produto',
  `preco_produto` float NOT NULL COMMENT 'Preço do produto',
  `quantidade_produto` int(11) NOT NULL COMMENT 'Quantidade do produto',
  `nome_produto` varchar(35) NOT NULL COMMENT 'Nome do produto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `descricao_produto`, `tamanho_produto`, `cor_produto`, `preco_produto`, `quantidade_produto`, `nome_produto`) VALUES
(2, 'Camiseta StreetWear COMP', 'M', 'Azul', 59.99, 19, 'Camiseta Oversized'),
(3, 'Calça Cargo COMP', 'P', 'Bege', 99.99, 7, 'Calça Cargo'),
(4, 'Calça Reta COMP', 'M', 'Preta', 105.99, 5, 'Calça Reta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador do usuário',
  `user_usuario` varchar(25) NOT NULL COMMENT 'User do usuário',
  `nivel_usuario` int(11) NOT NULL COMMENT 'Nível do usuário',
  `senha_usuario` varchar(25) NOT NULL COMMENT 'Senha do usuário',
  `nome_usuario` varchar(50) NOT NULL COMMENT 'Nome do usuário',
  `email_usuario` varchar(50) NOT NULL COMMENT 'E-mail do usuário'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `user_usuario`, `nivel_usuario`, `senha_usuario`, `nome_usuario`, `email_usuario`) VALUES
(1, 'gabriel', 1, '123', 'Gabriel Luis de Santana', 'gabrielsantana@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id_venda` int(11) NOT NULL COMMENT 'Identificador da venda',
  `quantidade_venda` int(11) NOT NULL COMMENT 'Quantidade de venda',
  `tipo_pagamento_venda` varchar(15) NOT NULL COMMENT 'Tipo de pagamento da venda',
  `data_venda` date NOT NULL COMMENT 'Data da venda',
  `hora_venda` varchar(10) NOT NULL COMMENT 'Hora da venda',
  `preco_venda` float NOT NULL COMMENT 'Preço da venda',
  `fk_id_produto` int(11) NOT NULL COMMENT 'Chave estrangeira para identificar a tabela produto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda`
--

INSERT INTO `venda` (`id_venda`, `quantidade_venda`, `tipo_pagamento_venda`, `data_venda`, `hora_venda`, `preco_venda`, `fk_id_produto`) VALUES
(5, 4, 'PIX', '2024-09-18', '20:47', 0, 3),
(6, 1, 'Dinheiro/À vist', '2024-11-26', '10:58', 0, 2),
(7, 1, 'Boleto', '2024-11-20', '16:13', 0, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `fk_id_fornecedor` (`fk_id_fornecedor`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id_fornecedor`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `fk_id_produto` (`fk_id_produto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador da tabela compra', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do fornecedor', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de produto', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do usuário', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador da venda', AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`fk_id_fornecedor`) REFERENCES `fornecedor` (`id_fornecedor`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`fk_id_produto`) REFERENCES `produto` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
