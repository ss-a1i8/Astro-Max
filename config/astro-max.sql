CREATE TABLE `users` (
  `user_id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `username` varchar(50) UNIQUE KEY NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','staff','management') DEFAULT 'customer'
);

CREATE TABLE `activities` (
  `activity_id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `activity_name` varchar(50) UNIQUE KEY NOT NULL,
  `activity_description` varchar(255) NOT NULL,
  `activity_img` varchar(255) NOT NULL
);