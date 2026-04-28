-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 07:46 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neworders`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `id` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klienci`
--

INSERT INTO `klienci` (`id`, `imie`, `nazwisko`, `email`, `telefon`, `adres`, `data_utworzenia`) VALUES
(1, 'Jan', 'Kowalski', 'jan.kowalski@example.com', '123456789', 'ul. Polska 1, Warszawa', '2026-02-22 10:33:40'),
(2, 'Anna', 'Nowak', 'anna.nowak@example.com', '987654321', 'ul. Krakowska 5, Kraków', '2026-02-22 10:33:40'),
(3, 'Piotr', 'Wiśniewski', 'piotr.wisniewski@example.com', '675666777', 'ul. Gdańska 10, Gdańsk', '2026-02-22 10:33:40'),
(4, 'Spacing', 'Kosmos', 'spacingkosmopan.mapy@gmail.com', '883911251', 'ul. Gdańska 10, Gdańsk', '2026-04-02 12:36:09'),
(5, 'Kuba', 'Kosmos', 'spacingkosmopan.mapy@gmail.com', '883911251', 'tu i tam', '2026-04-03 16:11:18'),
(6, '0', '0', '0@2', '', '', '2026-04-03 16:13:07');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `struktury`
--

CREATE TABLE `struktury` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `rodzic_id` int(11) DEFAULT NULL,
  `typ` enum('catalogue','text/plain') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `struktury`
--

INSERT INTO `struktury` (`id`, `nazwa`, `rodzic_id`, `typ`) VALUES
(1, 'main', NULL, 'catalogue'),
(2, 'outside', NULL, 'text/plain'),
(3, 'inside', NULL, 'text/plain'),
(4, 'afw', NULL, 'text/plain'),
(5, 'folder', NULL, 'catalogue'),
(8, 'plain', NULL, 'text/plain'),
(9, 'jakiś tam', 5, 'text/plain'),
(10, 'wmainplik', 1, 'text/plain'),
(11, 'urodziny', NULL, 'catalogue'),
(12, '50te', 11, 'text/plain');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tagi`
--

CREATE TABLE `tagi` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tagi`
--

INSERT INTO `tagi` (`id`, `nazwa`) VALUES
(1, 'Album'),
(2, 'Zaproszenia'),
(3, 'Rustykalne'),
(5, 'Zawieszki'),
(6, 'Urodzinowe');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `teksty`
--

CREATE TABLE `teksty` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL,
  `tresc` text DEFAULT NULL,
  `data_dodania` timestamp NOT NULL DEFAULT current_timestamp(),
  `struktura_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teksty`
--

INSERT INTO `teksty` (`id`, `tytul`, `tresc`, `data_dodania`, `struktura_id`) VALUES
(2, 'outside', '', '2026-04-18 19:44:24', 2),
(3, 'inside', '', '2026-04-18 19:44:37', 3),
(4, 'afw', 'eav', '2026-04-18 19:45:58', 4),
(7, 'plain', 'gewg', '2026-04-18 20:08:15', 8),
(8, 'jakiś tam', '', '2026-04-25 15:50:33', 9),
(9, 'wmainplik', '', '2026-04-25 15:57:23', 10),
(10, '50te', 'Najlepszego', '2026-04-26 10:57:59', 12);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy_zamowien`
--

CREATE TABLE `typy_zamowien` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typy_zamowien`
--

INSERT INTO `typy_zamowien` (`id`, `tytul`) VALUES
(0, 'Album'),
(2, 'Zaproszenie'),
(3, 'Księga Gości'),
(4, 'Nowy typ temporary');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `password_hash` text NOT NULL,
  `ostatnie_logowanie` datetime DEFAULT NULL,
  `utworzono` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('oczekujacy','aktywny','zablokowany') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `nazwa`, `password_hash`, `ostatnie_logowanie`, `utworzono`, `status`) VALUES
(1, 'Kuba', '$2y$10$Pkj/5m/8fl6ybyo7EWi6Ae8hDsA5G/pbwpHtP7WdnrbZ9sqbCR3ya', NULL, '2026-04-26 10:05:44', 'aktywny'),
(4, 'alicja', '$2y$10$oXlubO36wEYIKgjpzARWbOqwHG.0rfrjc6YyJSTxGncdAgH3dP3yK', NULL, '2026-04-26 11:58:49', 'aktywny'),
(5, 'kropka', '$2y$10$IGdRFTQIgyMoYqOQ/ZbzUutUVMjNO1ZBxv.YVIHGe7/jBxcB7nHVK', NULL, '2026-04-26 12:37:46', 'zablokowany');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id` int(11) NOT NULL,
  `klient_id` int(11) NOT NULL,
  `data_utworzenia` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('nowe','w realizacji','zrealizowane','anulowane') DEFAULT 'nowe',
  `kwota` decimal(10,2) DEFAULT 0.00,
  `opis` text DEFAULT NULL,
  `termin_realizacji` date NOT NULL,
  `tagi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tagi`)),
  `zalaczniki` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`zalaczniki`)),
  `zdjecia` text DEFAULT NULL,
  `typ_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id`, `klient_id`, `data_utworzenia`, `status`, `kwota`, `opis`, `termin_realizacji`, `tagi`, `zalaczniki`, `zdjecia`, `typ_id`) VALUES
(5, 5, '2026-04-17 10:12:45', 'w realizacji', 0.00, 'na 18stke', '2026-08-01', 'null', 'null', '[]', 2),
(6, 2, '2026-04-21 08:50:25', 'zrealizowane', 0.00, '', '2026-04-23', 'null', 'null', '[]', 3),
(7, 6, '2026-04-21 08:50:39', 'zrealizowane', 0.00, '', '2026-04-21', 'null', 'null', '[]', 0),
(8, 3, '2026-04-21 08:52:01', 'nowe', 0.00, '', '2026-04-19', 'null', 'null', '[]', 2),
(9, 2, '2026-04-21 08:52:14', 'nowe', 0.00, '', '2026-05-08', 'null', 'null', '[]', 2),
(10, 3, '2026-04-25 09:00:46', 'nowe', 0.00, '', '2026-04-25', 'null', 'null', '[]', 3),
(11, 2, '2026-04-25 09:17:02', 'nowe', 0.00, '', '2026-05-16', 'null', 'null', '[]', 0),
(12, 6, '2026-04-25 10:20:15', 'w realizacji', 0.00, 'to jest zaproszenie', '2026-05-08', NULL, NULL, NULL, 2),
(13, 4, '2026-04-25 10:20:48', 'anulowane', 0.00, 'dzdzb', '2026-04-25', NULL, NULL, NULL, 3),
(14, 5, '2026-04-25 10:21:07', 'nowe', 0.00, 'dfsb fd', '2026-04-25', NULL, NULL, NULL, 3),
(15, 5, '2026-04-25 10:22:09', 'nowe', 0.00, 'dfsb fd', '2026-04-25', NULL, NULL, NULL, 0),
(16, 5, '2026-04-25 10:22:35', 'w realizacji', 0.00, 'dsbdsb', '2026-04-25', NULL, NULL, NULL, 2),
(17, 5, '2026-04-25 10:23:08', 'nowe', 0.00, 'dzdzb', '2026-04-25', NULL, NULL, NULL, 3),
(18, 1, '2026-04-25 10:23:54', 'anulowane', 0.00, 'to jest zaproszenie', '2026-04-25', NULL, NULL, NULL, 2),
(19, 5, '2026-04-25 10:26:07', 'w realizacji', 0.00, 'test', '2026-07-17', 'null', 'null', '[]', 4),
(20, 6, '2026-04-26 11:42:23', 'nowe', 0.01, 'efs', '2026-04-26', 'null', 'null', '[]', 3),
(21, 6, '2026-04-26 11:42:37', 'nowe', 0.01, 'efs', '2026-04-26', 'null', 'null', '[]', 3),
(22, 1, '2026-04-26 11:50:57', 'nowe', 0.02, 'testowanie', '2026-04-26', 'null', 'null', '[]', 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE `zdjecia` (
  `id` int(11) NOT NULL,
  `nazwa_pliku` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `data_dodania` timestamp NOT NULL DEFAULT current_timestamp(),
  `sciezka` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `nazwa_pliku`, `opis`, `data_dodania`, `sciezka`) VALUES
(10, 'doors1.png', 'doors', '2026-03-28 10:59:30', '69c7b4922f38e.png'),
(11, 'spearer.png', 'spearer', '2026-03-28 10:59:53', '69c7b4a9f1cb2.png'),
(12, 'Zrzut ekranu 2024-11-01 223840.png', 'dżewo', '2026-03-28 11:01:18', '69c7b4fe62213.png'),
(42, 'WORTH BUYING.png', 'Bonnie', '2026-04-21 05:37:15', '69e70d0b04cd3.png'),
(43, 'ChatGPT Image Apr 17, 2026, 11_06_10 PM.png', 'gpt', '2026-04-21 05:37:48', '69e70d2c914fd.png'),
(44, 'grenades (2) (2).png', 'grenades', '2026-04-21 05:39:00', '69e70d74e578a.png'),
(45, 'bg.png', 'bck', '2026-04-21 05:41:07', '69e70df384efe.png'),
(46, 'teamwork baner.png', 'teamwork', '2026-04-21 05:42:23', '69e70e3fd4f42.png'),
(47, '17772013147262263390184421879929.jpg', 'selfie', '2026-04-26 11:02:05', '69edf0adb2dec.jpg');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `struktury`
--
ALTER TABLE `struktury`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rodzic_id` (`rodzic_id`);

--
-- Indeksy dla tabeli `tagi`
--
ALTER TABLE `tagi`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `teksty`
--
ALTER TABLE `teksty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `struktura_id` (`struktura_id`);

--
-- Indeksy dla tabeli `typy_zamowien`
--
ALTER TABLE `typy_zamowien`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`nazwa`),
  ADD KEY `idx_users_username` (`nazwa`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klient_id` (`klient_id`);

--
-- Indeksy dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `struktury`
--
ALTER TABLE `struktury`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tagi`
--
ALTER TABLE `tagi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teksty`
--
ALTER TABLE `teksty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `typy_zamowien`
--
ALTER TABLE `typy_zamowien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teksty`
--
ALTER TABLE `teksty`
  ADD CONSTRAINT `teksty_ibfk_1` FOREIGN KEY (`struktura_id`) REFERENCES `struktury` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_ibfk_1` FOREIGN KEY (`klient_id`) REFERENCES `klienci` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
