-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Final Version
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lapakgame`
--
CREATE DATABASE IF NOT EXISTS `lapakgame` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `lapakgame`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(50) DEFAULT NULL,
  `admin_email` varchar(50) DEFAULT NULL,
  `admin_password` varchar(255) DEFAULT NULL, /* REKOMENDASI: Gunakan VARCHAR(255) untuk menampung password yang di-hash */
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_name`, `admin_email`, `admin_password`) VALUES
('Calvin', 'calvin@gmail.com', '123'),
('Salman', 'salman@gmail.com', '124'),
('Apip', 'apip@gmail.com', '125'),
('Rizky', 'rizky@gmail.com', '126');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_cost` decimal(20,0) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'not paid',
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE IF NOT EXISTS `order_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT 0,
  `product_id` int(11) DEFAULT 0,
  `product_name` varchar(100) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_price` decimal(20,0) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
   PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) DEFAULT NULL,
  `product_category` varchar(50) DEFAULT NULL,
  `product_description` varchar(500) DEFAULT NULL,
  `product_price` decimal(20,0) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_file` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `spec_os` varchar(100) DEFAULT NULL,
  `spec_processor` varchar(100) DEFAULT NULL,
  `spec_memory` varchar(50) DEFAULT NULL,
  `spec_graphics` varchar(100) DEFAULT NULL,
  `spec_storage` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_category`, `product_description`, `product_price`, `product_image`, `product_file`, `created_at`, `spec_os`, `spec_processor`, `spec_memory`, `spec_graphics`, `spec_storage`) VALUES
(1, 'Call of Duty: Modern Warfare 3', 'Strategy', 'Call of Duty 3 (COD 3) adalah game tembak-menembak orang pertama (FPS) yang dirilis pada tahun 2006. Ini adalah entri ketiga dalam seri Call of Duty, dikembangkan oleh Treyarch dan diterbitkan oleh Activision.', '800000', 'Call_of_Duty_Modern_Warfare 3.jpg', 'call_of_duty_modern_warfare_3.zip', NULL, 'Windows 10/11', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '70 GB'),
(2, 'Cyberpunk 2077', 'RPG', 'Cyberpunk 2077 adalah sebuah permainan video action RPG yang dikembangkan dan dipublikasikan oleh CD Projekt Red. Berlatar di sebuah dunia terbuka bernama Night City, California, dalam dunia distopia Cyberpunk.', '850000', '1749557088_Cyberpunk_2077.jpg', 'cyberpunk_2077.zip', NULL, '64-bit Windows 10', 'Intel Core i7-6700 atau AMD Ryzen 5 1600', '8 GB', 'NVIDIA GeForce GTX 1060 6GB', '71 GB'),
(3, 'Devil May Cry', 'Action', 'Devil May Cry 5 adalah game aksi hack and slash yang dikembangkan dan diterbitkan oleh Capcom. Game ini menawarkan pengalaman bertarung yang dinamis dan stylish dengan karakter yang kuat dan musuh yang beragam.', '500000', '1749557779_Devil_May_Cry.jpg', 'devil_may_cry_5.zip', NULL, 'Windows 10/11', 'Intel? Core? i5-4460 / AMD FX?-6300', '8 GB', 'NVIDIA? GeForce? GTX 760 atau AMD Radeon? R7 260x 2GB', '36 GB'),
(4, 'Black Myth: Wukong', 'RPG', 'Black Myth: Wukong adalah game aksi RPG yang didasarkan pada mitologi Tiongkok, khususnya cerita Sun Wukong, kera sakti. Pemain akan menjelajahi dunia yang penuh keajaiban dan tantangan, mengungkap kebenaran di balik legenda tersebut.', '650000', 'Black Myth - Wukong.jpg', 'black_myth_wukong.zip', NULL, 'Windows 10/11', ' Intel Core i7-9700 / AMD Ryzen 5 5500', '8 GB', 'NVIDIA GeForce RTX 2060 / AMD Radeon RX 5700 XT', '130 GB'),
(5, 'Resident Evil Village', 'Horror', 'Resident Evil Village adalah game survival horror ber-genre first-person, dilanjutkan dari Resident Evil 7, di mana pemain mengendalikan Ethan Winters yang mencari putrinya yang diculik di desa Rumania.', '600000', '1749558309_Resident_Evil_Village.jpg', 'resident_evil_village.zip', NULL, 'Windows 10/11', 'AMD Ryzen 3 1200 atau Intel Core i5-7500', '8 GB', 'GPU AMD Radeon RX 560 atau NVIDIA GeForce GTX 1050 Ti dengan VRAM 4 GB', '51 GB'),
(6, 'The Sims 4', 'Simulation', 'The Sims 4 adalah gim simulasi kehidupan yang memungkinkan pemain untuk membuat dan mengendalikan karakter Sims mereka sendiri.', '145000', '1749558486_The_Sims_4.jpg', 'the_sims_4.zip', NULL, '64-bit Windows 10', 'Intel Core i3-3220 3,3 GHz (2 inti, 4 utas) atau AMD Ryzen 3 1200 3,1 GHz (4 inti)', '4 GB', 'NVIDIA GTX 650', '10 GB'),
(7, 'God of War (2018)', 'Action', 'God of War (2018) adalah game aksi-petualangan yang dikembangkan oleh Santa Monica Studio dan diterbitkan oleh Sony Interactive Entertainment.', '200000', '1749558739_God_of_War_(2018).jpg', 'god_of_war_2018.zip', NULL, '64-bit Windows 10', 'Intel i5-2500k atau AMD Ryzen 3 1200', '8 GB', 'NVIDIA GTX 960 (4 GB) atau AMD R9 290X (4 GB)', '71 GB'),
(8, 'NB2K23', 'Sports', 'Gim Basket Simulasi: NBA 2K23 menawarkan pengalaman bermain basket yang realistis, lengkap dengan gameplay, pemain, dan tim yang didasarkan pada liga NBA nyata.', '145000', '1749558849_NB2K23.jpg', 'nb2k23.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7980H ', '8 GB', 'NVIDIA GeForce GTX 1080Ti', '40 GB'),
(9, 'PES 2021', 'Sports', 'PES 2021, atau sekarang dikenal sebagai eFootball 2022 (dan seterusnya), adalah game sepak bola yang dikembangkan oleh Konami.', '145000', '1749558947_PES_2021.jpg', 'pes_2021.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7980H ', '8 GB', 'NVIDIA 9800 GT 1GB', '51 GB'),
(10, 'Planet Zoo', 'Simulation', 'Planet Zoo adalah game simulasi konstruksi dan manajemen yang memungkinkan pemain membangun, mengelola, dan menyesuaikan kebun binatang mereka sendiri dengan berbagai hewan yang realistis.', '145000', '1749559106_Planet_Zoo.jpg', 'planet_zoo.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7400u', '4 GB', 'NVIDIA GeForce GTX 1080Ti', '33 GB'),
(11, 'Resident Evil 2', 'Horror', 'Resident Evil 2 adalah game horor sintasan (survival horror) yang dikembangkan oleh Capcom, dan ini adalah remake dari game yang dirilis pada tahun 1998.', '500000', '1749559214_Resident_Evil_2.jpg', 'resident_evil_2.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7980H ', '8 GB', 'NVIDIA 9800 GT 1GB', '47 GB'),
(12, 'Sekiro: Shadows Die Twice', 'Action', 'Sekiro: Shadows Die Twice adalah game petualangan aksi yang menegangkan dengan latar belakang Jepang abad ke-14.', '350000', 'Sekiro_Shadows_Die_Twice.jpg', 'sekiro_shadows_die_twice.zip', NULL, 'Windows 10/11', 'AMD Phenom 9850 Quad-Core ', '8 GB', 'NVIDIA 9800 GT 1GB', '50 GB'),
(13, 'Stardew Valley', 'Simulation', 'Stardew Valley adalah permainan peran video simulasi pertanian dan peternakan yang dikembangkan secara swadaya oleh Eric Barone.', '30000', '1749559556_Stardew_Valley.jpg', 'stardew_valley.zip', NULL, 'Windows 8 32 Bit', 'Amd Ryzen 4360U ', '4 GB', 'NVIDIA GeForce GTX 1080Ti', '6 GB'),
(14, 'EA Sports FC 24', 'Sports', 'EA Sports FC 24 adalah game sepak bola yang dikembangkan oleh EA Sports, pengganti dari seri FIFA.', '200000', '1749623463_EA_Sports_FC_24.jpg', 'ea_sports_fc_24.zip', NULL, 'Windows 10/11', 'Intel Core i7-6700 atau AMD Ryzen 7 2700X', '8 GB', 'NVIDIA GeForce GTX 1660 atau AMD RX 5600 XT', '90 GB'),
(15, 'FIFA 22', 'Sports', 'FIFA 22 adalah game video simulasi sepak bola yang dikembangkan dan diterbitkan oleh Electronic Arts, bagian dari seri FIFA.', '170000', '1749623628_FIFA_22.jpg', 'fifa_22.zip', NULL, 'Windows 10/11', 'Amd Ryzen 5 4980H ', '8 GB', 'NVIDIA GeForce GTX 1080Ti', '70 GB'),
(16, 'Need for Speed Heat', 'Sports', 'Need for Speed Heat adalah game balapan aksi yang mengambil tempat di kota fiksi Palm City, yang terinspirasi dari Miami, Florida.', '700000', '1749623758_Need_for_Speed_Heat.jpg', 'need_for_speed_heat.zip', NULL, 'Windows 10/11', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'GPU AMD Radeon RX 560 atau NVIDIA GeForce GTX 1050 Ti dengan VRAM 4 GB', '90 GB'),
(17, 'Elden Ring', 'Offline Games', 'Elden Ring adalah game RPG aksi dunia terbuka yang dikembangkan oleh FromSoftware dan diterbitkan oleh Bandai Namco Entertainment.', '350000', '1749640852_Elden_Ring.jpg', 'elden_ring.zip', NULL, 'Windows 10/11', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '51 GB'),
(18, 'Ghost of Tsushima', 'Offline Games', 'Ghost of Tsushima adalah game dengan dunia terbuka yang berlatar di Jepang zaman feodal, di mana pemain berperan sebagai samurai Jin Sakai yang harus melawan invasi Mongol.', '500000', '1749640964_Ghost_of_Tsushima.jpg', 'ghost_of_tsushima.zip', NULL, 'Windows 10 64-bit ', 'AMD Phenom 9850 Quad-Core ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '71 GB'),
(19, 'Hogwarts Legacy', 'Offline Games', 'Hogwarts Legacy adalah game fantasi dunia terbuka yang memungkinkan pemain menjadi murid di Hogwarts School of Witchcraft and Wizardry.', '650000', '1749641111_Hogwarts_Legacy.jpg', 'hogwarts_legacy.zip', NULL, 'Windows 10/11', 'Intel? Core? i5-4460 / AMD FX?-6300', '8 GB', 'NVIDIA? GeForce? GTX 760 atau AMD Radeon? R7 260x 2GB', '172 GB'),
(20, 'Red Dead Redemption 2', 'Offline Games', 'Red Dead Redemption 2 (RDR2) adalah game Offline open world yang dikembangkan oleh Rockstar Games.', '850000', '1749641213_Red_Dead_Redemption_2.jpg', 'red_dead_redemption.zip', NULL, 'Windows 10/11', 'AMD Phenom 9850 Quad-Core ', '8 GB', 'NVIDIA 9800 GT 1GB', '90 GB'),
(21, 'Spider-Man 2', 'Offline Games', 'Game ini mengikuti Peter Parker dan Miles Morales saat mereka berjuang melawan Kraven the Hunter, seorang pemburu yang ingin membuat kota New York menjadi arena perburuan, dan Venom, yang mengancam untuk merusak Peter.', '600000', '1749641282_Spider-Man_2.jpg', 'spiderman_2.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7980H ', '8 GB', 'NVIDIA 9800 GT 1GB', '71 GB'),
(22, 'God of War: Ragnarok', 'Action', 'God of War: Ragnarok adalah game aksi yang dikembangkan oleh Santa Monica Studio dan diterbitkan oleh Sony Interactive Entertainment.', '870000', 'God of War Ragnarok.jpg', 'god_of_war_ragnarok.zip', NULL, 'Windows 10/11', 'Intel? Core? i5-4460 / AMD FX?-6300', '8 GB', 'GPU AMD Radeon RX 560 atau NVIDIA GeForce GTX 1050 Ti dengan VRAM 4 GB', '85 GB'),
(23, 'Directive 8020', 'Horror', 'Directive 8020 adalah permainan drama interaktif dan survival horror yang disajikan dari sudut pandang orang ketiga.', '450000', '1749641564_Directive_8020.jpg', 'directive_8020.zip', NULL, 'Windows 10/11', 'Amd Ryzen 5 7980H ', '8 GB', 'GPU AMD Radeon RX 560 atau NVIDIA GeForce GTX 1050 Ti dengan VRAM 4 GB', '69 GB'),
(24, 'Resident Evil 4 Remake', 'Horror', 'Resident Evil 4 Remake (atau sering disebut RE4 Remake) adalah game survival horror yang dikembangkan oleh Capcom.', '760000', '1749641642_Resident_Evil_4_Remake.jpg', 'resident_evil_4_remake.zip', NULL, 'Windows 10/11', 'Amd Ryzen 5 7980H ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '96 GB'),
(25, 'Senua\'s Saga Hellblade II', 'Horror', 'Senua\'s Saga: Hellblade II adalah gim horro yang dikembangkan oleh Ninja Theory dan diterbitkan oleh Xbox Game Studios.', '245000', 'senua_saga_hellblade_2.jpg', 'senua\'s_saga_hellblade_2.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 4 4700U ', '8 GB', 'NVIDIA GeForce GTX 1080Ti', '48 GB'),
(26, 'Silent Hill 2 Remake', 'Horror', 'Silent Hill 2 Remake adalah sebuah game survival horror yang dikembangkan oleh Bloober Team dan diterbitkan oleh Konami.', '550000', '1749641841_Silent_Hill_2_Remake.jpg', 'silent_hill_2_remake.zip', NULL, 'Windows 10/11', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '90 GB'),
(27, 'Cities: Skylines', 'Simulation', 'Cities: Skylines adalah game simulasi kota yang dikembangkan oleh Colossal Order dan diterbitkan oleh Paradox Interactive.', '450000', 'CitiesSkylines.jpg', 'cities_skylines.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7 6500H', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '71 GB'),
(28, 'Animal Crossing: New Horizons ', 'Simulation', 'Animal Crossing: New Horizons adalah game simulasi kehidupan sosial yang dikembangkan dan diterbitkan oleh Nintendo untuk Nintendo Switch.', '430000', 'AnimalCrossingNewHorizons.jpg', 'animal_crossing_new_horizons.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 5 7980H ', '8 GB', 'NVIDIA 9800 GT 1GB', '71 GB'),
(29, 'Dragon Age: Inquisition', 'RPG', 'Dragon Age: Inquisition adalah game RPG aksi (action role-playing game) yang dikembangkan oleh BioWare dan diterbitkan oleh Electronic Arts.', '850000', 'Dragon Age Inquisition.jpg', 'dragon_age_inquisition.zip', NULL, 'Windows 10/11', 'AMD Phenom 9850 Quad-Core ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '86 GB'),
(30, 'Final Fantasy XVI', 'RPG', 'Final Fantasy XVI adalah game role-playing yang dikembangkan dan dirilis oleh Square Enix.', '670000', '1749642223_Final_Fantasy_XVI.jpg', 'final_fantasy_xvi.zip', NULL, 'Windows 10 64-bit ', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '71 GB'),
(31, 'Persona 5 Royal', 'RPG', 'Persona 5 Royal adalah versi yang disempurnakan dari Persona 5, dengan penambahan konten cerita, karakter, dan gameplay baru.', '370000', '1749642289_Persona_5_Royal.jpg', 'persona_5_royal.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 5 7980H ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '52 GB'),
(32, 'Tales of Arise', 'RPG', 'Tales of Arise adalah game aksi RPG yang dikembangkan dan diterbitkan oleh Bandai Namco Entertainment.', '760000', '1749642354_Tales_of_Arise.jpg', 'tales_of_arise.zip', NULL, 'Windows 10/11', 'AMD Phenom 9850 Quad-Core ', '8 GB', 'NVIDIA GeForce GTX 1080Ti', '90 GB'),
(33, 'The Witcher 3: Wild Hunt', 'RPG', 'The Witcher 3: Wild Hunt adalah game action RPG yang berlatar di dunia fantasi terbuka, di mana pemain mengendalikan Geralt of Rivia, seorang witcher yang mencari putri angkatnya yang hilang.', '690000', 'The_Witcher_3_Wild_Hunt.jpg', 'the_witcher_3_wild_hunt.zip', NULL, 'Windows 10/11', 'Amd Ryzen 7 6980H ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '93 GB'),
(34, 'Human Fall Flat', 'Puzzle', 'Human Fall Flat adalah game platformer fisika yang lucu dan ringan, berlatar belakang alam mimpi yang bisa dimainkan sendirian atau hingga 8 pemain secara online.', '350000', '1749642555_Human_Fall_Flat.jpg', 'human_fall_flat.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 5 6380U', '8 GB', 'NVIDIA 9800 GT 1GB', '35 GB'),
(35, 'Portal 2', 'Puzzle', 'Portal 2 adalah game teka-teki orang pertama yang dikembangkan oleh Valve. Pemain mengendalikan Chell, yang kembali ke fasilitas Aperture Science untuk menghadapi GLaDOS.', '580000', '1749642613_Portal_2.jpg', 'portal_2.zip', NULL, 'Windows 10/11', 'Amd Ryzen 5 7980H ', '8 GB', 'NVIDIA GeForce GTX 960 / GTX 1650 atau AMD Radeon RX 470', '90 GB'),
(36, 'The Talos Principle', 'Puzzle', 'The Talos Principle adalah game puzzle first-person yang dikembangkan oleh Croteam dan diterbitkan oleh Devolver Digital.', '370000', '1749642679_The_Talos_Principle.jpg', 'the_talos_principle.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 5 6980U - atau setara', '8 GB', 'NVIDIA 9800 GT 1GB', '71 GB'),
(37, 'The Witness', 'Puzzle', 'The Witness adalah game teka-teki yang dikembangkan oleh Jonathan Blow, kreator Braid.', '245000', '1749642822_The_Witness.jpg', 'the_witness.zip', NULL, 'Windows 10 64-bit ', 'Amd Ryzen 5 6380U', '8 GB', 'NVIDIA 9800 GT 1GB', '44 GB'),
(38, 'Grand Theft Auto VI - 2025', 'Action', 'Game penuh aksi opend-world dengan grafik yang memukau', '860000', '1749776363_Grand_Theft_Auto_VI_-_2025.jpg', 'grand_theft_auto_vi_2025.zip', NULL, 'Windows 10/11', 'Intel Core i5-6600 atau AMD Ryzen 5 1400', '8 GB', 'GPU AMD Radeon RX 560 atau NVIDIA GeForce GTX 1050 Ti dengan VRAM 4 GB', '70 GB');
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) DEFAULT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL, /* REKOMENDASI: Gunakan VARCHAR(255) untuk menampung password yang di-hash */
  `user_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_photo`) VALUES
(1, 'Calvin', 'calvin@gmail.com', '123', 'calvin.jpg'),
(2, 'Salman', 'salman@gmail.com', '124', 'salman.jpg'),
(3, 'Apip', 'apip@gmail.com', '125', 'apip.jpg'),
(4, 'Rizky', 'rizky@gmail.com', '126', 'rizky.jpg');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;