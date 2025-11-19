SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Banco de dados: `pap_simao`

-- Estrutura da tabela para `categorias`
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados na tabela `categorias`
INSERT INTO `categorias` (`id`, `nome_categoria`) VALUES
(1, 'Hoodies'),
(2, 'Equipamentos'),
(3, 'T-shirts'),
(4, 'Fatos de treino'),
(5, 'Outros');

-- Estrutura da tabela para `produtos`
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_imagem` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `link_detalhes` varchar(255),
  PRIMARY KEY (`id_imagem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados na tabela `produtos`
INSERT INTO `produtos` (`id_imagem`, `titulo`, `descricao`, `preco`, `imagem`, `link_detalhes`) VALUES
(7, 'Cachecol azul (Kelme)', 'Masculino/Feminino', 8.00, './img/produtos_loja/cachecol 2.jpg', NULL),
(8, 'Hoodie azul (Alcateia Azul)', 'Masculino', 40.00, './img/produtos_loja/hoodie1_costas.jpg', 'produtos_detalhes/hoodie_alcateia_azul.php'),
(9, 'Hoodie azul (vilanovenses)', 'Masculino', 40.00, './img/produtos_loja/hoodie1_costas2.jpg', 'produtos_detalhes/hoodie_vilanovenses_azul.php'),
(10, 'Hoodie rosa (Alcateia Azul)', 'Feminino', 40.00, './img/produtos_loja/hoodie2_costas.jpg', 'produtos_detalhes/hoodie_alcateia_rosa.php'),
(11, 'Hoodie rosa (vilanovenses)', 'Feminino', 40.00, './img/produtos_loja/hoodie2_costas2.jpg', 'produtos_detalhes/hoodie_vilanovenses_rosa.php'),
(12, 'Equipamento Juvan (Benjamins)', 'Masculino', 45.00, './img/produtos_loja/equi_juvan_camisola.jpg', 'produtos_detalhes/equipamento_juvan.php'),
(13, 'Equipamento Kelme (Principal)', 'Masculino', 65.00, './img/produtos_loja/equi_kelme_camisola.jpg', 'produtos_detalhes/equipamento_kelme_azul.php'),
(14, 'Equipamento Kelme (Secundário)', 'Masculino', 65.00, './img/produtos_loja/equi_kelme_camisola_branco.jpg', 'produtos_detalhes/equipamento_kelme_branco.php'),
(15, 'Camisola Juvan (Benjamins)', 'Masculino', 30.00, './img/produtos_loja/equi_juvan_camisola.jpg', NULL),
(16, 'Camisola Kelme (Principal)', 'Masculino', 40.00, './img/produtos_loja/equi_kelme_camisola.jpg', NULL),
(17, 'Camisola Kelme (Secundário)', 'Masculino', 40.00, './img/produtos_loja/equi_kelme_camisola_branco.jpg', NULL),
(18, 'Polo azul ou branco (Kelme)', 'Masculino', 25.00, './img/produtos_loja/polo azule branco.jpg', NULL),
(19, 'T-shirt azul (Os vilanovenses)', 'Masculino/Feminino', 20.00, './img/produtos_loja/t-shirt 1.jpg', NULL),
(20, 'T-shirt cinza (Os vilanovenses)', 'Masculino/Feminino', 20.00, './img/produtos_loja/t-shirt 2.jpg', NULL),
(21, 'T-shirt preta (1935)', 'Masculino/Feminino', 20.00, './img/produtos_loja/t-shirt 3.jpg', NULL),
(22, 'T-shirt cinza (1935)', 'Masculino/Feminino', 20.00, './img/produtos_loja/t-shirt 5.jpg', NULL),
(23, 'T-shirt azul (Eu sou vilanovenses)', 'Masculino/Feminino', 20.00, './img/produtos_loja/t-shirt 4.jpg', NULL),
(24, 'Fato de treino (Juvan/Benjamins)', 'Masculino', 75.00, './img/produtos_loja/fato_treino_frente_quadrado.jpg', 'produtos_detalhes/fato_treino.php'),
(25, 'Galhardete azul (CFV)', 'Galhardete', 8.00, './img/produtos_loja/GALHARDETE.jpg', NULL);

-- Atualização da tabela produtos para incluir a coluna categoria
UPDATE `produtos` SET `categoria` = 'Hoodies' WHERE `id_imagem` IN (8, 9, 10, 11);
UPDATE `produtos` SET `categoria` = 'Equipamentos' WHERE `id_imagem` IN (12, 13, 14, 15, 16, 17);
UPDATE `produtos` SET `categoria` = 'T-shirts' WHERE `id_imagem` IN (18, 19, 20, 21, 22, 23);
UPDATE `produtos` SET `categoria` = 'Fatos de treino' WHERE `id_imagem` = 24;
UPDATE `produtos` SET `categoria` = 'Outros' WHERE `id_imagem` IN (7, 25);

COMMIT;

CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id_utilizador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `morada` varchar(300) NOT NULL,
  `codigo_postal` int(10) NOT NULL,
  `porta` int(5) NOT NULL,
  `iban` varchar(34) NOT NULL, 
  PRIMARY KEY (`id_utilizador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `utilizadores` (`id_utilizador`, `nome`, `email`, `senha`) VALUES
(1, 'admin', 'clubedefutebolosvilanovenses@gmail.com', 'adminmaster');
INSERT INTO `utilizadores` (`id_utilizador`, `nome`, `sobrenome`, `email`,`telefone`, `senha`,`morada`, `codigo_postal`, `porta`, `iban`) VALUES
(2, 'teste', 'teste', 'teste@gmail.com', '123456789', '123', 'Lagarinhos', '6904-065', '15', '1111111');


CREATE TABLE orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizador INT,
    order_date DATETIME,
    total_amount DECIMAL(10, 2),
    estado VARCHAR(200) NOT NULL,
    opcao_pagamento ENUM('Transferência', 'MBWAY') NOT NULL DEFAULT 'Transferência',
    FOREIGN KEY (id_utilizador) REFERENCES utilizadores(id_utilizador)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    titulo VARCHAR(255),
    quantidade INT,
    tamanho VARCHAR(50),
    preco_unitario DECIMAL(10, 2),
    preco_total DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id_order)
);

SELECT o.*, GROUP_CONCAT(oi.titulo SEPARATOR ', ') as product_titles
FROM orders o
LEFT JOIN order_items oi ON o.id_order = oi.order_id
GROUP BY o.id_order;