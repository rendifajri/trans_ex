-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.27 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for trans_ex
CREATE DATABASE IF NOT EXISTS `trans_ex` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `trans_ex`;

-- Dumping structure for table trans_ex.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table trans_ex.customer: ~6 rows (approximately)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id`, `name`) VALUES
	(1, 'Rendi'),
	(2, 'Rizka'),
	(3, 'Adi'),
	(5, 'Yoyok'),
	(6, 'Budi'),
	(7, 'Lukman');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table trans_ex.item
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode` (`barcode`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table trans_ex.item: ~5 rows (approximately)
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` (`id`, `barcode`, `name`, `price`) VALUES
	(1, '76239878', 'Malboro Putih 20', 27000),
	(2, '8998989100120', 'GG Filter 12', 18000),
	(3, '089686010947', 'Indomie ', 2500),
	(4, '8992753031894', 'Indomilk Sachet Putih', 1500),
	(5, '8991002105485', 'Kapal Api Special Mix', 1250);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;

-- Dumping structure for table trans_ex.sales_order
CREATE TABLE IF NOT EXISTS `sales_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `note` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `sales_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table trans_ex.sales_order: ~3 rows (approximately)
/*!40000 ALTER TABLE `sales_order` DISABLE KEYS */;
INSERT INTO `sales_order` (`id`, `customer_id`, `code`, `date`, `paid`, `note`) VALUES
	(1, 3, 'SO001', '2019-01-01 02:02:00', 120000, ''),
	(2, 7, 'SO002', '2019-11-13 01:30:00', 200000, ''),
	(3, 6, 'SO003', '2019-04-25 19:33:00', 140000, '');
/*!40000 ALTER TABLE `sales_order` ENABLE KEYS */;

-- Dumping structure for table trans_ex.sales_order_detail
CREATE TABLE IF NOT EXISTS `sales_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` float NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sales_order_id` (`sales_order_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `sales_order_detail_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sales_order_detail_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table trans_ex.sales_order_detail: ~12 rows (approximately)
/*!40000 ALTER TABLE `sales_order_detail` DISABLE KEYS */;
INSERT INTO `sales_order_detail` (`id`, `sales_order_id`, `item_id`, `quantity`, `price`) VALUES
	(1, 2, 1, 2, 27000),
	(2, 2, 2, 3, 18000),
	(3, 1, 1, 3, 27000),
	(4, 1, 2, 1, 18000),
	(5, 2, 5, 4, 1250),
	(6, 1, 3, 5, 2500),
	(7, 1, 4, 3, 1500),
	(8, 3, 2, 2, 18000),
	(9, 3, 3, 1, 2500),
	(10, 3, 5, 4, 1250),
	(11, 3, 1, 3, 27000),
	(12, 3, 4, 4, 1500);
/*!40000 ALTER TABLE `sales_order_detail` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
