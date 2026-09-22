-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/09/2026 às 21:14
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
-- Banco de dados: `shorty`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `click_logs`
--

CREATE TABLE `click_logs` (
  `id` bigint(20) NOT NULL,
  `url_id` int(11) NOT NULL,
  `utm_source` varchar(64) DEFAULT NULL,
  `utm_medium` varchar(64) DEFAULT NULL,
  `utm_campaign` varchar(64) DEFAULT NULL,
  `referrer` text DEFAULT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `clicked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `urls`
--

CREATE TABLE `urls` (
  `id` int(11) NOT NULL,
  `short_code` varchar(16) NOT NULL,
  `long_url` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `clicks_count` int(11) DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `click_logs`
--
ALTER TABLE `click_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_click_logs_url_id` (`url_id`),
  ADD KEY `idx_click_logs_utm_source` (`url_id`,`utm_source`);

--
-- Índices de tabela `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`),
  ADD KEY `fk_urls_user` (`user_id`),
  ADD KEY `idx_urls_short_code` (`short_code`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `click_logs`
--
ALTER TABLE `click_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `urls`
--
ALTER TABLE `urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `click_logs`
--
ALTER TABLE `click_logs`
  ADD CONSTRAINT `fk_clicks_url` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `urls`
--
ALTER TABLE `urls`
  ADD CONSTRAINT `fk_urls_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
