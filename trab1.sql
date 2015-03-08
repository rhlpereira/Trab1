--
-- Database: `trab1`
--

-- CREATE DATABASE IF NOT EXISTS trab1

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `grupos`
--

INSERT INTO `grupos` (`cod`, `nome`, `tipo`) VALUES
(3, 'Matematica', 1),
(4, 'Portugues', 2),
(5, 'Historia', 3),
(6, 'Inglês', 4),
(7, 'Religião', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos`
--

CREATE TABLE IF NOT EXISTS `tipos` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`cod`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `tipos`
--

INSERT INTO `tipos` (`cod`, `nome`) VALUES
(1, 'Ensino Fundamental'),
(2, 'Ensino Médio'),
(3, 'Graduação'),
(4, 'Pós Graduação');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `grupo` int(11) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL,
  `nascimento` DATETIME DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `sexo` CHAR(1) DEFAULT NULL,
  `tel1` varchar(13) DEFAULT NULL,
  `tel2` varchar(13) DEFAULT NULL,
  `tel3` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`cod`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`login`, `senha`, `admin`) VALUES
('master', '202cb962ac59075b964b07152d234b70', 1);
