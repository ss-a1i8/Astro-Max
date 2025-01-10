CREATE TABLE `users` (
  `user_id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `username` varchar(50) UNIQUE KEY NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','staff','management') DEFAULT 'customer'
);