-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 27, 2026 alle 12:07
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
-- Struttura della tabella `atleti`
--

CREATE TABLE `atleti` (
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `turno` int(11) NOT NULL,
  `numero_tesseramento` varchar(50) NOT NULL,
  `grado_cintura` varchar(50) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `atleti`
--

INSERT INTO `atleti` (`nome`, `cognome`, `turno`, `numero_tesseramento`, `grado_cintura`, `data_nascita`, `telefono`, `email`) VALUES
('Michele', 'Castaldo', 3, '107500', 'Nera 1ºDan', '0206-11-16', '423489820', 'castaldo.michele@itsdallachiesa.edu.it'),
('Luigi', 'Piscopo', 3, '156144', 'Nera 3ºDan', NULL, NULL, NULL),
('Paolo', 'Testa', 3, '164383', 'Nera 1ºDan', NULL, NULL, NULL),
('Francesco Pio', 'Calvanese', 3, '172614', 'Nera 2ºDan', NULL, NULL, NULL),
('Fabio', 'Laudando', 3, '174642', 'Nera 1ºDan', NULL, NULL, NULL),
('Simone', 'Ferrara', 3, '174644', 'Nera 1ºDan', NULL, NULL, NULL),
('Ciro', 'Addevico', 3, '176295', 'Rossa', NULL, NULL, NULL),
('Luigi', 'Tuccillo', 3, '185253', 'Nera 1ºDan', NULL, NULL, NULL),
('Ciro', 'Maiello', 2, '186734', 'Verde', NULL, NULL, NULL),
('Vittorio Maria', 'Guerra', 3, '192586', 'Nera 1ºDan', NULL, NULL, NULL),
('Alessandro', 'Allocca', 3, '194332', 'Nera', NULL, NULL, NULL),
('Emanuele', 'Sainas', 2, '202464', 'Blu', NULL, NULL, NULL),
('Carlo', 'Serbati', 2, '203025', 'Blu', NULL, NULL, NULL),
('Antonio', 'Addevico', 2, '206609', 'Verde', NULL, NULL, NULL),
('Raffaele', 'Ortoglio', 2, '207424', 'Verde', NULL, NULL, NULL),
('Domenico', 'Laezza', 2, '217749', 'Verde', NULL, NULL, NULL),
('Mario', 'De Riso', 2, '222049', 'Verde', NULL, NULL, NULL),
('Gioia Nunzia', 'Perrotta', 3, '222624', 'Verde', NULL, NULL, NULL),
('Alessandro', 'Sole', 2, '227423', 'Gialla', NULL, NULL, NULL),
('Francesca Pia', 'Duro', 3, '227974', 'Verde', NULL, NULL, NULL),
('Luigia', 'De Luca', 3, '235507', 'Verde', NULL, NULL, NULL),
('Matteo', 'Colindo', 1, '238110', 'Bianca', NULL, NULL, NULL),
('Diego', 'Caropreso', 1, '243660', 'Bianca', NULL, NULL, NULL),
('Francesco', 'Testa', 2, '250757', 'Blu', NULL, NULL, NULL),
('Test', 'Test', 1, '71315806', 'Bianca', NULL, NULL, NULL),
('test1', 'test', 1, '72905261', 'Bianca', NULL, NULL, NULL),
('Pacifico', 'Laezza', 4, '911', 'Nera 7°Dan', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `id` int(11) NOT NULL,
  `utente` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `numero_tesseramento` varchar(50) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `data_nascita` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`id`, `utente`, `password`, `numero_tesseramento`, `nome`, `cognome`, `data_nascita`, `telefono`, `email`) VALUES
(2, 'sdsd', '123', NULL, 'm', 'c', '1212-03-21', '233', 'srer@gmail.com'),
(5, 'amico2', 'ste', NULL, 'stepan', 'maksimovic', '2007-03-01', '3275805522', 'stepanmaksyimiv@gmail.com'),
(6, 'ccc', 'ccc', '107500', 'MICHELE', 'CASTALDO', '0206-11-16', '423489820', 'castaldo.michele@itsdallachiesa.edu.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `gara_iscrizioni`
--

CREATE TABLE `gara_iscrizioni` (
  `id` int(11) NOT NULL,
  `id_gara` int(11) NOT NULL,
  `numero_tesseramento` varchar(50) DEFAULT NULL,
  `nome_atleta` varchar(100) DEFAULT NULL,
  `allenatore` varchar(100) DEFAULT NULL,
  `data_iscrizione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gara_iscrizioni`
--

INSERT INTO `gara_iscrizioni` (`id`, `id_gara`, `numero_tesseramento`, `nome_atleta`, `allenatore`, `data_iscrizione`) VALUES
(1, 101, '194332', 'Allocca Alessandro', 'ertre', '2026-05-06 09:34:14'),
(2, 101, '172614', 'Calvanese Francesco Pio', 'ertre', '2026-05-06 09:34:14'),
(3, 105, '172614', 'Calvanese Francesco Pio', 'Pacifico Laezza', '2026-05-21 09:06:41'),
(4, 105, '243660', 'Caropreso Diego', 'Pacifico Laezza', '2026-05-21 09:06:41'),
(5, 105, '107500', 'Castaldo Michele', 'Pacifico Laezza', '2026-05-21 09:06:41'),
(6, 105, '192586', 'Guerra Vittorio Maria', 'Pacifico Laezza', '2026-05-21 09:06:41'),
(7, 105, '217749', 'Laezza Domenico', 'Pacifico Laezza', '2026-05-21 09:06:41'),
(8, 105, '156144', 'Piscopo Luigi', 'Pacifico Laezza', '2026-05-21 09:06:41');

-- --------------------------------------------------------

--
-- Struttura della tabella `gare`
--

CREATE TABLE `gare` (
  `id_gara` int(11) NOT NULL,
  `titolo` varchar(200) NOT NULL,
  `data` varchar(50) NOT NULL,
  `luogo` varchar(150) NOT NULL,
  `specialita` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gare`
--

INSERT INTO `gare` (`id_gara`, `titolo`, `data`, `luogo`, `specialita`) VALUES
(101, 'Campionati Forme e Freestyle - Taekwondo e Parataekwondo #101', '11 - 12 Aprile 2026', 'Palazzetto dello Sport, Giugliano', 'FITA'),
(102, 'Tashkent 2026 World Taekwondo Junior Championships #102', '12 - 17 Aprile 2026', 'Olympic City, Tashkent, Uzbekistan', 'Internazionale'),
(103, 'European Seniors Championships 2026 #103', '12 - 14 Maggio 2026', 'BMW Park Venue', 'Internazionale'),
(104, 'Rome 2026 World Taekwondo Grand Prix Series I #104', '04 - 07 Giugno 2026', 'Foro Italico - Roma', 'Internazionale'),
(105, 'Virtual Taekwondo Roma Open 2026 #105', '04 Giugno 2026', 'Foro Italico - Roma', 'Internazionale'),
(106, 'Kim e Liù 2026 - Taekwondo to the Future! #106', '05 - 07 Giugno 2026', 'Foro Italico - Roma', 'Internazionale'),
(107, 'Campionati Italiani Taekwondo Senior Cinture Nere #107', '08 Giugno 2026', 'Foro Italico - Roma', 'FITA'),
(108, '3° San Marino Open #108', '27 - 28 Giugno 2026', 'Multieventi Sport Domus - Serravalle (RSM)', 'Internazionale');

-- --------------------------------------------------------

--
-- Struttura della tabella `turni`
--

CREATE TABLE `turni` (
  `id_turno` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descrizione` varchar(100) DEFAULT NULL,
  `orario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `turni`
--

INSERT INTO `turni` (`id_turno`, `nome`, `descrizione`, `orario`) VALUES
(1, '1° Turno', 'Kids / Cadetti', '17:00 - 18:30'),
(2, '2° Turno', 'Cadetti / Junior', '18:30 - 20:00'),
(3, '3° Turno', 'Senior', '20:00 - 21:30'),
(4, 'Allenatori/Tecnici', 'Maestro', '');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `atleti`
--
ALTER TABLE `atleti`
  ADD PRIMARY KEY (`numero_tesseramento`),
  ADD KEY `fk_atleti_turni` (`turno`);

--
-- Indici per le tabelle `credenziali`
--
ALTER TABLE `credenziali`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utente` (`utente`),
  ADD UNIQUE KEY `credenziali_tesseramento_unique` (`numero_tesseramento`);

--
-- Indici per le tabelle `gara_iscrizioni`
--
ALTER TABLE `gara_iscrizioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gara` (`id_gara`),
  ADD KEY `gara_iscrizioni_tesseramento_idx` (`numero_tesseramento`);

--
-- Indici per le tabelle `gare`
--
ALTER TABLE `gare`
  ADD PRIMARY KEY (`id_gara`);

--
-- Indici per le tabelle `turni`
--
ALTER TABLE `turni`
  ADD PRIMARY KEY (`id_turno`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `atleti`
--
ALTER TABLE `atleti`
  ADD CONSTRAINT `fk_atleti_turni` FOREIGN KEY (`turno`) REFERENCES `turni` (`id_turno`);

--
-- Limiti per la tabella `credenziali`
--
ALTER TABLE `credenziali`
  ADD CONSTRAINT `credenziali_atleti_fk` FOREIGN KEY (`numero_tesseramento`) REFERENCES `atleti` (`numero_tesseramento`) ON DELETE CASCADE;

--
-- Limiti per la tabella `gara_iscrizioni`
--
ALTER TABLE `gara_iscrizioni`
  ADD CONSTRAINT `gara_iscrizioni_atleti_fk` FOREIGN KEY (`numero_tesseramento`) REFERENCES `atleti` (`numero_tesseramento`),
  ADD CONSTRAINT `gara_iscrizioni_ibfk_1` FOREIGN KEY (`id_gara`) REFERENCES `gare` (`id_gara`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
