/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`restapi` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `restapi`;

/*Table structure for table `sampledata` */

DROP TABLE IF EXISTS `sampledata`;

CREATE TABLE `sampledata` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `age` smallint(3) unsigned NOT NULL DEFAULT '0',
  `city` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sampledata` */

insert  into `sampledata`(`uid`,`name`,`age`,`city`,`country`) values 

(1,'Karl Auer',42,'Hamburg','Germany'),
(2,'Pavel Nemec',56,'Prag','Czech Republic'),
(3,'Paolo di Santo',27,'Rio de Janeiro','Brazil'),
(4,'Vittoria Ranieri',31,'Rome','Italy'),
(5,'Jenny Gump',27,'New York','USA');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
