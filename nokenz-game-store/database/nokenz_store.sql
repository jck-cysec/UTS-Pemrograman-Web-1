-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2025 at 10:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nokenz_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `game_id` int DEFAULT NULL,
  `qty` int DEFAULT '1',
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int NOT NULL,
  `nama_game` varchar(150) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `deskripsi` text,
  `gambar` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `nama_game`, `genre`, `platform`, `harga`, `deskripsi`, `gambar`, `created_at`) VALUES
(7, 'The Witcher 3: Wild Hunt', 'RPG', 'PC', 350000, 'Open-world action RPG set in a dark fantasy universe. Play as Geralt of Rivia, a monster slayer.', 'witcher3.jpg', '2025-11-20 23:32:02'),
(8, 'God of War (2018)', 'Action', 'PC', 450000, 'Kratos and his son Atreus embark on a deeply personal journey through the Norse wilds.', 'gow2018.jpg', '2025-11-20 23:33:44'),
(9, 'Cyberpunk 2077', 'RPG', 'PC', 600000, 'An open-world, action-adventure story set in Night City, a megalopolis obsessed with power, glamour and body modification.', 'cyberpunk2077.jpg', '2025-11-20 23:34:12'),
(10, 'Doom Eternal', 'FPS', 'PC', 380000, 'The ultimate speed-and-power experience. Rip and tear through dimensions as the Doom Slayer.', 'doometernal.jpg', '2025-11-20 23:35:59'),
(11, 'Red Dead Redemption 2', 'Adventure', 'PC', 550000, 'An epic tale of life in America at the dawn of the modern age. Arthur Morgan, an outlaw, must choose.', 'rdr2.jpg', '2025-11-20 23:36:26'),
(12, 'Hades', 'Roguelike', 'PC', 250000, 'A rogue-like dungeon crawler where you defy the god of the dead as you hack and slash your way out of the Underworld.', 'hades.jpg', '2025-11-20 23:38:35'),
(13, 'League of Legends', 'MOBA', 'PC', 0, 'Free-to-play, highly competitive multiplayer online battle arena. (Input as Free/0)', 'lol.jpg', '2025-11-20 23:40:19'),
(14, 'FIFA 24', 'Sports', 'PS5', 800000, 'The newest installment in the football simulation franchise. Experience unparalleled realism on PS5.', 'fifa24.jpg', '2025-11-20 23:40:49'),
(15, 'Elden Ring', 'Action', 'PS5', 700000, 'A fantasy action RPG adventure set within a world created by Hidetaka Miyazaki and George R. R. Martin.', 'eldenring.jpg', '2025-11-20 23:41:18'),
(16, 'Call of Duty: Modern Warfare III', 'FPS', 'PC', 780000, 'The next installment in the iconic FPS franchise with a new campaign and multiplayer maps.', 'codmw3.jpg', '2025-11-20 23:41:53'),
(17, 'Diablo IV', 'RPG', 'PS5', 850000, 'An epic new chapter in the Diablo series, featuring a vast open world and deep character customization.', 'diablo4.jpg', '2025-11-20 23:42:19'),
(18, 'Metroid Dread', 'Adventure', 'Switch', 580000, 'Samus Aran explores a dangerous new planet, hunted by the formidable E.M.M.I. robots.', 'metroid.jpg', '2025-11-20 23:42:44'),
(19, 'Pokémon Scarlet', 'RPG', 'Switch', 600000, 'The newest generation of Pokémon adventure in an open-world setting.', 'pokemonscarlet.jpg', '2025-11-20 23:43:13'),
(20, 'Mario Kart 8 Deluxe', 'Racing', 'Switch', 620000, 'Race and battle your friends in the ultimate Mario Kart game.', 'mariokart8.jpg', '2025-11-20 23:43:49'),
(21, 'Animal Crossing: New Horizons', 'Simulation', 'Switch', 650000, 'Build your own paradise island life in real-time. A relaxing, social simulation game.', 'animalcrossing.jpg', '2025-11-20 23:44:40'),
(22, 'Spider-Man: Miles Morales', 'Action', 'PS5', 650000, 'Miles Morales adapts to his new powers under the mentorship of Peter Parker.', 'milesmorales.jpg', '2025-11-20 23:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `nama`, `created_at`) VALUES
(1, 'RPG', '2025-11-20 12:12:19'),
(2, 'Action', '2025-11-20 12:24:31'),
(3, 'Adventure', '2025-11-20 23:02:32'),
(4, 'FPS', '2025-11-20 23:34:31'),
(5, 'Simulation', '2025-11-20 23:34:42'),
(6, 'Roguelike', '2025-11-20 23:34:47'),
(7, 'MOBA', '2025-11-20 23:34:51'),
(8, 'Sports', '2025-11-20 23:34:56'),
(9, 'Racing', '2025-11-20 23:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `deskripsi` text,
  `tanggal` date DEFAULT NULL,
  `gambar` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `judul`, `deskripsi`, `tanggal`, `gambar`) VALUES
(3, 'Roblox 2025 Adopsi Konsep Futuristis, Integrasi Teknologi Virtual Mendalam', 'Event tahunan Roblox mengusung tema futuristik dengan tantangan interaktif baru dan teknologi virtual yang lebih imersif, memperkuat posisi metaverse sebagai platform kreasi dan kompetisi.', '2025-11-19', 'news_roblox.jpg'),
(4, 'Tren PC Gaming Rakitan 4 Jutaan Meningkat Jelang Akhir 2025', 'Meskipun tren cloud gaming naik, permintaan untuk PC rakitan entry-level tetap tinggi di pasar Asia, didorong oleh peningkatan ketersediaan komponen dan popularitas game e-sport ringan.', '2025-11-20', 'news_pcrakit.jpg'),
(5, 'Ray Tracing Generasi Baru: NVIDIA Luncurkan DLSS 4.0 untuk Grafis Hiper-Realistik', 'NVIDIA mengumumkan DLSS versi 4.0 yang menjanjikan peningkatan frame rate signifikan pada resolusi 4K dan 8K tanpa mengorbankan detail grafis, menandai lompatan besar dalam visual game PC.', '2025-11-15', 'news_dlss4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `tanggal_order` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_channel_details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_harga`, `status`, `tanggal_order`, `payment_method`, `payment_proof`, `payment_channel_details`) VALUES
(6, 4, 1450000, 'paid', '2025-11-21 07:41:01', 'ovo', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `game_id` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `qty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `game_id`, `harga`, `qty`) VALUES
(7, 6, 21, 650000, 1),
(8, 6, 14, 800000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `platforms`
--

CREATE TABLE `platforms` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `platforms`
--

INSERT INTO `platforms` (`id`, `nama`, `created_at`) VALUES
(1, 'PC', '2025-11-20 12:12:21'),
(2, 'Xbox', '2025-11-20 12:24:35'),
(3, 'Gameboy', '2025-11-20 23:02:54'),
(4, 'PS5', '2025-11-20 23:35:16'),
(5, 'Switch', '2025-11-20 23:35:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@nokenz.com', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', '2025-11-19 08:50:12'),
(4, 'asd', 'asd@example.com', 'asd', '7815696ecbf1c96e6894b779456d330e', 'user', '2025-11-20 05:59:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `platforms`
--
ALTER TABLE `platforms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
