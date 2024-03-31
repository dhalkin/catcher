-- MySQL dump 10.13  Distrib 5.7.38, for osx10.16 (x86_64)
--
-- Host: 127.0.0.1    Database: catcher
-- ------------------------------------------------------
-- Server version	8.0.12

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `symbols`
--

USE catcher;

DROP TABLE IF EXISTS `symbols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `symbols` (
  `symbol` varchar(12) NOT NULL,
  UNIQUE KEY `index_name` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `symbols`
--

LOCK TABLES `symbols` WRITE;
/*!40000 ALTER TABLE `symbols` DISABLE KEYS */;
INSERT INTO `symbols` VALUES ('1INCH'),('AAVE'),('ACA'),('ACH'),('ACM'),('ADA'),('ADX'),('AGLD'),('AION'),('AKRO'),('ALCX'),('ALGO'),('ALICE'),('ALPACA'),('ALPHA'),('ALPINE'),('AMP'),('ANC'),('ANKR'),('ANT'),('APE'),('API3'),('APT'),('AR'),('ARDR'),('ARPA'),('ASR'),('ASTR'),('ATA'),('ATM'),('ATOM'),('AUCTION'),('AUD'),('AUDIO'),('AUTO'),('AVA'),('AVAX'),('AXS'),('BADGER'),('BAKE'),('BAL'),('BAND'),('BAR'),('BAT'),('BCH'),('BEAM'),('BEL'),('BETA'),('BICO'),('BIFI'),('BLZ'),('BNB'),('BNT'),('BNX'),('BOND'),('BSW'),('BTC'),('BTCST'),('BTS'),('BTTC'),('BURGER'),('BUSD'),('C98'),('CAKE'),('CELO'),('CELR'),('CFX'),('CHESS'),('CHR'),('CHZ'),('CITY'),('CKB'),('CLV'),('COCOS'),('COMP'),('COS'),('COTI'),('CRV'),('CTK'),('CTSI'),('CTXC'),('CVC'),('CVP'),('CVX'),('DAR'),('DASH'),('DATA'),('DCR'),('DEGO'),('DENT'),('DEXE'),('DF'),('DGB'),('DIA'),('DOCK'),('DODO'),('DOGE'),('DOT'),('DREP'),('DUSK'),('DYDX'),('EGLD'),('ELF'),('ENJ'),('ENS'),('EOS'),('EPX'),('ERN'),('ETC'),('ETH'),('EUR'),('FARM'),('FET'),('FIDA'),('FIL'),('FIO'),('FIRO'),('FIS'),('FLM'),('FLOW'),('FLUX'),('FOR'),('FORTH'),('FRONT'),('FTM'),('FUN'),('FXS'),('GAL'),('GALA'),('GBP'),('GHST'),('GLMR'),('GMT'),('GMX'),('GNO'),('GRT'),('GTC'),('GTO'),('HARD'),('HBAR'),('HFT'),('HIGH'),('HIVE'),('HOT'),('ICP'),('ICX'),('IDEX'),('ILV'),('IMX'),('INJ'),('IOST'),('IOTA'),('IOTX'),('IRIS'),('JASMY'),('JOE'),('JST'),('JUV'),('KAVA'),('KDA'),('KEY'),('KLAY'),('KMD'),('KNC'),('KP3R'),('KSM'),('LAZIO'),('LDO'),('LEVER'),('LINA'),('LINK'),('LIT'),('LOKA'),('LPT'),('LRC'),('LSK'),('LTC'),('LTO'),('LUNA'),('LUNC'),('MANA'),('MASK'),('MATIC'),('MBL'),('MBOX'),('MC'),('MDT'),('MDX'),('MFT'),('MINA'),('MIR'),('MITH'),('MKR'),('MLN'),('MOB'),('MOVR'),('MTL'),('MULTI'),('NEAR'),('NEBL'),('NEO'),('NEXO'),('NKN'),('NMR'),('NULS'),('OCEAN'),('OG'),('OGN'),('OM'),('OMG'),('ONE'),('ONG'),('ONT'),('OOKI'),('OP'),('ORN'),('OSMO'),('OXT'),('PAXG'),('PEOPLE'),('PERL'),('PERP'),('PHA'),('PHB'),('PLA'),('PNT'),('POLS'),('POLYX'),('POND'),('PORTO'),('POWR'),('PSG'),('PUNDIX'),('PYR'),('QI'),('QNT'),('QTUM'),('QUICK'),('RAD'),('RARE'),('RAY'),('REEF'),('REI'),('REN'),('REP'),('REQ'),('RIF'),('RLC'),('RNDR'),('ROSE'),('RSR'),('RUNE'),('RVN'),('SAND'),('SANTOS'),('SC'),('SCRT'),('SFP'),('SHIB'),('SKL'),('SLP'),('SNX'),('SOL'),('SPELL'),('SRM'),('STEEM'),('STG'),('STMX'),('STORJ'),('STPT'),('STRAX'),('STX'),('SUN'),('SUPER'),('SUSHI'),('SXP'),('SYS'),('T'),('TFUEL'),('THETA'),('TKO'),('TLM'),('TOMO'),('TORN'),('TRB'),('TRIBE'),('TROY'),('TRU'),('TRX'),('TVK'),('TWT'),('UMA'),('UNFI'),('UNI'),('UTK'),('VET'),('VGX'),('VIDT'),('VITE'),('VOXEL'),('VTHO'),('WAN'),('WAVES'),('WAXP'),('WIN'),('WING'),('WNXM'),('WOO'),('WRX'),('WTC'),('XEC'),('XEM'),('XLM'),('XMR'),('XNO'),('XRP'),('XTZ'),('XVG'),('XVS'),('YFI'),('YFII'),('YGG'),('ZEC'),('ZEN'),('ZIL'),('ZRX');
/*!40000 ALTER TABLE `symbols` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telegraph_bots`
--

DROP TABLE IF EXISTS `telegraph_bots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telegraph_bots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telegraph_bots_token_unique` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telegraph_bots`
--

LOCK TABLES `telegraph_bots` WRITE;
/*!40000 ALTER TABLE `telegraph_bots` DISABLE KEYS */;
INSERT INTO `telegraph_bots` VALUES (2,'5951715648:AAF8QL1cs-0EKpKO8ufOR3OeyPDh2YbPi4E','pBot','2022-12-04 19:52:59','2022-12-04 19:52:59');
/*!40000 ALTER TABLE `telegraph_bots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telegraph_chats`
--

DROP TABLE IF EXISTS `telegraph_chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telegraph_chats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegraph_bot_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telegraph_chats_chat_id_telegraph_bot_id_unique` (`chat_id`,`telegraph_bot_id`),
  KEY `telegraph_chats_telegraph_bot_id_foreign` (`telegraph_bot_id`),
  CONSTRAINT `telegraph_chats_telegraph_bot_id_foreign` FOREIGN KEY (`telegraph_bot_id`) REFERENCES `telegraph_bots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telegraph_chats`
--

LOCK TABLES `telegraph_chats` WRITE;
/*!40000 ALTER TABLE `telegraph_chats` DISABLE KEYS */;
INSERT INTO `telegraph_chats` VALUES (1,'73097902','Dmitry & pBot',2,'2022-12-04 20:19:45','2022-12-04 20:19:45');
/*!40000 ALTER TABLE `telegraph_chats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-06  8:48:26
