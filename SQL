-- Users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','active','suspended') DEFAULT 'pending',
  `verification_token` varchar(64) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `2fa_enabled` tinyint(1) DEFAULT 0,
  `2fa_secret` varchar(32) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trading pairs
CREATE TABLE `trading_pairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL,
  `base_currency` varchar(10) NOT NULL,
  `quote_currency` varchar(10) NOT NULL,
  `last_price` decimal(20,8) NOT NULL,
  `price_change_24h` decimal(10,2) NOT NULL,
  `volume_24h` decimal(20,2) NOT NULL,
  `high_24h` decimal(20,8) NOT NULL,
  `low_24h` decimal(20,8) NOT NULL,
  `ma7` decimal(20,8) DEFAULT NULL,
  `ma14` decimal(20,8) DEFAULT NULL,
  `ma28` decimal(20,8) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for trading pairs
INSERT INTO `trading_pairs` (`symbol`, `base_currency`, `quote_currency`, `last_price`, `price_change_24h`, `volume_24h`, `high_24h`, `low_24h`, `ma7`, `ma14`, `ma28`) VALUES
('BTC/USDT', 'BTC', 'USDT', 105740.30, -1.43, 50000000.00, 107500.00, 104200.00, 106200.50, 105800.25, 104500.75),
('ETH/USDT', 'ETH', 'USDT', 2411.60, -3.26, 25000000.00, 2500.00, 2380.00, 2450.25, 2430.50, 2400.75),
('APEX/USDT', 'APEX', 'USDT', 0.1702, -7.80, 500000.00, 0.1850, 0.1680, 0.1750, 0.1725, 0.1700),
('MNT/USDT', 'MNT', 'USDT', 0.5627, -4.53, 3000000.00, 0.5900, 0.5600, 0.5700, 0.5650, 0.5600),
('SOL/USDT', 'SOL', 'USDT', 146.95, -5.47, 15000000.00, 155.00, 145.00, 150.00, 148.50, 145.75),
('ONDO/USDT', 'ONDO', 'USDT', 0.7408, -4.19, 2000000.00, 0.7800, 0.7350, 0.7500, 0.7450, 0.7400);

-- Password reset tokens
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Login attempts logging
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `attempt_type` enum('login','2fa','password_reset') NOT NULL,
  `successful` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
