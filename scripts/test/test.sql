-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: it490
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre_id` int NOT NULL,
  `overview` varchar(2048) NOT NULL,
  `poster_img_url` varchar(255) NOT NULL,
  `vote_average` float NOT NULL,
  `createdAt` bigint NOT NULL,
  `release_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES ('1084187','Pretty Lethal',28,'A troupe of ballerinas find themselves fighting for survival as they attempt to escape from a remote inn after their bus breaks down on the way to a dance competition.','https://image.tmdb.org/t/p/w500/znTPnXCK3lEQJgqXCvP7e5FUz6f.jpg',6.6,1774538724,'2026-03-13'),('1084242','Zootopia 2',16,'After cracking the biggest case in Zootopia\'s history, rookie cops Judy Hopps and Nick Wilde find themselves on the twisting trail of a great mystery when Gary De\'Snake arrives and turns the animal metropolis upside down. To crack the case, Judy and Nick must go undercover to unexpected new parts of town, where their growing partnership is tested like never before.','https://image.tmdb.org/t/p/w500/oJ7g2CifqpStmoYQyaLQgEU32qO.jpg',7.61,1774538724,'2025-11-26'),('1159559','Scream 7',27,'When a new Ghostface killer emerges in the quiet town where Sidney Prescott has built a new life, her darkest fears are realized as her daughter becomes the next target. Determined to protect her family, Sidney must face the horrors of her past to put an end to the bloodshed once and for all.','https://image.tmdb.org/t/p/w500/jjyuk0edLiW8vOSnlfwWCCLpbh5.jpg',5.807,1774538724,'2026-02-25'),('1171145','Crime 101',80,'When an elusive thief whose high-stakes heists unfold along the iconic 101 freeway in Los Angeles eyes the score of a lifetime, with hopes of this being his final job, his path collides with a disillusioned insurance broker who is facing her own crossroads. Determined to crack the case, a relentless detective closes in on the operation, raising the stakes even higher.','https://image.tmdb.org/t/p/w500/heMdO64ys1hR896YE2jvTv8JlBX.jpg',6.991,1774538724,'2026-02-11'),('1193501','Whistle',27,'A misfit group of unwitting high school students stumble upon a cursed object, an ancient Aztec Death Whistle. They discover that blowing the whistle and the terrifying sound it emits will summon their future deaths to hunt them down.','https://image.tmdb.org/t/p/w500/eGxPyseSnEZBMJaopGfRUO9HSYx.jpg',6.025,1774538724,'2026-01-20'),('1198994','Send Help',27,'Two colleagues become stranded on a deserted island, the only survivors of a plane crash. On the island, they must overcome past grievances and work together to survive, but ultimately, it\'s a battle of wills and wits to make it out alive.','https://image.tmdb.org/t/p/w500/mjkS2iAgWj3ik1DTjvI15nHZ7yl.jpg',7.03,1774538724,'2026-01-22'),('1236153','Mercy',878,'In the near future, a detective stands on trial accused of murdering his wife. He has ninety minutes to prove his innocence to the advanced AI Judge he once championed, before it determines his fate.','https://image.tmdb.org/t/p/w500/pyok1kZJCfyuFapYXzHcy7BLlQa.jpg',7.061,1774538725,'2026-01-20'),('1265609','War Machine',28,'On one last grueling mission during Army Ranger training, a combat engineer must lead his unit in a fight against a giant otherworldly killing machine.','https://image.tmdb.org/t/p/w500/tlPgDzwIE7VYYIIAGCTUOnN4wI1.jpg',7.278,1774538724,'2026-02-12'),('1290821','Shelter',28,'A man living in self-imposed exile on a remote island rescues a young girl from a violent storm, setting off a chain of events that forces him out of seclusion to protect her from enemies tied to his past.','https://image.tmdb.org/t/p/w500/buPFnHZ3xQy6vZEHxbHgL1Pc6CR.jpg',6.7,1774538724,'2026-01-28'),('1311031','Demon Slayer: Kimetsu no Yaiba Infinity Castle',16,'The Demon Slayer Corps are drawn into the Infinity Castle, where Tanjiro, Nezuko, and the Hashira face terrifying Upper Rank demons in a desperate fight as the final battle against Muzan Kibutsuji begins.','https://image.tmdb.org/t/p/w500/fWVSwgjpT2D78VUh6X8UBd2rorW.jpg',7.671,1774538725,'2025-07-18'),('1316092','\"Wuthering Heights\"',10749,'Tragedy strikes when Heathcliff falls in love with Catherine Earnshaw, a woman from a wealthy family in 18th-century England.','https://image.tmdb.org/t/p/w500/3YBce6dTh1D5oCMITXk2S5QhPt.jpg',6.377,1774538725,'2026-02-11'),('1327819','Hoppers',16,'Scientists have discovered how to \'hop\' human consciousness into lifelike robotic animals, allowing people to communicate with animals as animals. Animal lover Mabel seizes an opportunity to use the technology, uncovering mysteries within the animal world beyond anything she could have imagined.','https://image.tmdb.org/t/p/w500/xjtWQ2CL1mpmMNwuU5HeS4Iuwuu.jpg',7.6,1774538725,'2026-03-04'),('1381216','Lake Jesup: Bonecrusher\'s Revenge',27,'In 2003, Lake Jesup became the stage for a real-life horror story, as a monstrous alligator escaped from captivity and began a reign of terror. As the body count rose, a desperate hunt ensued to stop the creature.','https://image.tmdb.org/t/p/w500/1Z1TgGXS1MD4DDfIkBNloM43vvj.jpg',6.1,1774538724,'2024-05-01'),('1523145','Your Heart Will Be Broken',10749,'High school student Polina is saved from bullying at her new school and makes a deal with the main bully Bars: he must pretend to be her boyfriend and protect her, and she must do everything he says. During this game, the couple develops real feelings, but her family and classmates have reasons to separate the lovers.','https://image.tmdb.org/t/p/w500/iGpMm603GUKH2SiXB2S5m4sZ17t.jpg',7.8,1774538724,'2026-03-26'),('1634301','Vanaveera',28,'A descendant of the Vanara clan is pushed into conflict when a modern-day Ravana seizes his only bike for an election rally. What starts as a petty injustice soon erupts into a fierce fight for self-respect and dignity.','https://image.tmdb.org/t/p/w500/oBYExKI8E3bTzQjPkofhpV2EJon.jpg',7,1774538725,'2026-02-13'),('680493','Return to Silent Hill',9648,'When James receives a mysterious letter from his lost love Mary, he is drawn to Silent Hill—a once-familiar town now consumed by darkness. As he searches for her, James faces monstrous creatures and unravels a terrifying truth that will push him to the edge of his sanity.','https://image.tmdb.org/t/p/w500/fqAGFN2K2kDL0EHxvJaXzaMUkkt.jpg',5.007,1774538725,'2026-01-21'),('687163','Project Hail Mary',878,'Science teacher Ryland Grace wakes up on a spaceship light years from home with no recollection of who he is or how he got there. As his memory returns, he begins to uncover his mission: solve the riddle of the mysterious substance causing the sun to die out. He must call on his scientific knowledge and unorthodox ideas to save everything on Earth from extinction… but an unexpected friendship means he may not have to do it alone.','https://image.tmdb.org/t/p/w500/yihdXomYb5kTeSivtFndMy5iDmf.jpg',8.173,1774538724,'2026-03-15'),('83533','Avatar: Fire and Ash',878,'In the wake of the devastating war against the RDA and the loss of their eldest son, Jake Sully and Neytiri face a new threat on Pandora: the Ash People, a violent and power-hungry Na\'vi tribe led by the ruthless Varang. Jake\'s family must fight for their survival and the future of Pandora in a conflict that pushes them to their emotional and physical limits.','https://image.tmdb.org/t/p/w500/bRBeSHfGHwkEpImlhxPmOcUsaeg.jpg',7.267,1774538724,'2025-12-17'),('840464','Greenland 2: Migration',12,'Having found the safety of the Greenland bunker after the comet Clarke decimated the Earth, the Garrity family must now risk everything to embark on a perilous journey across the wasteland of Europe to find a new home.','https://image.tmdb.org/t/p/w500/z2tqCJLsw6uEJ8nJV8BsQXGa3dr.jpg',6.4,1774538724,'2026-01-07'),('875828','Peaky Blinders: The Immortal Man',80,'After his estranged son gets embroiled in a Nazi plot, self-exiled gangster Tommy Shelby must return to Birmingham to save his family — and his nation.','https://image.tmdb.org/t/p/w500/gRMalasZEzsZi4w2VFuYusfSfqf.jpg',7.4,1774538724,'2026-03-05');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `popular_movies`
--

DROP TABLE IF EXISTS `popular_movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `popular_movies` (
  `id` varchar(255) NOT NULL,
  `createdAt` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `popular_movies`
--

LOCK TABLES `popular_movies` WRITE;
/*!40000 ALTER TABLE `popular_movies` DISABLE KEYS */;
INSERT INTO `popular_movies` VALUES ('1084187',1774538723),('1084242',1774538723),('1159559',1774538723),('1171145',1774538723),('1193501',1774538723),('1198994',1774538723),('1236153',1774538723),('1265609',1774538723),('1290821',1774538723),('1311031',1774538723),('1316092',1774538723),('1327819',1774538723),('1381216',1774538723),('1523145',1774538723),('1634301',1774538723),('680493',1774538723),('687163',1774538723),('83533',1774538723),('840464',1774538723),('875828',1774538723);
/*!40000 ALTER TABLE `popular_movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `movie_id` varchar(255) NOT NULL,
  `score` int NOT NULL,
  `review` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,'test_recommend','1266798',8,'This movie freaking rules'),(2,'TEST VALUE','',4,'i really like this movie'),(3,'test','1265609',4,'review'),(4,'test','24428',4,'this is my favorite movie!'),(5,'test','799882',9,'wow!'),(6,'test','1419406',8,'your review'),(7,'test','36819',9,'fantastic drug trip'),(8,'newuser','119423',10,'nice!'),(9,'test_recommend','1266798',8,'This movie freaking rules');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test','it490madd@gmail.com','test'),(2,'test_recommend','it490madd@gmail.com','test'),(3,'newuser','newuser@gmail.com','test'),(4,'test','it490madd@gmail.com','test'),(5,'test_recommend','it490madd@gmail.com','test'),(7,'test2','testingtest@testingtest','$2y$10$K9hTw0wzm4ZlwLJkA8o6c.ti9XqFDmViv1MOBTTsqA4o8S6SLAdf2'),(8,'test3','testingtest@testingtest','$2y$10$l3fab/Ea5NQ.Fkpktq7U8.Is2pRlHdz5eIusFm5vctXdyzORbZ9ha');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `validations`
--

DROP TABLE IF EXISTS `validations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `validations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `sessionKey` varchar(255) NOT NULL,
  `createdAt` bigint NOT NULL,
  `expiresAt` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sessionKey` (`sessionKey`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `validations`
--

LOCK TABLES `validations` WRITE;
/*!40000 ALTER TABLE `validations` DISABLE KEYS */;
INSERT INTO `validations` VALUES (9,'newuser','d29a3f52740a6af16a25',1773331631,1773331931),(12,'test','5a25ffdaa680aa805f74',1774536671,1774536971),(13,'testEn','d0238f04e5391ac2129b',1774537043,1774537343),(20,'test3','2fb457ef46f471a51d8e',1775403635,1775403935);
/*!40000 ALTER TABLE `validations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `watchlist`
--

DROP TABLE IF EXISTS `watchlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `watchlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `movie_id` varchar(255) NOT NULL,
  `movie_name` varchar(255) NOT NULL,
  `release_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watchlist`
--

LOCK TABLES `watchlist` WRITE;
/*!40000 ALTER TABLE `watchlist` DISABLE KEYS */;
INSERT INTO `watchlist` VALUES (1,'test','1265609','War Machine','2026-02-12'),(2,'test','1003596','Avengers: Doomsday','2026-12-16'),(3,'newuser','1003596','Avengers: Doomsday','2026-12-16');
/*!40000 ALTER TABLE `watchlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-05 12:40:38
