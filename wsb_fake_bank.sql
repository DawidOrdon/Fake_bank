-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 22 Kwi 2023, 18:58
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `wsb_fake_bank`
--
CREATE DATABASE IF NOT EXISTS `wsb_fake_bank` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `wsb_fake_bank`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `account_hash`
--

CREATE TABLE `account_hash` (
  `id` int(11) NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `id_pattern` int(11) NOT NULL,
  `hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `id_account_hash` int(11) NOT NULL,
  `wrong_password` int(11) NOT NULL,
  `date` date NOT NULL,
  `succes` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `patterns`
--

CREATE TABLE `patterns` (
  `id` int(11) NOT NULL,
  `number_of_characters` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pattern_char`
--

CREATE TABLE `pattern_char` (
  `id` int(11) NOT NULL,
  `id_pattern` int(11) NOT NULL,
  `pass_char` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `pesel` varchar(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `account_hash`
--
ALTER TABLE `account_hash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_account` (`id_user`),
  ADD KEY `id_pattern` (`id_pattern`);

--
-- Indeksy dla tabeli `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`,`id_account_hash`),
  ADD KEY `id_account_pattern` (`id_account_hash`);

--
-- Indeksy dla tabeli `patterns`
--
ALTER TABLE `patterns`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `pattern_char`
--
ALTER TABLE `pattern_char`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pattern` (`id_pattern`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `account_hash`
--
ALTER TABLE `account_hash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `patterns`
--
ALTER TABLE `patterns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `pattern_char`
--
ALTER TABLE `pattern_char`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `account_hash`
--
ALTER TABLE `account_hash`
  ADD CONSTRAINT `account_hash_ibfk_2` FOREIGN KEY (`id_pattern`) REFERENCES `patterns` (`id`),
  ADD CONSTRAINT `account_hash_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Ograniczenia dla tabeli `pattern_char`
--
ALTER TABLE `pattern_char`
  ADD CONSTRAINT `pattern_char_ibfk_1` FOREIGN KEY (`id_pattern`) REFERENCES `patterns` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
