CREATE TABLE `users` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `email` varchar(255) DEFAULT NULL,
                         `password` varchar(500) DEFAULT NULL,
                         `phone` varchar(100) DEFAULT NULL,
                         `name` varchar(100) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;