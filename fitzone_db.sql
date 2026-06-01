CREATE DATABASE IF NOT EXISTS fitzone_db;
USE fitzone_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `inquiry_replies`;
DROP TABLE IF EXISTS `training_registrations`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `queries`;
DROP TABLE IF EXISTS `memberships`;
DROP TABLE IF EXISTS `trainers`;
DROP TABLE IF EXISTS `classes`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','staff','admin') NOT NULL DEFAULT 'customer',
  `password_reset_required` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`username`, `email`, `password`, `role`, `password_reset_required`) VALUES
('admin', 'admin@fitzone.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0),
('staff', 'staff@fitzone.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 0);

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `classes` (`name`, `description`, `schedule`, `image_path`) VALUES
('ZenFlow Yoga', 'Find your inner peace and build flexibility with our calming Yoga sessions. Perfect for all levels.', 'Mon, Wed, Fri at 8:00 AM', NULL),
('Ignite HIIT', 'High-intensity interval training to boost your metabolism and burn maximum calories in minimum time.', 'Tue, Thu at 6:30 PM', NULL),
('PowerLift Strength', 'Build lean muscle and increase overall strength with our guided strength training sessions.', 'Mon, Wed, Fri at 11:00 AM', NULL);

CREATE TABLE `trainers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `certifications` varchar(255) DEFAULT NULL,
  `price_per_hour` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `trainers` (`name`, `specialty`, `bio`, `image_path`, `certifications`, `price_per_hour`) VALUES
('John Doe', 'Strength & Conditioning', 'Certified strength and conditioning specialist with over 10 years of experience.', NULL, 'Certified NSCA-CSCS', 75.00),
('Jane Smith', 'Yoga & Flexibility', 'A certified yoga instructor who believes in the power of movement to heal the body and calm the mind.', NULL, 'Certified RYT-200', 65.00);

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` text NOT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `memberships` (`name`, `price`, `features`, `is_popular`) VALUES
('Basic Plan', 30.00, 'Full Gym Access;Cardio & Strength Equipment;-No Group Classes;-No Personal Training discounts', 0),
('Premium Plan', 50.00, 'All Basic Plan features;Unlimited Group Classes;10% Off Personal Training;-No Nutrition Counseling', 1),
('VIP Plan', 80.00, 'All Premium Plan features;20% Off Personal Training;Monthly Nutrition Counseling;Exclusive Member Events', 0);

CREATE TABLE `queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','answered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `inquiry_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `query_id` (`query_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `inquiry_replies_ibfk_1` FOREIGN KEY (`query_id`) REFERENCES `queries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inquiry_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `training_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `trainer_id` (`trainer_id`),
  CONSTRAINT `training_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `training_registrations_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `blog_posts` (`title`, `content`, `author_id`, `category`) VALUES
('5 Essential Tips for Beginner Gym-Goers', 'Starting your fitness journey can be daunting, but with the right approach, you can build a strong foundation, prevent injuries, and stay motivated. Here are five crucial tips for beginner gym-goers:\n1. Start Slow and Be Patient\nDon\'t try to do too much too soon. Begin with lighter weights and fewer repetitions, gradually increasing intensity as your strength and endurance improve. Consistency is more important than intensity in the beginning.\n2. Focus on Form, Not Weight\nProper form is crucial to prevent injuries and ensure you\'re effectively targeting the right muscles. Watch videos, use mirrors, and don\'t hesitate to ask a trainer for guidance. Heavier weights with poor form are counterproductive.\n3. Incorporate Both Cardio and Strength Training\nA balanced fitness routine includes both cardiovascular exercises (like running, cycling) and strength training (lifting weights, bodyweight exercises). Cardio improves heart health, while strength training builds muscle and boosts metabolism.\n4. Prioritize Nutrition and Hydration\nYour diet fuels your workouts and aids recovery. Focus on whole foods, lean proteins, healthy fats, and complex carbohydrates. Drink plenty of water throughout the day, especially around your workouts.\n5. Listen to Your Body and Rest\nRest days are just as important as workout days. They allow your muscles to recover and grow. If you feel pain (not just muscle soreness), stop and rest. Overtraining can lead to injuries and burnout.\nRemember, everyone starts somewhere. Celebrate your progress, stay consistent, and enjoy the journey to a healthier you!', 1, 'Workout'),
('Fuel Your Workouts: Easy Protein Smoothie Recipes', 'Protein smoothies are a fantastic way to fuel your body before or after a workout, providing essential nutrients for energy and muscle recovery. Here are a few easy and delicious recipes:\n1. Berry Blast Protein Smoothie\n1 scoop vanilla protein powder\n1 cup mixed berries (fresh or frozen)\n1/2 banana\n1 cup almond milk (or milk of choice)\n1 tbsp chia seeds\nBlend until smooth.\n2. Green Powerhouse Smoothie\n1 scoop unflavored or vanilla protein powder\n1 cup spinach or kale\n1/2 green apple, chopped\n1/2 cup Greek yogurt\n1 cup water or coconut water\nBlend until creamy.\n3. Chocolate Peanut Butter Delight\n1 scoop chocolate protein powder\n1 tbsp peanut butter\n1/2 cup rolled oats\n1 cup milk (dairy or non-dairy)\nIce cubes (optional)\nBlend well.\nAdjust liquid for desired consistency. Enjoy your nutritious and delicious post-workout fuel!', 1, 'Recipes'),
('Client Success Story: From Couch to 5K', 'Meet John, a 45-year-old client who joined FitZone three months ago with a simple goal: to run a 5K marathon. Previously leading a sedentary lifestyle, John was determined to make a change. Here\'s his inspiring journey:\nThe Challenge\nJohn\'s initial fitness level was low. He struggled with even short bursts of jogging. His main challenge was consistency and finding a routine that he could stick to.\nFitZone\'s Solution\nJohn started with our beginner\'s cardio classes, focusing on building stamina. Our trainers helped him set realistic goals and introduced him to a progressive running plan. He also took advantage of our nutrition counseling to improve his diet.\nThe Transformation\nWithin the first month, John noticed significant improvements in his energy levels. By the second month, he was comfortably jogging longer distances. Our community\'s encouragement played a huge role in keeping him motivated. Three months into his journey, John not only completed his first 5K but also shaved minutes off his initial practice times!\nJohn\'s Advice\n"FitZone changed my life. The trainers are incredible, and the atmosphere is so supportive. Don\'t be afraid to start, even if you feel like you\'re not \'fit enough.\' Every step counts!"\nJohn\'s story is a testament to what you can achieve with dedication and the right support. Join FitZone today and write your own success story!', 1, 'Success Stories');
