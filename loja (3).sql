-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/04/2026 às 16:07
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
-- Banco de dados: `loja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `endereco_cliente` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `cpf`, `endereco_cliente`) VALUES
(11, 'Dymas Kawam Batista', '516237038', 11),
(12, 'Dymas Kawam Batista', '516237038', 13),
(13, 'Felipe Martins Silva', '64746872343', 14),
(14, 'Rafael Vieira Andrade', '80500978820', 15),
(15, 'Fernanda Oliveira Machado', '21913619399', 16),
(16, 'Pedro Barbosa Oliveira', '69985435346', 17),
(17, 'Guilherme Dias Rocha', '47510799118', 18),
(18, 'Thiago Sousa Lima', '25135427849', 19),
(19, 'Débora Rocha Sousa', '08412411824', 20),
(20, 'Vinícius Barbosa Alves', '53487401640', 21),
(21, 'Carlos Costa Moreira', '24278680112', 22),
(22, 'Camila Santos Marques', '59826204505', 23),
(23, 'Thiago Dias Pereira', '15869232260', 24),
(24, 'Eduardo Andrade Freitas', '56342160733', 25),
(25, 'Viviane Freitas Almeida', '54330365414', 26),
(26, 'Fábio Rocha Sousa', '68501429401', 27),
(27, 'Amanda Carvalho Ribeiro', '56981693406', 28),
(28, 'Carlos Sousa Freitas', '83561595148', 29),
(29, 'Henrique Dias Carvalho', '56482366299', 30),
(30, 'Henrique Martins Fernandes', '04436995777', 31),
(31, 'Gabriela Alves Sousa', '72148951343', 32),
(32, 'Débora Alves Rodrigues', '00379176936', 33),
(33, 'Maria Martins Pereira', '20163287083', 34),
(34, 'Carolina Machado Souza', '72788957986', 35),
(35, 'Mônica Freitas Fernandes', '72774348734', 36),
(36, 'Marcelo Oliveira Nascimento', '43455812236', 37),
(37, 'Bruna Rodrigues Nascimento', '31665876036', 38),
(38, 'Paulo Mendes Moreira', '90967054668', 39),
(39, 'Cristina Andrade Fernandes', '93734670656', 40),
(40, 'Renata Ferreira Marques', '72980699016', 41),
(41, 'Guilherme Machado Almeida', '20465375564', 42),
(42, 'Daniela Marques Carvalho', '41708053100', 43),
(43, 'Mateus Alves Marques', '09232719374', 44),
(44, 'Priscila Ribeiro Ferreira', '99124190496', 45),
(45, 'Roberto Nascimento Alves', '19314919058', 46),
(46, 'Antônio Dias Ribeiro', '18506716572', 47),
(47, 'Antônio Ferreira Andrade', '84987769453', 48),
(48, 'Mônica Freitas Oliveira', '47379965075', 49),
(49, 'Eduardo Lopes Alves', '54549480831', 50),
(50, 'Mateus Andrade Carvalho', '78377701436', 51),
(51, 'Bruna Pereira Gomes', '95788568557', 52),
(52, 'Leonardo Gomes Lima', '31351823374', 53),
(53, 'Renata Vieira Moreira', '89413435240', 54),
(54, 'Tatiane Fernandes Rodrigues', '40084271094', 55),
(55, 'Ana Lopes Almeida', '52047116719', 56),
(56, 'Letícia Dias Santos', '22941318699', 57),
(57, 'Carla Barbosa Pereira', '86774964990', 58),
(58, 'Vanessa Andrade Souza', '33412328120', 59),
(59, 'Luiz Almeida Nascimento', '97403447134', 60),
(60, 'Carla Nunes Rocha', '93618324210', 61),
(61, 'Felipe Nunes Gomes', '99471746488', 62),
(62, 'Maria Almeida Oliveira', '90659401399', 63);

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `cep` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`id`, `rua`, `numero`, `cidade`, `estado`, `cep`) VALUES
(11, 'sdsdsda', '2131', '123', '123123', '13064-560'),
(12, 'sdsdsda', '2131', '123', 'VR', '13064-560'),
(13, 'sdsdsda', '2131', '123', '123123', '13064-560'),
(14, 'Rua Tiradentes', '410', 'Jundiaí', 'SP', '13200-350'),
(15, 'Avenida Central', '2287', 'Americana', 'SP', '13470-792'),
(16, 'Rua Castro Alves', '1425', 'Rio de Janeiro', 'RJ', '20000-532'),
(17, 'Avenida Brasil', '489', 'Sumaré', 'SP', '13170-323'),
(18, 'Avenida Central', '8280', 'Salvador', 'BA', '40000-127'),
(19, 'Rua Castro Alves', '3258', 'Belo Horizonte', 'MG', '30000-529'),
(20, 'Avenida Central', '7360', 'Rio de Janeiro', 'RJ', '20000-384'),
(21, 'Rua das Flores', '2616', 'Guarulhos', 'SP', '07000-448'),
(22, 'Rua Sete de Setembro', '2548', 'Valinhos', 'SP', '13270-881'),
(23, 'Avenida Independência', '1675', 'Sumaré', 'SP', '13170-489'),
(24, 'Rua Tiradentes', '5882', 'Santos', 'SP', '11000-718'),
(25, 'Rua Sete de Setembro', '712', 'Curitiba', 'PR', '80000-649'),
(26, 'Rua Tiradentes', '6202', 'Sumaré', 'SP', '13170-665'),
(27, 'Rua Marechal Deodoro', '5926', 'Rio de Janeiro', 'RJ', '20000-296'),
(28, 'Rua São Paulo', '751', 'Vinhedo', 'SP', '13280-891'),
(29, 'Rua Marechal Deodoro', '1308', 'Vinhedo', 'SP', '13280-987'),
(30, 'Rua Tiradentes', '6228', 'Jundiaí', 'SP', '13200-564'),
(31, 'Rua Dom Pedro', '2665', 'Santos', 'SP', '11000-463'),
(32, 'Rua das Acácias', '4375', 'Sumaré', 'SP', '13170-723'),
(33, 'Rua XV de Novembro', '8752', 'Vinhedo', 'SP', '13280-267'),
(34, 'Rua Barão de Mauá', '6217', 'Jundiaí', 'SP', '13200-755'),
(35, 'Rua Castro Alves', '3599', 'Ribeirão Preto', 'SP', '14000-963'),
(36, 'Avenida Brasil', '3753', 'São Paulo', 'SP', '01000-924'),
(37, 'Avenida Independência', '6573', 'Jundiaí', 'SP', '13200-167'),
(38, 'Rua das Acácias', '9293', 'Ribeirão Preto', 'SP', '14000-317'),
(39, 'Rua João Pessoa', '6483', 'Curitiba', 'PR', '80000-246'),
(40, 'Rua Sete de Setembro', '2288', 'Vinhedo', 'SP', '13280-862'),
(41, 'Rua Castro Alves', '8831', 'Jundiaí', 'SP', '13200-864'),
(42, 'Rua Visconde de Mauá', '7020', 'Rio de Janeiro', 'RJ', '20000-508'),
(43, 'Rua Dom Pedro', '3594', 'Hortolândia', 'SP', '13180-621'),
(44, 'Rua João Pessoa', '1490', 'São Paulo', 'SP', '01000-981'),
(45, 'Rua Tiradentes', '2505', 'Indaiatuba', 'SP', '13330-911'),
(46, 'Avenida Getúlio Vargas', '9772', 'Sumaré', 'SP', '13170-494'),
(47, 'Rua Coronel Teixeira', '9764', 'Curitiba', 'PR', '80000-641'),
(48, 'Rua Sete de Setembro', '9065', 'Campinas', 'SP', '13000-796'),
(49, 'Rua Tiradentes', '8798', 'Jundiaí', 'SP', '13200-887'),
(50, 'Avenida Independência', '1828', 'Sorocaba', 'SP', '18000-545'),
(51, 'Rua XV de Novembro', '7434', 'Campinas', 'SP', '13000-839'),
(52, 'Rua Sete de Setembro', '8202', 'Indaiatuba', 'SP', '13330-619'),
(53, 'Rua Tiradentes', '4890', 'Porto Alegre', 'RS', '90000-723'),
(54, 'Rua das Acácias', '2505', 'Santos', 'SP', '11000-880'),
(55, 'Rua XV de Novembro', '8838', 'Porto Alegre', 'RS', '90000-100'),
(56, 'Alameda dos Anjos', '5311', 'Londrina', 'PR', '86000-119'),
(57, 'Rua Tiradentes', '5948', 'Sorocaba', 'SP', '18000-345'),
(58, 'Avenida Brasil', '3947', 'Rio de Janeiro', 'RJ', '20000-180'),
(59, 'Rua São Paulo', '7963', 'Sumaré', 'SP', '13170-878'),
(60, 'Rua Castro Alves', '2061', 'Hortolândia', 'SP', '13180-775'),
(61, 'Rua João Pessoa', '9008', 'Indaiatuba', 'SP', '13330-371'),
(62, 'Avenida Santos Dumont', '9939', 'Guarulhos', 'SP', '07000-316'),
(63, 'Rua Castro Alves', '3296', 'Sorocaba', 'SP', '18000-508'),
(64, 'Rua Dom Pedro', '7178', 'Porto Alegre', 'RS', '90000-562'),
(65, 'Rua Tiradentes', '4062', 'Vinhedo', 'SP', '13280-165'),
(66, 'Avenida Independência', '345', 'Rio de Janeiro', 'RJ', '20000-667'),
(67, 'Avenida Central', '9642', 'Vinhedo', 'SP', '13280-107'),
(68, 'Rua São Paulo', '965', 'Vinhedo', 'SP', '13280-169'),
(69, 'Avenida Brasil', '5414', 'Sumaré', 'SP', '13170-626'),
(70, 'Avenida Central', '4563', 'Londrina', 'PR', '86000-319'),
(71, 'Rua Castro Alves', '2168', 'Rio de Janeiro', 'RJ', '20000-690'),
(72, 'Rua João Pessoa', '3982', 'Londrina', 'PR', '86000-926'),
(73, 'Avenida Getúlio Vargas', '3120', 'Americana', 'SP', '13470-199'),
(74, 'Avenida Getúlio Vargas', '5805', 'Guarulhos', 'SP', '07000-520'),
(75, 'Rua Barão de Mauá', '888', 'Americana', 'SP', '13470-162'),
(76, 'Rua Coronel Teixeira', '5560', 'Americana', 'SP', '13470-354'),
(77, 'Rua das Acácias', '3117', 'Belo Horizonte', 'MG', '30000-559'),
(78, 'Avenida Paulista', '6913', 'Indaiatuba', 'SP', '13330-385'),
(79, 'Rua Barão de Mauá', '4093', 'Sumaré', 'SP', '13170-553'),
(80, 'Rua Castro Alves', '1605', 'São Paulo', 'SP', '01000-767'),
(81, 'Rua Castro Alves', '242', 'Sumaré', 'SP', '13170-871'),
(82, 'Avenida Central', '2725', 'Guarulhos', 'SP', '07000-597'),
(83, 'Rua João Pessoa', '3503', 'Osasco', 'SP', '06000-160');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `id_produto`, `quantidade`) VALUES
(4, 7, 1),
(5, 8, 22000),
(8, 9, 10000),
(10, 10, 4795),
(11, 11, 4492),
(12, 12, 2663),
(13, 13, 3120),
(14, 14, 4893),
(15, 15, 4346),
(16, 16, 125870),
(17, 17, 3716),
(18, 18, 4141),
(19, 19, 4959),
(20, 20, 3524),
(21, 21, 812),
(22, 22, 937),
(23, 23, 4516),
(24, 24, 1761),
(25, 25, 3523),
(26, 26, 3699),
(27, 27, 1871),
(28, 28, 3389),
(29, 29, 2776),
(30, 30, 3715),
(31, 31, 3266),
(32, 32, 3407),
(33, 33, 778),
(34, 34, 2560),
(35, 35, 3496),
(36, 36, 2560),
(37, 37, 2088),
(38, 38, 3054),
(39, 39, 1250),
(40, 40, 3885),
(41, 41, 549),
(42, 42, 747),
(43, 43, 632),
(44, 44, 763),
(45, 45, 3537),
(46, 46, 791),
(47, 47, 3052),
(48, 48, 1065),
(49, 49, 4557),
(50, 50, 491),
(51, 51, 4804),
(52, 52, 4600),
(53, 53, 4601),
(54, 54, 2700),
(55, 55, 1001),
(56, 56, 3365),
(57, 57, 2896),
(58, 58, 3464),
(59, 59, 421);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `endereco_forncedor` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id`, `nome`, `cnpj`, `endereco_forncedor`) VALUES
(1, 'Dymas Kawam Batista', '123414', 12),
(2, 'InfoDistrib S.A.', '38597703482477', 64),
(3, 'ComponentesBR Ltda', '09324808613171', 65),
(4, 'ElectroForn S.A.', '74846773782639', 66),
(5, 'EpsilonDistrib Ltda', '21465840449972', 67),
(6, 'OmegaForn S.A.', '87558867533963', 68),
(7, 'DeltaSupply Ltda', '05766270289517', 69),
(8, 'ComponentesBR Ltda', '87026217459615', 70),
(9, 'ZetaComp S.A.', '65780913431611', 71),
(10, 'OmegaForn S.A.', '24005045562386', 72),
(11, 'EtaSupply ME', '22219693792374', 73),
(12, 'OmegaForn S.A.', '40748217594647', 74),
(13, 'DataForn ME', '36713695944064', 75),
(14, 'TechSupply Ltda', '90974395339421', 76),
(15, 'InfoDistrib S.A.', '47095214562328', 77),
(16, 'BetaDistrib S.A.', '88424745171236', 78),
(17, 'ZetaComp S.A.', '51604817549651', 79),
(18, 'FastParts Ltda', '70985931746120', 80),
(19, 'InfoDistrib S.A.', '47113826758692', 81),
(20, 'DeltaSupply Ltda', '17964053773515', 82),
(21, 'ZetaComp S.A.', '50643171390053', 83);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor_produto`
--

CREATE TABLE `fornecedor_produto` (
  `id` int(11) NOT NULL,
  `id_fornecedor` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `preco_compra` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor_produto`
--

INSERT INTO `fornecedor_produto` (`id`, `id_fornecedor`, `id_produto`, `preco_compra`) VALUES
(2, 1, 8, 100.00),
(3, 1, 7, 312.00),
(4, 1, 9, 123.00),
(5, 1, 9, 100.00),
(6, 21, 10, 2586.27),
(7, 11, 10, 2977.83),
(8, 6, 11, 2482.38),
(9, 5, 12, 548.55),
(10, 13, 13, 2496.34),
(11, 5, 13, 2835.28),
(12, 10, 13, 2890.43),
(13, 21, 14, 1707.19),
(14, 19, 14, 1754.79),
(15, 2, 14, 1789.52),
(16, 7, 15, 1459.29),
(17, 12, 16, 1637.01),
(18, 13, 16, 2867.66),
(19, 14, 17, 783.23),
(20, 4, 17, 879.20),
(21, 6, 17, 513.31),
(22, 8, 18, 2167.22),
(23, 14, 18, 1765.08),
(24, 15, 18, 1974.45),
(25, 20, 19, 2800.84),
(26, 4, 19, 1794.84),
(27, 3, 20, 1660.79),
(28, 4, 20, 1640.79),
(29, 10, 20, 1832.49),
(30, 10, 21, 935.21),
(31, 8, 21, 619.27),
(32, 5, 22, 3136.22),
(33, 11, 22, 2989.69),
(34, 11, 23, 2658.29),
(35, 3, 23, 3010.96),
(36, 9, 23, 1988.45),
(37, 8, 24, 1811.99),
(38, 6, 24, 1360.93),
(39, 2, 25, 2295.48),
(40, 7, 26, 307.17),
(41, 6, 26, 372.41),
(42, 19, 27, 2337.08),
(43, 13, 27, 2860.62),
(44, 4, 27, 2396.49),
(45, 4, 28, 412.01),
(46, 12, 28, 411.45),
(47, 15, 29, 980.78),
(48, 11, 29, 1336.35),
(49, 5, 29, 833.05),
(50, 16, 30, 2182.87),
(51, 13, 30, 1725.66),
(52, 4, 30, 2181.44),
(53, 18, 31, 1592.13),
(54, 4, 31, 1503.66),
(55, 9, 32, 72.87),
(56, 12, 32, 46.23),
(57, 5, 33, 631.05),
(58, 18, 33, 905.95),
(59, 20, 33, 705.26),
(60, 6, 34, 297.78),
(61, 9, 34, 309.52),
(62, 5, 34, 403.09),
(63, 4, 35, 1193.42),
(64, 7, 35, 1267.53),
(65, 17, 35, 1279.21),
(66, 20, 36, 269.32),
(67, 12, 36, 252.11),
(68, 21, 36, 329.95),
(69, 10, 37, 145.11),
(70, 20, 37, 199.71),
(71, 16, 38, 932.85),
(72, 21, 38, 1194.52),
(73, 4, 39, 1125.44),
(74, 21, 39, 1056.33),
(75, 20, 40, 2187.57),
(76, 19, 40, 1962.97),
(77, 16, 41, 1634.81),
(78, 8, 42, 650.87),
(79, 12, 42, 831.51),
(80, 17, 42, 630.74),
(81, 4, 43, 1932.90),
(82, 18, 43, 1277.08),
(83, 16, 44, 2124.49),
(84, 21, 44, 1660.20),
(85, 18, 44, 1933.29),
(86, 14, 45, 3050.92),
(87, 15, 45, 2898.85),
(88, 12, 46, 1398.10),
(89, 19, 47, 974.63),
(90, 10, 48, 1667.47),
(91, 6, 48, 1949.16),
(92, 13, 49, 93.95),
(93, 14, 50, 2538.15),
(94, 6, 50, 2655.40),
(95, 4, 50, 3380.59),
(96, 18, 51, 1835.10),
(97, 15, 52, 873.10),
(98, 18, 52, 1091.65),
(99, 13, 52, 1574.64),
(100, 17, 53, 271.50),
(101, 8, 53, 253.86),
(102, 11, 54, 2483.48),
(103, 18, 55, 1496.61),
(104, 18, 56, 3179.80),
(105, 3, 56, 2772.94),
(106, 12, 56, 2740.21),
(107, 7, 57, 755.54),
(108, 21, 58, 318.58),
(109, 7, 58, 404.66),
(110, 16, 58, 500.05),
(111, 21, 59, 435.86),
(112, 11, 59, 443.08),
(113, 17, 34, 12345.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_venda`
--

CREATE TABLE `item_venda` (
  `id` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item_venda`
--

INSERT INTO `item_venda` (`id`, `id_venda`, `id_produto`, `quantidade`) VALUES
(1, 1, 8, 10),
(2, 2, 7, 999),
(3, 3, 43, 7),
(4, 3, 53, 6),
(5, 3, 27, 16),
(6, 4, 38, 13),
(7, 4, 26, 17),
(8, 4, 8, 15),
(9, 4, 12, 8),
(10, 5, 44, 2),
(11, 5, 29, 2),
(12, 6, 38, 16),
(13, 6, 45, 10),
(14, 6, 48, 18),
(15, 7, 13, 14),
(16, 8, 23, 12),
(17, 8, 53, 13),
(18, 9, 9, 19),
(19, 9, 32, 18),
(20, 9, 10, 7),
(21, 10, 42, 13),
(22, 10, 25, 17),
(23, 10, 11, 15),
(24, 11, 59, 20),
(25, 11, 46, 4),
(26, 11, 50, 5),
(27, 12, 32, 12),
(28, 13, 42, 5),
(29, 13, 30, 7),
(30, 13, 55, 20),
(31, 14, 39, 11),
(32, 14, 9, 16),
(33, 14, 15, 17),
(34, 14, 52, 15),
(35, 15, 45, 5),
(36, 15, 39, 11),
(37, 16, 17, 10),
(38, 16, 32, 19),
(39, 16, 46, 11),
(40, 17, 52, 1),
(41, 17, 43, 12),
(42, 17, 26, 11),
(43, 17, 37, 4),
(44, 18, 44, 1),
(45, 18, 26, 20),
(46, 18, 57, 16),
(47, 18, 53, 9),
(48, 19, 53, 19),
(49, 19, 10, 16),
(50, 20, 40, 20),
(51, 20, 47, 13),
(52, 21, 59, 8),
(53, 21, 50, 2),
(54, 22, 19, 1),
(55, 23, 27, 7),
(56, 23, 33, 14),
(57, 23, 16, 17),
(58, 23, 51, 20),
(59, 24, 54, 5),
(60, 24, 53, 17),
(61, 24, 10, 7),
(62, 24, 52, 18),
(63, 25, 49, 13),
(64, 25, 37, 11),
(65, 25, 40, 6),
(66, 26, 41, 9),
(67, 26, 28, 20),
(68, 26, 29, 16),
(69, 26, 50, 7),
(70, 27, 24, 10),
(71, 27, 42, 8),
(72, 28, 56, 7),
(73, 28, 25, 16),
(74, 28, 52, 11),
(75, 29, 29, 9),
(76, 29, 42, 10),
(77, 29, 57, 4),
(78, 29, 53, 19),
(79, 30, 32, 5),
(80, 30, 59, 10),
(81, 30, 29, 2),
(82, 30, 56, 10),
(83, 31, 29, 15),
(84, 32, 54, 7),
(85, 32, 37, 18),
(86, 32, 20, 9),
(87, 33, 15, 19),
(88, 33, 13, 8),
(89, 33, 46, 8),
(90, 34, 49, 17),
(91, 35, 47, 2),
(92, 35, 21, 4),
(93, 36, 28, 5),
(94, 36, 52, 1),
(95, 36, 37, 18),
(96, 36, 13, 6),
(97, 37, 48, 10),
(98, 37, 37, 11),
(99, 37, 19, 10),
(100, 37, 55, 2),
(101, 38, 48, 19),
(102, 39, 41, 2),
(103, 39, 54, 6),
(104, 40, 18, 16),
(105, 40, 9, 6),
(106, 40, 32, 10),
(107, 40, 57, 2),
(108, 41, 26, 19),
(109, 42, 28, 10),
(110, 43, 48, 5),
(111, 43, 41, 17),
(112, 43, 40, 15),
(113, 43, 38, 9),
(114, 44, 58, 11),
(115, 44, 14, 6),
(116, 45, 48, 1),
(117, 45, 23, 11),
(118, 45, 52, 10),
(119, 45, 18, 19),
(120, 46, 18, 13),
(121, 46, 46, 14),
(122, 47, 12, 4),
(123, 47, 32, 6),
(124, 47, 49, 5),
(125, 48, 27, 13),
(126, 48, 22, 8),
(127, 48, 7, 15),
(128, 48, 23, 9),
(129, 49, 26, 19),
(130, 49, 44, 1),
(131, 49, 53, 9),
(132, 50, 51, 4),
(133, 50, 22, 15),
(134, 50, 10, 10),
(135, 51, 32, 17),
(136, 51, 50, 10),
(137, 52, 47, 10),
(138, 53, 46, 16),
(139, 53, 21, 5),
(140, 53, 15, 15),
(141, 54, 33, 16),
(142, 54, 51, 18),
(143, 54, 42, 7),
(144, 55, 50, 20),
(145, 55, 55, 3),
(146, 56, 40, 19),
(147, 56, 52, 4),
(148, 56, 30, 2),
(149, 56, 11, 18),
(150, 57, 43, 5),
(151, 57, 41, 6),
(152, 58, 40, 7),
(153, 58, 35, 19),
(154, 58, 14, 16),
(155, 59, 39, 15),
(156, 60, 36, 5),
(157, 61, 36, 15),
(158, 61, 43, 10),
(159, 61, 10, 1),
(160, 61, 42, 13),
(161, 62, 59, 7),
(162, 62, 7, 19),
(163, 62, 54, 3),
(164, 63, 34, 12),
(165, 64, 41, 2),
(166, 65, 37, 2),
(167, 66, 33, 5),
(168, 66, 18, 14),
(169, 66, 56, 12),
(170, 67, 35, 18),
(171, 67, 31, 5),
(172, 67, 12, 12),
(173, 67, 50, 4),
(174, 68, 41, 17),
(175, 68, 32, 5),
(176, 69, 7, 1),
(177, 69, 55, 10),
(178, 70, 50, 18),
(179, 70, 53, 13),
(180, 70, 41, 8),
(181, 70, 34, 8),
(182, 71, 29, 4),
(183, 71, 16, 2),
(184, 71, 24, 14),
(185, 71, 19, 20),
(186, 72, 22, 7),
(187, 73, 13, 20),
(188, 74, 35, 20),
(189, 75, 22, 2),
(190, 76, 35, 2),
(191, 76, 21, 5),
(192, 76, 41, 17),
(193, 76, 20, 10),
(194, 77, 59, 19),
(195, 77, 53, 11),
(196, 78, 22, 17),
(197, 78, 26, 8),
(198, 78, 16, 14),
(199, 79, 24, 19),
(200, 79, 10, 6),
(201, 79, 42, 14),
(202, 80, 10, 13),
(203, 80, 29, 17),
(204, 80, 48, 11),
(205, 80, 49, 14),
(206, 81, 16, 18),
(207, 81, 26, 16),
(208, 81, 31, 8),
(209, 81, 18, 8),
(210, 82, 52, 15),
(211, 82, 16, 2),
(212, 82, 58, 18),
(213, 83, 33, 13),
(214, 83, 42, 8),
(215, 83, 40, 9),
(216, 83, 15, 7),
(217, 84, 48, 12),
(218, 84, 12, 3),
(219, 84, 35, 18),
(220, 85, 10, 13),
(221, 85, 24, 20),
(222, 86, 11, 7),
(223, 87, 37, 11),
(224, 87, 20, 10),
(225, 88, 20, 7),
(226, 89, 54, 16),
(227, 90, 51, 7),
(228, 90, 45, 13),
(229, 91, 42, 10),
(230, 91, 27, 13),
(231, 92, 41, 9),
(232, 92, 48, 12),
(233, 92, 29, 17),
(234, 92, 26, 16),
(235, 93, 13, 11),
(236, 93, 58, 7),
(237, 93, 53, 12),
(238, 93, 37, 11),
(239, 94, 9, 5),
(240, 94, 43, 1),
(241, 94, 21, 9),
(242, 94, 54, 18),
(243, 95, 25, 8),
(244, 95, 16, 13),
(245, 95, 19, 19),
(246, 95, 28, 8),
(247, 96, 42, 9),
(248, 96, 48, 16),
(249, 96, 50, 16),
(250, 96, 28, 15),
(251, 97, 53, 12),
(252, 97, 57, 6),
(253, 98, 53, 16),
(254, 98, 41, 6),
(255, 99, 40, 2),
(256, 100, 59, 2),
(257, 101, 33, 5),
(258, 102, 11, 5),
(259, 102, 52, 1),
(260, 103, 38, 12),
(261, 104, 43, 67);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id`, `nome`, `descricao`, `preco`) VALUES
(7, 'Zeremongol', '', 100.00),
(8, 'Demoirgo frito', '', 100.00),
(9, 'guilerme', '123', 123.00),
(10, 'Notebook Pro', 'Produto de alta qualidade - Notebook Pro', 4944.34),
(11, 'Mouse Sem Fio', 'Produto de alta qualidade - Mouse Sem Fio', 3941.18),
(12, 'Teclado Mecânico', 'Produto de alta qualidade - Teclado Mecânico', 1049.77),
(13, 'Monitor 24\"', 'Produto de alta qualidade - Monitor 24\"', 4154.09),
(14, 'Headset Gamer', 'Produto de alta qualidade - Headset Gamer', 2784.31),
(15, 'Webcam HD', 'Produto de alta qualidade - Webcam HD', 2944.22),
(16, 'Cabo HDMI', 'Produto de alta qualidade - Cabo HDMI', 4070.77),
(17, 'Hub USB', 'Produto de alta qualidade - Hub USB', 1187.90),
(18, 'SSD 480GB', 'Produto de alta qualidade - SSD 480GB', 3878.51),
(19, 'Memória RAM 8GB', 'Produto de alta qualidade - Memória RAM 8GB', 3949.46),
(20, 'Impressora Laser', 'Produto de alta qualidade - Impressora Laser', 2991.93),
(21, 'Scanner A4', 'Produto de alta qualidade - Scanner A4', 1407.96),
(22, 'Roteador Wi-Fi', 'Produto de alta qualidade - Roteador Wi-Fi', 4920.52),
(23, 'Nobreak 600VA', 'Produto de alta qualidade - Nobreak 600VA', 4937.91),
(24, 'Estabilizador', 'Produto de alta qualidade - Estabilizador', 2714.77),
(25, 'Cadeira Gamer', 'Produto de alta qualidade - Cadeira Gamer', 3998.51),
(26, 'Mesa de Escritório', 'Produto de alta qualidade - Mesa de Escritório', 576.23),
(27, 'Suporte Monitor', 'Produto de alta qualidade - Suporte Monitor', 4336.15),
(28, 'Mousepad Grande', 'Produto de alta qualidade - Mousepad Grande', 684.96),
(29, 'Caixa de Som Bluetooth', 'Produto de alta qualidade - Caixa de Som Bluetooth', 1810.36),
(30, 'Smartphone Android', 'Produto de alta qualidade - Smartphone Android', 3948.40),
(31, 'Carregador Rápido', 'Produto de alta qualidade - Carregador Rápido', 2956.50),
(32, 'Película Protetora', 'Produto de alta qualidade - Película Protetora', 108.33),
(33, 'Capa Protetora', 'Produto de alta qualidade - Capa Protetora', 1348.78),
(34, 'Fone de Ouvido', 'Produto de alta qualidade - Fone de Ouvido', 659.89),
(35, 'Pen Drive 64GB', 'Produto de alta qualidade - Pen Drive 64GB', 2122.07),
(36, 'Cartão SD 128GB', 'Produto de alta qualidade - Cartão SD 128GB', 594.74),
(37, 'Leitor de Cartão', 'Produto de alta qualidade - Leitor de Cartão', 345.78),
(38, 'Adaptador USB-C', 'Produto de alta qualidade - Adaptador USB-C', 2257.83),
(39, 'Câmera IP', 'Produto de alta qualidade - Câmera IP', 1829.23),
(40, 'Smart TV 50\"', 'Produto de alta qualidade - Smart TV 50\"', 2980.42),
(41, 'Controle Remoto', 'Produto de alta qualidade - Controle Remoto', 2276.37),
(42, 'Receptor HDTV', 'Produto de alta qualidade - Receptor HDTV', 1131.00),
(43, 'Antena Digital', 'Produto de alta qualidade - Antena Digital', 3086.98),
(44, 'Cabo de Rede', 'Produto de alta qualidade - Cabo de Rede', 3644.30),
(45, 'Switch 8 Portas', 'Produto de alta qualidade - Switch 8 Portas', 4546.40),
(46, 'Rack para Servidor', 'Produto de alta qualidade - Rack para Servidor', 3304.65),
(47, 'Patch Panel', 'Produto de alta qualidade - Patch Panel', 1529.00),
(48, 'Organizador de Cabos', 'Produto de alta qualidade - Organizador de Cabos', 3227.30),
(49, 'Filtro de Linha', 'Produto de alta qualidade - Filtro de Linha', 184.72),
(50, 'Bateria 10000mAh', 'Produto de alta qualidade - Bateria 10000mAh', 4992.88),
(51, 'Suporte Celular', 'Produto de alta qualidade - Suporte Celular', 4240.97),
(52, 'Relógio Smartwatch', 'Produto de alta qualidade - Relógio Smartwatch', 2148.48),
(53, 'Pulseira Fitness', 'Produto de alta qualidade - Pulseira Fitness', 566.47),
(54, 'Teclado Bluetooth', 'Produto de alta qualidade - Teclado Bluetooth', 3570.53),
(55, 'Mouse Vertical', 'Produto de alta qualidade - Mouse Vertical', 2234.40),
(56, 'Apoio de Pulso', 'Produto de alta qualidade - Apoio de Pulso', 4499.26),
(57, 'Luminária LED', 'Produto de alta qualidade - Luminária LED', 1630.80),
(58, 'Ventilador USB', 'Produto de alta qualidade - Ventilador USB', 767.12),
(59, 'Webcam 4K', 'Produto de alta qualidade - Webcam 4K', 657.07);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `papel` enum('admin','vendedor') NOT NULL DEFAULT 'vendedor',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `papel`, `criado_em`) VALUES
(1, 'Administrador', 'admin@loja.com', '$2a$12$drzbWuL8oHv94EUPWoDz7.T.4JQvZturpqJ7Y48PdLJIuWFA/Ibue', 'admin', '2026-04-06 11:31:44'),
(2, 'Vendedor Padrão', 'vendedor@loja.com', '$2a$12$drzbWuL8oHv94EUPWoDz7.T.4JQvZturpqJ7Y48PdLJIuWFA/Ibue', 'vendedor', '2026-04-06 11:31:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda`
--

INSERT INTO `venda` (`id`, `data`, `id_cliente`, `id_vendedor`) VALUES
(1, '2026-03-29 23:42:58', 11, 5),
(2, '2026-03-29 23:43:20', 11, 5),
(3, '2025-04-30 17:15:00', 30, 20),
(4, '2026-03-04 06:23:00', 54, 23),
(5, '2025-08-14 14:49:00', 29, 17),
(6, '2025-09-15 16:26:00', 21, 11),
(7, '2026-02-15 19:08:00', 27, 6),
(8, '2025-11-25 15:55:00', 34, 22),
(9, '2025-02-22 22:54:00', 44, 8),
(10, '2025-05-26 02:48:00', 21, 13),
(11, '2025-08-19 16:09:00', 38, 7),
(12, '2025-04-24 14:56:00', 33, 5),
(13, '2025-08-01 01:25:00', 43, 16),
(14, '2025-05-01 12:05:00', 34, 12),
(15, '2025-01-15 10:59:00', 17, 25),
(16, '2025-06-21 04:08:00', 13, 14),
(17, '2026-03-01 15:44:00', 19, 20),
(18, '2025-08-18 19:00:00', 16, 5),
(19, '2025-05-12 06:53:00', 20, 22),
(20, '2026-01-08 19:33:00', 38, 8),
(21, '2026-02-02 09:15:00', 30, 8),
(22, '2025-01-25 07:26:00', 51, 24),
(23, '2025-08-23 02:07:00', 42, 24),
(24, '2025-10-02 00:40:00', 43, 23),
(25, '2025-05-04 22:09:00', 29, 18),
(26, '2025-01-01 19:22:00', 26, 23),
(27, '2025-08-02 05:42:00', 53, 7),
(28, '2025-09-26 11:04:00', 44, 22),
(29, '2025-09-17 16:35:00', 12, 17),
(30, '2026-03-24 15:02:00', 51, 17),
(31, '2025-07-11 08:47:00', 12, 16),
(32, '2026-02-08 02:22:00', 26, 25),
(33, '2025-02-23 18:47:00', 59, 15),
(34, '2025-03-10 01:22:00', 45, 15),
(35, '2026-02-21 20:11:00', 60, 19),
(36, '2025-12-23 15:40:00', 22, 9),
(37, '2025-02-02 22:49:00', 40, 6),
(38, '2025-05-31 06:02:00', 61, 11),
(39, '2026-03-30 01:20:00', 30, 21),
(40, '2025-07-23 17:30:00', 27, 6),
(41, '2026-01-21 20:12:00', 29, 16),
(42, '2026-03-18 01:55:00', 52, 15),
(43, '2025-05-20 03:51:00', 34, 18),
(44, '2025-07-24 23:28:00', 35, 15),
(45, '2025-04-06 15:44:00', 42, 16),
(46, '2026-02-13 16:17:00', 62, 7),
(47, '2026-01-08 13:05:00', 38, 24),
(48, '2026-02-26 05:34:00', 29, 15),
(49, '2025-02-22 02:20:00', 53, 14),
(50, '2025-06-06 14:38:00', 56, 18),
(51, '2025-03-27 22:28:00', 33, 19),
(52, '2025-01-22 23:55:00', 33, 24),
(53, '2025-08-11 08:40:00', 61, 6),
(54, '2025-02-08 21:40:00', 36, 16),
(55, '2025-09-20 23:43:00', 21, 5),
(56, '2025-03-15 19:43:00', 61, 19),
(57, '2025-01-18 04:04:00', 26, 25),
(58, '2025-07-07 11:24:00', 47, 6),
(59, '2025-11-06 04:43:00', 39, 16),
(60, '2025-07-10 14:48:00', 15, 23),
(61, '2025-03-12 16:23:00', 36, 15),
(62, '2025-11-29 08:15:00', 18, 5),
(63, '2026-01-12 05:31:00', 44, 17),
(64, '2025-10-15 03:16:00', 60, 13),
(65, '2025-12-27 14:13:00', 50, 14),
(66, '2025-12-22 15:12:00', 18, 9),
(67, '2026-03-13 02:28:00', 22, 19),
(68, '2025-02-14 21:54:00', 31, 16),
(69, '2025-12-30 02:35:00', 45, 14),
(70, '2025-06-03 05:45:00', 56, 25),
(71, '2025-03-31 11:32:00', 25, 8),
(72, '2025-04-13 04:15:00', 61, 20),
(73, '2025-01-14 11:35:00', 47, 16),
(74, '2025-08-28 17:08:00', 50, 7),
(75, '2025-02-03 09:25:00', 56, 20),
(76, '2025-09-27 13:49:00', 37, 23),
(77, '2025-02-07 04:20:00', 52, 7),
(78, '2025-08-19 14:43:00', 44, 16),
(79, '2025-03-07 17:40:00', 48, 10),
(80, '2026-01-29 04:27:00', 43, 6),
(81, '2026-03-02 03:33:00', 20, 14),
(82, '2025-03-26 05:20:00', 56, 12),
(83, '2025-06-27 16:57:00', 29, 7),
(84, '2025-05-09 06:40:00', 46, 13),
(85, '2025-03-06 20:19:00', 50, 22),
(86, '2025-02-17 16:41:00', 21, 23),
(87, '2025-10-25 04:10:00', 53, 24),
(88, '2026-01-04 19:21:00', 47, 6),
(89, '2026-02-27 00:05:00', 13, 25),
(90, '2026-01-30 18:16:00', 52, 11),
(91, '2026-01-28 18:26:00', 50, 25),
(92, '2025-01-16 15:56:00', 51, 22),
(93, '2025-05-29 20:19:00', 41, 12),
(94, '2026-02-17 21:25:00', 30, 19),
(95, '2025-02-07 22:03:00', 21, 19),
(96, '2025-08-01 15:29:00', 24, 15),
(97, '2025-11-07 04:20:00', 56, 15),
(98, '2026-01-11 11:25:00', 19, 16),
(99, '2025-09-21 17:06:00', 31, 12),
(100, '2025-08-27 03:17:00', 39, 12),
(101, '2025-03-14 03:03:00', 29, 17),
(102, '2026-03-19 19:26:00', 26, 10),
(103, '2026-04-06 11:04:56', 46, 22),
(104, '2026-04-08 11:31:26', 26, 20);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedor`
--

CREATE TABLE `vendedor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendedor`
--

INSERT INTO `vendedor` (`id`, `nome`) VALUES
(5, 'jose finado da costa perera silva de moraes andrade nascimento carlos manuel da silva santos daniel '),
(6, 'Lucas Moreira Dias'),
(7, 'Viviane Lima Vieira'),
(8, 'Pedro Moreira Moreira'),
(9, 'Eduardo Lopes Sousa'),
(10, 'Mariana Almeida Freitas'),
(11, 'Leonardo Ferreira Vieira'),
(12, 'Antônio Rocha Marques'),
(13, 'Maria Oliveira Lopes'),
(14, 'Fábio Carvalho Costa'),
(15, 'Alexandre Dias Souza'),
(16, 'Sandra Ferreira Costa'),
(17, 'Luiz Nascimento Lopes'),
(18, 'Vinícius Dias Martins'),
(19, 'Viviane Moreira Fernandes'),
(20, 'Pedro Almeida Oliveira'),
(21, 'Alexandre Lima Costa'),
(22, 'André Moreira Martins'),
(23, 'Adriana Sousa Marques'),
(24, 'Carlos Dias Machado'),
(25, 'Camila Almeida Carvalho'),
(26, 'jose finado da costa perera silva de moraes andrade nascimento carlos manuel da silva santos daniel ');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_endereco_cliente` (`endereco_cliente`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_endereco_fornecedor` (`endereco_forncedor`);

--
-- Índices de tabela `fornecedor_produto`
--
ALTER TABLE `fornecedor_produto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_fornecedor` (`id_fornecedor`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `item_venda`
--
ALTER TABLE `item_venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venda` (`id_venda`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Índices de tabela `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `fornecedor_produto`
--
ALTER TABLE `fornecedor_produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de tabela `item_venda`
--
ALTER TABLE `item_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de tabela `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `FK_endereco_cliente` FOREIGN KEY (`endereco_cliente`) REFERENCES `endereco` (`id`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id`);

--
-- Restrições para tabelas `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD CONSTRAINT `FK_endereco_fornecedor` FOREIGN KEY (`endereco_forncedor`) REFERENCES `endereco` (`id`);

--
-- Restrições para tabelas `fornecedor_produto`
--
ALTER TABLE `fornecedor_produto`
  ADD CONSTRAINT `fornecedor_produto_ibfk_1` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor` (`id`),
  ADD CONSTRAINT `fornecedor_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id`);

--
-- Restrições para tabelas `item_venda`
--
ALTER TABLE `item_venda`
  ADD CONSTRAINT `item_venda_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`),
  ADD CONSTRAINT `item_venda_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
