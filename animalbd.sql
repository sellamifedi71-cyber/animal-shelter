-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 09:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `animalbd`
--

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `ida` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Age` int(11) NOT NULL,
  `Race` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`ida`, `type`, `Nom`, `Age`, `Race`) VALUES
(1, 'Chien', 'Rex', 3, 'Berger Allemand'),
(2, 'Chat', 'Mimi', 1, 'Bengal'),
(3, 'Cheval', 'Tornado', 6, 'Frison'),
(4, 'Chien', 'Jack', 2, 'Beagle'),
(5, 'Cheval', 'Spirit', 7, 'Mustang'),
(6, 'Chat', 'Simba', 4, 'Persan'),
(7, 'Cheval', 'Saphir', 4, 'Andalou'),
(8, 'Chien', 'Buddy', 3, 'Bulldog Anglais'),
(9, 'Chat', 'Whiskers', 5, 'Maine Coon'),
(10, 'Cheval', 'Zeus', 6, 'Camargue'),
(11, 'Chien', 'Snow', 6, 'Samoyède'),
(12, 'Chat', 'Oscar', 2, 'Abyssinien'),
(13, 'Cheval', 'Neige', 9, 'Lipizzan'),
(14, 'Chien', 'Zeus', 5, 'Rottweiler'),
(15, 'Cheval', 'Orion', 8, 'Quarter Horse'),
(16, 'Chat', 'Puffy', 4, 'Scottish Fold'),
(17, 'Cheval', 'Éclair', 5, 'Pur-sang arabe'),
(18, 'Chien', 'Max', 4, 'Golden Retriever'),
(19, 'Cheval', 'Tornado', 6, 'Frison'),
(20, 'Chat', 'Chacha', 3, 'Chartreux'),
(21, 'Chien', 'Rocky', 5, 'Husky Sibérien'),
(22, 'Cheval', 'Bella', 3, 'Lusitanien'),
(23, 'Chien', 'Rex', 3, 'Berger Allemand'),
(24, 'Cheval', 'Saphir', 4, 'Andalou'),
(25, 'Chien', 'Duke', 7, 'Boxer'),
(26, 'Chat', 'Leo', 1, 'Ragdoll'),
(27, 'Cheval', 'Snow', 6, 'Samoyède'),
(28, 'Chat', 'Milo', 1, 'Sphynx'),
(29, 'Chien', 'Bella', 1, 'Labrador'),
(30, 'Cheval', 'Bella', 3, 'Lusitanien');

-- --------------------------------------------------------

--
-- Table structure for table `demande`
--

CREATE TABLE `demande` (
  `id` int(11) NOT NULL,
  `idu` int(11) DEFAULT NULL,
  `ida` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `decision` char(1) DEFAULT NULL CHECK (`decision` in ('A','W','R'))
) ;

--
-- Dumping data for table `demande`
--

INSERT INTO `demande` (`id`, `idu`, `ida`, `date`, `decision`) VALUES
(1, 1, 1, '2024-04-01', 'A'),
(2, 2, 3, '2024-04-02', 'W'),
(3, 3, 5, '2024-04-03', 'R'),
(4, 4, 7, '2024-04-04', 'A'),
(5, 5, 8, '2024-04-05', 'W'),
(6, 6, 2, '2024-04-06', 'A'),
(7, 7, 4, '2024-04-07', 'R'),
(8, 8, 9, '2024-04-08', 'W'),
(9, 9, 10, '2024-04-09', 'A'),
(10, 10, 12, '2024-04-10', 'R'),
(11, 1, 15, '2024-04-11', 'W'),
(12, 2, 17, '2024-04-12', 'A'),
(13, 3, 19, '2024-04-13', 'R'),
(14, 4, 21, '2024-04-14', 'A'),
(15, 5, 22, '2024-04-15', 'W'),
(16, 6, 23, '2024-04-16', 'A'),
(17, 7, 25, '2024-04-17', 'W'),
(18, 8, 27, '2024-04-18', 'R'),
(19, 9, 29, '2024-04-19', 'A'),
(20, 10, 30, '2024-04-20', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idu` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `joinDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`idu`, `username`, `pass`, `joinDate`) VALUES
(1, 'sami23', 'pass123', '2023-06-01'),
(2, 'nour_b', 'secure456', '2023-07-15'),
(3, 'ikramM', 'ikram@789', '2024-01-10'),
(4, 'mehdi.k', 'mehdi2024', '2024-02-20'),
(5, 'salma_dz', 'salmaPass', '2023-11-10'),
(6, 'omarx', 'omarX123', '2024-03-05'),
(7, 'fatma7', 'fatmaSecure', '2024-04-01'),
(8, 'ahmed_l', 'ahmedPass', '2024-03-12'),
(9, 'mona.k', 'mona2024', '2024-02-28'),
(10, 'ali.j', 'aliPass123', '2024-01-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`ida`);

--
-- Indexes for table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idu` (`idu`),
  ADD KEY `ida` (`ida`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idu`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`idu`) REFERENCES `utilisateur` (`idu`),
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`ida`) REFERENCES `animal` (`ida`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
