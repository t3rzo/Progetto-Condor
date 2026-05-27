-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 27, 2026 alle 11:35
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
  `numero_tesseramento` varchar(50) NOT NULL PRIMARY KEY,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `turno` varchar(20) NOT NULL,
  `grado_cintura` varchar(50) NOT NULL,
  `data_nascita` date,
  `telefono` varchar(20),
  `email` varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `atleti`
--

INSERT INTO `atleti` (`numero_tesseramento`, `nome`, `cognome`, `turno`, `grado_cintura`, `data_nascita`, `telefono`, `email`) VALUES
('107500', 'Michele', 'Castaldo', '3', 'Nera 1ºDan', '1986-11-16', '3423489820', 'castaldo.michele@itsdallachiesa.edu.it'),
('156144', 'Luigi', 'Piscopo', '3', 'Nera 3ºDan', NULL, NULL, NULL),
('164383', 'Paolo', 'Testa', '3', 'Nera 1ºDan', NULL, NULL, NULL),
('172614', 'Francesco Pio', 'Calvanese', '3', 'Nera 2ºDan', NULL, NULL, NULL),
('174642', 'Fabio', 'Laudando', '3', 'Nera 1ºDan', NULL, NULL, NULL),
('174644', 'Simone', 'Ferrara', '3', 'Nera 1ºDan', NULL, NULL, NULL),
('176295', 'Ciro', 'Addevico', '3', 'Rossa', NULL, NULL, NULL),
('185253', 'Luigi', 'Tuccillo', '3', 'Nera 1ºDan', NULL, NULL, NULL),
('186734', 'Ciro', 'Maiello', '2', 'Verde', NULL, NULL, NULL),
('192586', 'Vittorio Maria', 'Guerra', '3', 'Nera 1ºDan', NULL, NULL, NULL),
('194332', 'Alessandro', 'Allocca', '3', 'Nera', NULL, NULL, NULL),
('202464', 'Emanuele', 'Sainas', '2', 'Blu', NULL, NULL, NULL),
('203025', 'Carlo', 'Serbati', '2', 'Blu', NULL, NULL, NULL),
('206609', 'Antonio', 'Addevico', '2', 'Verde', NULL, NULL, NULL),
('207424', 'Raffaele', 'Ortoglio', '2', 'Verde', NULL, NULL, NULL),
('217749', 'Domenico', 'Laezza', '2', 'Verde', NULL, NULL, NULL),
('222049', 'Mario', 'De Riso', '2', 'Verde', NULL, NULL, NULL),
('222624', 'Gioia Nunzia', 'Perrotta', '3', 'Verde', NULL, NULL, NULL),
('227423', 'Alessandro', 'Sole', '2', 'Gialla', NULL, NULL, NULL),
('227974', 'Francesca Pia', 'Duro', '3', 'Verde', NULL, NULL, NULL),
('235507', 'Luigia', 'De Luca', '3', 'Verde', NULL, NULL, NULL),
('238110', 'Matteo', 'Colindo', '1', 'Bianca', NULL, NULL, NULL),
('243660', 'Diego', 'Caropreso', '1', 'Bianca', NULL, NULL, NULL),
('250757', 'Francesco', 'Testa', '2', 'Blu', NULL, NULL, NULL),
('911', 'Pacifico', 'Laezza', '4', 'Nera 7°Dan', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `utente` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `numero_tesseramento` varchar(50) NOT NULL,
  FOREIGN KEY (`numero_tesseramento`) REFERENCES `atleti` (`numero_tesseramento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`id`, `utente`, `password`, `numero_tesseramento`) VALUES
(6, 'ccc', 'ccc', '107500');

-- --------------------------------------------------------

--
-- Struttura della tabella `corsi`
--

CREATE TABLE `corsi` (
  `id_corso` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(100) NOT NULL,
  `turno` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizioni_corsi`
--

CREATE TABLE `iscrizioni_corsi` (
  `id_iscrizione` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `numero_tesseramento` varchar(50) NOT NULL,
  `id_corso` int(11) NOT NULL,
  `turno` varchar(20),
  `data_iscrizione` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`numero_tesseramento`) REFERENCES `atleti` (`numero_tesseramento`) ON DELETE CASCADE,
  FOREIGN KEY (`id_corso`) REFERENCES `corsi` (`id_corso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `gare`
--

CREATE TABLE `gare` (
  `id_gara` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
-- Struttura della tabella `gara_iscrizioni`
--

CREATE TABLE `gara_iscrizioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_gara` int(11) NOT NULL,
  `numero_tesseramento` varchar(50) NOT NULL,
  `allenatore` varchar(100),
  `data_iscrizione` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_gara`) REFERENCES `gare` (`id_gara`) ON DELETE CASCADE,
  FOREIGN KEY (`numero_tesseramento`) REFERENCES `atleti` (`numero_tesseramento`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gara_iscrizioni`
--

INSERT INTO `gara_iscrizioni` (`id`, `id_gara`, `numero_tesseramento`, `allenatore`, `data_iscrizione`) VALUES
(1, 101, '194332', 'ertre', '2026-05-06 09:34:14'),
(2, 101, '172614', 'ertre', '2026-05-06 09:34:14');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
