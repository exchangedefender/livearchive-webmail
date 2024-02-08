/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `messages` (`id`, `timestamp`, `subject`, `sender`, `recipient`, `size`, `file_path`, `sender_envelope`, `attachment_count`, `preview`) VALUES
    (1, '2023-04-26 23:52:42', 'Hosting Invoice # ZIN581607 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 134534, 'demo@exchangedefender.com/Maildir/new/1682553162.792dc309afba8c8f4387.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN581607\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (2, '2023-04-26 23:52:42', 'Hosting Invoice # ZIN581607 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 134534, 'demo@exchangedefender.com/Maildir/new/1682553162.792dc309afba8c8f4387.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN581607\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (3, '2024-01-16 11:36:58', '3 Days only! Up to $550 off your vacay resolutions.', 'jetblueairways@email.jetblue.com', 'demo@exchangedefender.com', 86797, 'demo@exchangedefender.com/Maildir/new/3 Days only! Up to $550 off your vacay resolutions..livearchive.exchangedefender.com.eml', 'JetBlue Vacations <jetblueairways@email.jetblue.com>', 0, 'Save big on packages. View in a web browser Preference Center | Help | Business Travel | Privacy | About JetBlue Add jetblueairways@email.jetblue.com to your...'),
    (4, '2024-01-12 20:22:00', 'Just dropped! Shop fresh weekly deals', 'store-news@amazon.com', 'demo@exchangedefender.com', 113856, 'demo@exchangedefender.com/Maildir/new/Just dropped! Shop fresh weekly deals.eml', 'Amazon.com <store-news@amazon.com>', 0, '\r\n  \r\nA variety of products https://www.amazon.com/deals?ref_=h_1_1_manu_img\r\n\r\n\r\nIncredible savings are waiting\r\n\r\nCheck out today’s deals\r\n\r\nShop now (ht...'),
    (5, '2024-01-16 16:38:05', 'Move more. Live healthier. Save now.', 'googlestore-noreply@google.com', 'demo@exchangedefender.com', 111306, 'demo@exchangedefender.com/Maildir/new/Move more. Live healthier.livearchive.exchangedefender.com.eml', 'Google Store <googlestore-noreply@google.com>', 0, 'Shop deals on motivating Pixel devices\r\n‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ...'),
    (6, '2023-09-07 08:42:20', 'Hosting Invoice # ZIN610274 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139805, 'demo@exchangedefender.com/Maildir/new/1694076140.792dc30928605b8c4614.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN610274\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (7, '2023-09-07 08:42:20', 'Hosting Invoice # ZIN610274 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139805, 'demo@exchangedefender.com/Maildir/new/1694076140.792dc30928605b8c4614.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN610274\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (8, '2023-03-08 09:03:39', 'Hosting Invoice # ZIN573441 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139935, 'demo@exchangedefender.com/Maildir/new/1678266219.792dc309c2c020784155.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN573441\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (9, '2023-03-08 09:03:39', 'Hosting Invoice # ZIN573441 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139935, 'demo@exchangedefender.com/Maildir/new/1678266219.792dc309c2c020784155.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN573441\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (10, '2023-01-30 20:03:07', 'Hosting Invoice # ZIN562980 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135665, 'demo@exchangedefender.com/Maildir/new/1675108987.792dc309cc59bd3d3823.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN562980\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (11, '2023-01-30 20:03:07', 'Hosting Invoice # ZIN562980 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135665, 'demo@exchangedefender.com/Maildir/new/1675108987.792dc309cc59bd3d3823.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN562980\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (12, '2023-04-26 05:56:17', 'Hosting - First Past Due Reminder DEMO01 - Total Past Due 5,080.00', 'receivables@hosting.com', 'demo@exchangedefender.com', 163909, 'demo@exchangedefender.com/Maildir/new/1682488577.792dc3099e7f532b4386.livearchive.exchangedefender.com.eml', 'Auto_Correspondence <receivables@hosting.com>', 1, 'Dear Accounts Payable,\n \nYou are receiving this email and the attached letter because your account has invoices that are over 10 days past due. \n \nIf yo...'),
    (13, '2023-04-26 05:56:17', 'Hosting - First Past Due Reminder DEMO01 - Total Past Due 5,080.00', 'receivables@hosting.com', 'demo@exchangedefender.com', 163909, 'demo@exchangedefender.com/Maildir/new/1682488577.792dc3099e7f532b4386.livearchive.exchangedefender.com.eml', 'Auto_Correspondence <receivables@hosting.com>', 1, 'Dear Accounts Payable,\n \nYou are receiving this email and the attached letter because your account has invoices that are over 10 days past due. \n \nIf yo...'),
    (14, '2023-04-26 05:56:17', 'Hosting - First Past Due Reminder DEMO01 - Total Past Due 5,080.00', 'receivables@hosting.com', 'demo@exchangedefender.com', 163909, 'demo@exchangedefender.com/Maildir/new/1682488577.792dc3099e7f532b4386.livearchive.exchangedefender.com.eml', 'Auto_Correspondence <receivables@hosting.com>', 1, 'Dear Accounts Payable,\n \nYou are receiving this email and the attached letter because your account has invoices that are over 10 days past due. \n \nIf yo...'),
    (15, '2024-01-18 15:05:23', 'Issue 211: Breaking Tetris', 'do-not-reply@stackoverflow.email', 'demo@exchangedefender.com', 43566, 'demo@exchangedefender.com/Maildir/new/Issue 211_ Breaking Tetris.eml', 'Stack Overflow <do-not-reply@stackoverflow.email>', 0, 'Robot butlers, rebutting Spock, and the JS hero that is Proxy ‌ ‌ ‌ ‌ ‌ \r\n <https://stackoverflow.com/> \r\n\r\n\r\n\r\n\r\n 17 January \r\n\r\n Welcome to ISSUE...'),
    (16, '2022-12-06 10:26:45', 'Hosting Invoice # ZIN553390 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139883, 'demo@exchangedefender.com/Maildir/new/1670322405.792dc309afadb51e3527.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN553390\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (17, '2022-12-06 10:26:45', 'Hosting Invoice # ZIN553390 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139883, 'demo@exchangedefender.com/Maildir/new/1670322405.792dc309afadb51e3527.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN553390\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (18, '2023-03-21 02:08:46', 'Hosting Invoice # ZIN575107 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135351, 'demo@exchangedefender.com/Maildir/new/1679364526.792dc30923cda7504170.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN575107\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (19, '2023-03-21 02:08:46', 'Hosting Invoice # ZIN575107 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135351, 'demo@exchangedefender.com/Maildir/new/1679364526.792dc30923cda7504170.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN575107\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (20, '2023-01-06 16:30:34', 'Hosting Invoice # ZIN559560 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139846, 'demo@exchangedefender.com/Maildir/new/1673022634.792dc309383126833763.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN559560\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (21, '2023-01-06 16:30:34', 'Hosting Invoice # ZIN559560 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139846, 'demo@exchangedefender.com/Maildir/new/1673022634.792dc309383126833763.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN559560\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (22, '2024-01-16 17:35:39', 'Take 60% off your next 2 orders', 'uber@uber.com', 'demo@exchangedefender.com', 135150, 'demo@exchangedefender.com/Maildir/new/Take 60% off your next 2 orders.eml', 'Uber Eats <uber@uber.com>', 0, 'See what’s new and save money, too .yahooHide{display:none!important}   We miss you! Get 60% off your next 2 restaurant orders. Order now\n Travis\n\nIt’s ...'),
    (23, '2023-06-06 09:35:13', 'Hosting Invoice # ZIN592013 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139734, 'demo@exchangedefender.com/Maildir/new/1686044113.792dc309d528ef7a4581.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN592013\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (24, '2023-06-06 09:35:13', 'Hosting Invoice # ZIN592013 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139734, 'demo@exchangedefender.com/Maildir/new/1686044113.792dc309d528ef7a4581.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN592013\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (25, '2024-01-12 13:00:22', 'Win $1,000, A Disturbed Flyaway And Sandals!', 'wjrr@e.iheart.com', 'demo@exchangedefender.com', 60653, 'demo@exchangedefender.com/Maildir/new/Win $1,000, A Disturbed Flyaway And Sandals!.eml', '101one WJRR <wjrr@e.iheart.com>', 0, 'We have a chance for you to score tickets to upcoming WJRR shows! ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ‌ ...'),
    (26, '2023-09-26 22:26:48', 'Hosting Invoice # ZIN612076 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135215, 'demo@exchangedefender.com/Maildir/new/1695767208.792dc3090a1dec334639.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN612076\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (27, '2023-09-26 22:26:48', 'Hosting Invoice # ZIN612076 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135215, 'demo@exchangedefender.com/Maildir/new/1695767208.792dc3090a1dec334639.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN612076\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (28, '2024-01-14 17:00:54', 'Y\'all have a wide range of experience!', 'hi@kevinpowell.co', 'demo@exchangedefender.com', 63045, 'demo@exchangedefender.com/Maildir/new/Y\'all have a wide range of experience!.eml', 'Kevin Powell <hi@kevinpowell.co>', 0, 'Very quickly, before we get into the meat of this week’s\r\nnewsletter, I am on Mastodon now.\r\n\r\nIt took me way to long to get around to it, but if you are o...'),
    (29, '2023-04-07 07:52:58', 'Hosting Invoice # ZIN579794 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 140000, 'demo@exchangedefender.com/Maildir/new/1680853978.792dc30909b1aa924366.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN579794\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (30, '2023-04-07 07:52:58', 'Hosting Invoice # ZIN579794 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 140000, 'demo@exchangedefender.com/Maildir/new/1680853978.792dc30909b1aa924366.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN579794\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (31, '2023-11-04 11:14:48', 'Hosting Invoice # ZIN622256 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139379, 'demo@exchangedefender.com/Maildir/new/1699096488.792dc309a5e749e35026.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN622256\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (32, '2023-11-04 11:14:48', 'Hosting Invoice # ZIN622256 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139379, 'demo@exchangedefender.com/Maildir/new/1699096488.792dc309a5e749e35026.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN622256\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (33, '2024-01-15 17:43:16', 'AT&T Wants Your Feedback', 'invites@csat.att-mail.com', 'demo@exchangedefender.com', 21940, 'demo@exchangedefender.com/Maildir/new/AT&T Wants Your Feedback.eml', 'AT&T Customer Research <invites@csat.att-mail.com>', 0, 'Dear AT&T Customer,\r\n\r\nAs a valued AT&T customer, your feedback is critical in helping us make\r\nimprovements that result in better solutions, services, and c...'),
    (34, '2023-10-05 07:29:35', 'Hosting Invoice # ZIN616224 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139233, 'demo@exchangedefender.com/Maildir/new/1696490975.792dc309bfbd70734818.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN616224\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (35, '2023-10-05 07:29:35', 'Hosting Invoice # ZIN616224 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 139233, 'demo@exchangedefender.com/Maildir/new/1696490975.792dc309bfbd70734818.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN616224\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (36, '2023-10-29 20:50:00', 'Hosting Invoice # ZIN618085 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135230, 'demo@exchangedefender.com/Maildir/new/1698612600.792dc309976a1c2b4863.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN618085\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (37, '2023-10-29 20:50:00', 'Hosting Invoice # ZIN618085 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 135230, 'demo@exchangedefender.com/Maildir/new/1698612600.792dc309976a1c2b4863.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN618085\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (38, '2023-02-04 14:34:55', 'Hosting Invoice # ZIN567218 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 140403, 'demo@exchangedefender.com/Maildir/new/1675521295.792dc3092b8f3bad3997.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN567218\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (39, '2023-02-04 14:34:55', 'Hosting Invoice # ZIN567218 / Customer # DEMO01', 'arinvoices@hosting.com', 'demo@exchangedefender.com', 140403, 'demo@exchangedefender.com/Maildir/new/1675521295.792dc3092b8f3bad3997.livearchive.exchangedefender.com.eml', 'AR Invoices <arinvoices@hosting.com>', 1, '\r\nPlease find attached invoice/credit # ZIN567218\r\n\r\n\r\nThis invoice has been sent from an unmonitored email account.\r\n\r\nPlease read this message carefully as...'),
    (40, '2024-01-12 21:36:49', 'It\'s time to evaluate your retirement savings', 'bankofamerica@emcom.bankofamerica.com', 'demo@exchangedefender.com', 44366, 'demo@exchangedefender.com/Maildir/new/It\'s time to evaluate your retirement savings.eml', 'Bank of America <bankofamerica@emcom.bankofamerica.com>', 0, '\r\n\r\n\r\n------ Bank of America ------\r\n\r\nWant to see the online version of this email from Bank of America?\r\nClick the link below:\r\nhttps://view.emcom.bankofam...');


DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;





/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;