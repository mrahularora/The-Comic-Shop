-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: comic_book_store
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `comic_book_store`
--

/*!40000 DROP DATABASE IF EXISTS `comic_book_store`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `comic_book_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `comic_book_store`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Marvel','Marvel Comics'),(2,'DC','Dc Comics'),(3,'Other','Other Comic Books');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (3,'rahul@gmail.com','2026-07-15 05:24:20'),(6,'aroraz340@gmail.com','2026-07-15 05:24:45');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderitems`
--

DROP TABLE IF EXISTS `orderitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderitems`
--

LOCK TABLES `orderitems` WRITE;
/*!40000 ALTER TABLE `orderitems` DISABLE KEYS */;
INSERT INTO `orderitems` VALUES (1,15,2,5,18.50),(2,15,14,1,19.99),(3,15,12,1,20.99),(4,16,2,1,18.50),(5,16,14,1,19.99),(6,16,13,1,23.99),(7,17,15,1,13.99),(8,17,2,1,18.50),(9,17,6,1,16.99),(10,18,13,1,23.99),(11,18,8,1,22.00),(12,18,6,1,16.99);
/*!40000 ALTER TABLE `orderitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` varchar(200) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `status` enum('Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Processing',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (15,155,133.48,'2024-08-15 18:27:36','73 SCOTS PINE TRAIL','N2R0N6','5483333418','Shipped'),(16,156,62.48,'2024-08-15 19:20:34','73 scots pine','N2R0N6','5483333418','Shipped'),(17,157,49.48,'2026-07-14 07:48:27','73 scots pine','N2R0N6','5483333418','Shipped'),(18,158,71.17,'2026-07-15 06:00:15','19 Ridge Road , Cambridge','N2R1T4','5483333418','Shipped');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `long_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `writer` varchar(100) DEFAULT NULL,
  `format` varchar(60) DEFAULT NULL,
  `age_rating` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Spider-Man 2','A collection of classic Spider-Man comics by Stan Lee.','Spider-Man 2 collects a series of classic Spider-Man stories penned by the legendary Stan Lee. The collection features some of the most iconic moments in Spider-Man history, showcasing the character’s evolution and his pivotal battles against formidable foes. This compilation is a must-have for both longtime fans and newcomers to the Spider-Man saga.',21.99,'images/products/marvel/9.jpg',1,'MARVEL','','',''),(2,'Batman: Robin and Howard','The iconic Batman story where Bruce Wayne returns to fight crime with his sidekicks Robin and Howard.','In Batman: Robin and Howard, Bruce Wayne returns to his crime-fighting roots alongside his trusted sidekicks, Robin and Howard. This story arc delves into the dynamic between Batman and his allies as they face off against new and old adversaries. With intense action and deep character exploration, this storyline is a thrilling addition to the Batman legacy.',18.50,'images/products/dc/1.jpg',2,NULL,NULL,NULL,NULL),(3,'The Flash hits the wall','The Flash Hits the Wall is a storyline from DC Comics focused on the superhero The Flash, who has powers of incredible speed.','The Flash Hits the Wall explores a high-stakes storyline where The Flash confronts new challenges that test his speed and heroism. As he races against time and faces formidable enemies, this series highlights the limits of his powers and the personal struggles he endures. This storyline is a thrilling read for fans of speed and superhero drama.',22.75,'images/products/dc/2.jpg',2,NULL,NULL,NULL,NULL),(4,'Wonder Woman: The Complete Collection','Explore the adventures of Wonder Woman through George Pérez\'s legendary run.','Wonder Woman: The Complete Collection features George Pérez’s iconic run on the series, showcasing the origins and adventures of the Amazonian warrior. This collection includes reimagined origins, epic battles, and deep mythological explorations, providing a comprehensive view of Wonder Woman’s role in the DC Universe. It’s a definitive collection for any Wonder Woman enthusiast.',25.99,'images/products/dc/11.jpg',2,NULL,NULL,NULL,NULL),(5,'Marvel : Deadpool Vs Variant','Deadpool vs. the Variant is a comic book series that centres on one of Marvel\'s most popular antiheroes.','Deadpool vs. the Variant pits the irreverent antihero Deadpool against a series of alternate versions of himself. This series offers a humorous and action-packed adventure as Deadpool navigates through various realities, facing off against different incarnations and battling his way through chaos and comedy. It’s a unique take on Deadpool’s character with plenty of irreverent humor and thrilling action.',20.99,'images/products/marvel/3.jpg',1,NULL,NULL,NULL,NULL),(6,'The Amazing Spider Girl','The Amazing Spider-Girl is a comic book series by Marvel Comics focusing on May Mayday Parker, the teenage daughter of Peter Parker, the original Spider-Man.','The Amazing Spider-Girl follows the adventures of May \"Mayday\" Parker, the teenage daughter of the original Spider-Man, Peter Parker. This series explores her journey as she steps into the role of a superhero, dealing with the pressures of being a teenage superhero and the legacy of her father. It’s a fresh perspective on the Spider-Man universe with a new generation of web-slinging adventures.',16.99,'images/products/marvel/5.jpg',1,NULL,NULL,NULL,NULL),(7,'Superior Spider-Man','Superior Spider-Man is a major, dramatic Marvel Comics storyline, one which places a major twist upon the Spider-Man saga.','Superior Spider-Man introduces a significant twist in the Spider-Man saga by featuring a new character in the role of Spider-Man. The storyline delves into the complexities of identity and responsibility as the new Spider-Man grapples with the hero’s duties and the legacy of Peter Parker. It’s a dramatic and engaging twist on the beloved superhero’s story.',24.00,'images/products/marvel/6.jpg',1,NULL,NULL,NULL,NULL),(8,'Marvel Voices','Marvel Voices is an anthology series published by Marvel Comics that focuses on amplifying diverse voices and stories within the Marvel Universe','Marvel Voices is an anthology series that highlights diverse voices and stories within the Marvel Universe. Each issue features a range of characters and creators, providing new perspectives and stories that amplify the rich diversity of the Marvel Comics world. It’s an essential series for exploring varied narratives and celebrating the spectrum of voices in superhero storytelling.',22.00,'images/products/marvel/10.jpg',1,NULL,NULL,NULL,NULL),(9,'Marvel : Uncanny Avengers','Uncanny Avengers is a Marvel Comics title crossing the Avengers and the X-Men to study their interaction with each other.','Uncanny Avengers brings together the Avengers and the X-Men in a crossover that explores their interactions and conflicts. This series delves into how these iconic teams collaborate and clash as they face new threats and challenges. It’s a dynamic and action-packed exploration of Marvel’s premier superhero teams.',24.55,'images/products/marvel/12.jpg',1,NULL,NULL,NULL,NULL),(10,'Super Man - House of Brainiac','Superman: House of Brainiac is a comic book storyline by DC Comics dealing with one of the major confrontations between Superman and Brainiac','Superman: House of Brainiac is a thrilling storyline that features a major confrontation between Superman and his nemesis, Brainiac. The narrative explores the high-stakes battle between the two powerful foes as Brainiac’s plans threaten Earth and Superman’s heroic resolve is tested. This story is filled with action, drama, and deep character exploration.',23.49,'images/products/dc/3.jpg',2,NULL,NULL,NULL,NULL),(11,'Titans Out of the Shadows','Titans: Out of the Shadows is a storyline from DC Comics featuring the Teen Titans, a group of young superheroes.','Titans: Out of the Shadows features the Teen Titans as they come into their own as young superheroes. This storyline showcases their adventures, growth, and the challenges they face as a team. The series highlights their individual strengths and their collective efforts to protect the world from emerging threats.',23.49,'images/products/dc/4.jpg',2,NULL,NULL,NULL,NULL),(12,'Shazam: Meet the Captain','Shazam: Meet the Captain is a comic book storyline that introduces and explores the character of Shazam, also known as Captain Marvel, in the DC Universe.','Shazam: Meet the Captain reintroduces Billy Batson and his transformation into Shazam, exploring his origin and powers. This storyline provides a deep dive into Shazam’s character, his magical abilities, and his place within the DC Universe. It offers new insights into his battles, allies, and the magic that empowers him.',20.99,'images/products/dc/5.jpg',2,NULL,NULL,NULL,NULL),(13,'Marvel Adventures: Fantastic Four','Marvel Adventures: Fantastic Four is a Marvel Comics series retelling the adventures of one of Marvel\'s first.','Marvel Adventures: Fantastic Four provides a retelling of the classic adventures of Marvel’s first superhero team: the Fantastic Four. This series captures their groundbreaking stories, teamwork, and battles against formidable villains, presented in a way that’s accessible to new readers while retaining the essence of their iconic adventures.',23.99,'images/products/marvel/4.jpg',1,NULL,NULL,NULL,NULL),(14,'Dungeons and Dragons: Artificers & Alchemy','This sourcebook, or rather this expansion to the Dungeons & Dragons (D&D) setting, describes the class.','Dungeons and Dragons: Artificers & Alchemy expands on the Artificer class, introducing detailed rules and options for crafting, magic items, and alchemical creations. This sourcebook enhances the D&D gameplay experience by providing new tools and rules for players interested in integrating technology and magic into their campaigns.',19.99,'images/products/other/1.jpg',3,NULL,NULL,NULL,NULL),(15,'Conan: The Barbarian','Conan the Barbarian is a comic book series that chronicles the activities of Conan, a powerful and deadly warrior from the Hyborian Age.','Conan the Barbarian chronicles the epic adventures of Conan, a formidable warrior from the Hyborian Age. This series follows Conan’s quests for power, survival, and glory in a mythical and perilous world. With its rich storytelling and vivid illustrations, Conan remains a classic in the fantasy genre.',13.99,'images/products/other/2.jpg',3,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (154,'test','$2y$10$nk7DUxMwDSO9X42ab3PIk.vsS4X0d.jZrgTvt47Ee8G96Ch6b/4fu','test@gmail.com','2024-08-15 16:43:08','customer'),(155,'rahul','$2y$10$CPG4KIfQjLZd4klxR5rJi.vHC35YxBakh6FpLHk4IDmRcM1szyPOS','rarora4475@Conestogac.on.ca','2024-08-15 18:00:13','customer'),(156,'rahul2','$2y$10$EQUT1BlmErHjNLYkbNSXZOkTqPGdAVPDGbirJTOlvzJAEyhYZRNti','rahularora07@hotmail.com','2024-08-15 19:19:40','customer'),(157,'mrahularora','$2y$10$mEFPiQbuT2SP7vBgjSM2z.EHVmLvnkFll6OwujvIx0dC1wO9wt/5m','rahularora@gmail.com','2026-07-14 07:47:05','admin'),(158,'mrahularora1','$2y$10$olLZtvMF3/t5eY54O/.Uquv/wgQjs7U8xLxmCMna2.0ZtTYDqALCu','rahul@gmail.com','2026-07-15 04:17:02','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'comic_book_store'
--

--
-- Dumping routines for database 'comic_book_store'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-15  2:49:57
