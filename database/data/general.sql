# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 0.0.0.0 (MariaDB-10.2.10+maria~jessie)
# Database: safetynet
# Generation Time: 2017-10-06 11:44:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table assets
# ------------------------------------------------------------

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;

TRUNCATE `assets`;

INSERT INTO `assets` (`id`, `name`, `file_name`, `mime_type`, `focus_point`, `disk`, `extension`, `path`, `url`, `size`, `width`, `height`, `created_at`, `updated_at`)
VALUES
	(1, 'source-logo', 'source-logo.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'source-logo.png', 'http://localhost/uploads/source-logo.png', 31901, NULL, NULL, '2018-02-02 16:53:39', '2018-02-02 16:53:39'),
	(2, 'nexo-logo', 'nexo-logo.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'nexo-logo.png', 'http://localhost/uploads/nexo-logo.png', 33369, NULL, NULL, '2018-02-20 18:44:15', '2018-02-20 18:44:15'),
	(3, 'aviation', 'aviation.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'aviation.png', 'http://localhost/uploads/aviation.png', 160690, NULL, NULL, '2018-02-20 18:52:36', '2018-02-20 18:52:36'),
	(4, 'defence', 'defence.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'defence.png', 'http://localhost/uploads/defence.png', 129050, NULL, NULL, '2018-02-20 18:52:37', '2018-02-20 18:52:37'),
	(5, 'marine', 'marine.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'marine.png', 'http://localhost/uploads/marine.png', 166171, NULL, NULL, '2018-02-20 18:52:38', '2018-02-20 18:52:38'),
	(6, 'offshore', 'offshore.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'offshore.png', 'http://localhost/uploads/offshore.png', 119142, NULL, NULL, '2018-02-20 18:52:38', '2018-02-20 18:52:38'),
	(7, 'source-header', 'source-header.png', 'image/png', '{\"percentageX\":\"50%\",\"percentageY\":\"50%\",\"focusX\":0,\"focusY\":0}', 'uploads', 'png', 'banner-images/source-header.png', 'http://localhost/uploads/banner-images/source-header.png', 400814, NULL, NULL, '2018-02-20 18:57:31', '2018-02-20 18:57:31');

/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
