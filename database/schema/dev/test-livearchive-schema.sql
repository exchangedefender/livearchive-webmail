/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE IF NOT EXISTS `migrations` (
                              `id` int unsigned NOT NULL AUTO_INCREMENT,
                              `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `batch` int NOT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


/*DROP TABLE IF EXISTS `messages`;*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL,
  `subject` varchar(990) NOT NULL DEFAULT '',
  `sender` varchar(320) NOT NULL DEFAULT '',
  `recipient` varchar(320) NOT NULL DEFAULT '',
  `size` bigint NOT NULL DEFAULT '0',
  `file_path` text NOT NULL,
  `sender_envelope` varchar(512) NOT NULL,
  `attachment_count` int NOT NULL DEFAULT '0',
  `preview` varchar(160) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `sender_recipient_idx` (`sender`,`recipient`) USING BTREE,
  KEY `timestamp_idx` (`timestamp`) USING BTREE,
  FULLTEXT KEY `subject_idx` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `messages` (`id`, `timestamp`, `subject`, `sender`, `recipient`, `size`, `file_path`, `sender_envelope`, `attachment_count`, `preview`) VALUES
     (23, '2023-12-28 21:51:21', 'test Thu, 28 Dec 2023 16:51:21 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703800281.Vfd19I405f0M947237.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (24, '2023-10-27 14:39:05', 'test Fri, 27 Oct 2023 10:39:05 -0400', 'tsheldon@macbook-air-3.local', 'travis@localexample.exchangedefender.com', 729, 'travis@localexample.exchangedefender.com/Maildir/new/1698417545.Vfd1aI3a20M629757.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (25, '2023-10-27 14:39:05', 'test Fri, 27 Oct 2023 10:39:05 -0400', 'tsheldon@macbook-air-3.local', 'vlad@ownwebnow.com', 729, 'vlad@ownwebnow.com/Maildir/new/1698417545.Vfd1aI3a20M629757.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (26, '2023-12-28 21:51:18', 'test Thu, 28 Dec 2023 16:51:18 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703800278.Vfd19I405eeM459055.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (27, '2023-10-27 14:57:14', 'test Fri, 27 Oct 2023 10:57:14 -0400', 'tsheldon@macbook-air-3.local', 'travis@localexample.exchangedefender.com', 639, 'travis@localexample.exchangedefender.com/Maildir/new/1698418634.Vfd1aI3a1fM122731.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (28, '2024-01-03 22:20:28', 'test Thu, 28 Dec 2023 16:30:28 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 696, 'travis@ownwebnow.com/Maildir/new/1703799028.Vfd19I405dbM611657.livearchive.exchangedefender.com.eml', 'Travis Sheldon <tsheldon@macbook-air-3.local>', 0, 'This is a test maillog'),
     (29, '2023-12-28 21:51:17', 'test Thu, 28 Dec 2023 16:51:17 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703800277.Vfd19I405dcM675969.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (30, '2023-12-28 21:51:20', 'test Thu, 28 Dec 2023 16:51:20 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703800280.Vfd19I405efM287286.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (31, '2023-12-29 07:43:13', 'test Fri, 29 Dec 2023 02:43:13 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703835793.Vfd19I405d9M515619.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (32, '2023-12-29 07:43:14', 'test Fri, 29 Dec 2023 02:43:14 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703835794.Vfd19I405f2M642773.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (33, '2023-12-29 07:43:16', 'test Fri, 29 Dec 2023 02:43:16 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703835796.Vfd19I405f4M146382.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (34, '2023-12-29 07:43:15', 'test Fri, 29 Dec 2023 02:43:15 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703835795.Vfd19I405f3M361449.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (35, '2024-01-01 07:43:15', 'test Mon, 1 Jan 2024 02:43:15 -0500', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1703835795.Vfd19I405f3M361449.livearchive.exchangedefender.com.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (36, '2024-01-05 20:20:28', 'this test has no body in s3', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/does_not_exist_intentionally.eml', 'tsheldon@macbook-air-3.local', 0, 'This is a test maillog'),
     (37, '2024-01-05 22:20:28', 'this test has an attachment', 'tsheldon@macbook-air-3.local', 'travis@ownwebnow.com', 679, 'travis@ownwebnow.com/Maildir/new/1682488577.792dc3099e7f532b4386.postfix-backend-livearchive.eml', 'tsheldon@macbook-air-3.local', 1, 'This is a test maillog');


/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

