-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 06, 2026 alle 11:35
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accedi_condor`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `atleti_corsi`
--

CREATE TABLE `atleti_corsi` (
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `turno` varchar(20) NOT NULL,
  `numero_tesseramento` varchar(50) NOT NULL,
  `grado_cintura` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `atleti_corsi`
--

INSERT INTO `atleti_corsi` (`nome`, `cognome`, `turno`, `numero_tesseramento`, `grado_cintura`) VALUES
('Michele', 'Castaldo', '3', '107500', 'Nera 1ºDan'),
('Luigi', 'Piscopo', '3', '156144', 'Nera 3ºDan'),
('Paolo', 'Testa', '3', '164383', 'Nera 1ºDan'),
('Francesco Pio', 'Calvanese', '3', '172614', 'Nera 2ºDan'),
('Fabio', 'Laudando', '3', '174642', 'Nera 1ºDan'),
('Simone', 'Ferrara', '3', '174644', 'Nera 1ºDan'),
('Ciro', 'Addevico', '3', '176295', 'Rossa'),
('Luigi', 'Tuccillo', '3', '185253', 'Nera 1ºDan'),
('Ciro', 'Maiello', '2', '186734', 'Verde'),
('Vittorio Maria', 'Guerra', '3', '192586', 'Nera 1ºDan'),
('Alessandro', 'Allocca', '3', '194332', 'Nera'),
('Emanuele', 'Sainas', '2', '202464', 'Blu'),
('Carlo', 'Serbati', '2', '203025', 'Blu'),
('Antonio', 'Addevico', '2', '206609', 'Verde'),
('Raffaele', 'Ortoglio', '2', '207424', 'Verde'),
('Domenico', 'Laezza', '2', '217749', 'Verde'),
('Mario', 'De Riso', '2', '222049', 'Verde'),
('Gioia Nunzia', 'Perrotta', '3', '222624', 'Verde'),
('Alessandro', 'Sole', '2', '227423', 'Gialla'),
('Francesca Pia', 'Duro', '3', '227974', 'Verde'),
('Luigia', 'De Luca', '3', '235507', 'Verde'),
('Matteo', 'Colindo', '1', '238110', 'Bianca'),
('Diego', 'Caropreso', '1', '243660', 'Bianca'),
('Francesco', 'Testa', '2', '250757', 'Blu'),
('Pacifico', 'Laezza', '4', '911', 'Nera 7°Dan');

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `id` int(11) NOT NULL,
  `utente` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `data_nascita` date NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`id`, `utente`, `password`, `nome`, `cognome`, `data_nascita`, `telefono`, `email`) VALUES
(2, 'sdsd', '123', 'm', 'c', '1212-03-21', '233', 'srer@gmail.com'),
(5, 'amico2', 'ste', 'stepan', 'maksimovic', '2007-03-01', '3275805522', 'stepanmaksyimiv@gmail.com'),
(6, 'ccc', 'ccc', 'MICHELE', 'CASTALDO', '0206-11-16', '423489820', 'castaldo.michele@itsdallachiesa.edu.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `gara_iscrizioni`
--

CREATE TABLE `gara_iscrizioni` (
  `id` int(11) NOT NULL,
  `id_gara` int(11) NOT NULL,
  `nome_atleta` varchar(100) DEFAULT NULL,
  `allenatore` varchar(100) DEFAULT NULL,
  `data_iscrizione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gara_iscrizioni`
--

INSERT INTO `gara_iscrizioni` (`id`, `id_gara`, `nome_atleta`, `allenatore`, `data_iscrizione`) VALUES
(1, 101, 'Allocca Alessandro', 'ertre', '2026-05-06 09:34:14'),
(2, 101, 'Calvanese Francesco Pio', 'ertre', '2026-05-06 09:34:14');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `atleti_corsi`
--
ALTER TABLE `atleti_corsi`
  ADD PRIMARY KEY (`numero_tesseramento`);

--
-- Indici per le tabelle `credenziali`
--
ALTER TABLE `credenziali`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utente` (`utente`);

--
-- Indici per le tabelle `gara_iscrizioni`
--
ALTER TABLE `gara_iscrizioni`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `credenziali`
--
ALTER TABLE `credenziali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `gara_iscrizioni`
--
ALTER TABLE `gara_iscrizioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
