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

CREATE TABLE `bookings` (
  `booking_id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `user_id` int(10) NOT NULL,
  `activity_id` int(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);