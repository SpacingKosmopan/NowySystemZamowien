-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 03:03 PM
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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tagi`
--

CREATE TABLE `tagi` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy_zamowien`
--

CREATE TABLE `typy_zamowien` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `typ_id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL DEFAULT 'brak tytułu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `struktury`
--
ALTER TABLE `struktury`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tagi`
--
ALTER TABLE `tagi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teksty`
--
ALTER TABLE `teksty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `typy_zamowien`
--
ALTER TABLE `typy_zamowien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
