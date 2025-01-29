/*
SQLyog Community v13.2.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - dlmsdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dlmsdb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `dlmsdb`;

/*Table structure for table `tbl_listing_ratings` */

DROP TABLE IF EXISTS `tbl_listing_ratings`;

CREATE TABLE `tbl_listing_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_listing_ratings` */

insert  into `tbl_listing_ratings`(`id`,`listing_id`,`user_id`,`rating_date`) values 
(10,2,15,'2025-01-05 23:48:55'),
(11,6,15,'2025-01-06 00:39:03'),
(12,6,15,'2025-01-25 15:01:43');

/*Table structure for table `tbl_reservations` */

DROP TABLE IF EXISTS `tbl_reservations`;

CREATE TABLE `tbl_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `listing_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `reservation_purpose` text NOT NULL,
  `date_submitted` datetime DEFAULT current_timestamp(),
  `reservation_status` int(11) DEFAULT 0 COMMENT '0 = pending , 1 = approved, 2 = rejected, 3 = cancelled',
  `reservation_remarks` text DEFAULT NULL,
  `action_by` int(11) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`reservation_date`,`reservation_time`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_reservations` */

insert  into `tbl_reservations`(`id`,`user_id`,`mobile_no`,`listing_id`,`reservation_date`,`reservation_time`,`reservation_purpose`,`date_submitted`,`reservation_status`,`reservation_remarks`,`action_by`,`action_date`) values 
(2,8,'09204696634',3,'2024-12-21','10:00:00','Table for 2 person, Lomi Special','2024-12-19 23:59:51',1,NULL,2,'2024-12-19 17:34:52'),
(4,7,'09686898534',11,'2024-12-23','14:00:00','5kg Clothes','2024-12-20 00:02:17',1,NULL,13,'2024-12-19 17:36:44'),
(5,9,'09999999999',9,'2024-12-20','15:05:00','Anti Rabbies','2024-12-20 00:03:08',0,NULL,NULL,NULL),
(6,8,'09204696634',3,'2024-12-20','03:35:00','test','2024-12-20 00:35:42',1,NULL,2,'2024-12-19 17:36:03'),
(7,7,'09686898534',12,'2024-12-25','14:37:00','test','2024-12-20 00:37:27',1,NULL,12,'2024-12-19 17:38:13'),
(8,7,'09686898534',12,'2024-12-26','03:37:00','test','2024-12-20 00:37:39',1,NULL,12,'2024-12-19 17:38:16'),
(9,7,'09686898534',12,'2024-12-27','05:37:00','test','2024-12-20 00:37:53',2,NULL,12,'2024-12-19 17:38:19'),
(10,15,'09060482092',2,'2024-12-28','15:00:00','papagupit po','2024-12-28 15:03:54',1,NULL,3,'2024-12-28 08:05:54'),
(11,15,'09060482092',6,'2025-01-06','13:00:00','kakain','2025-01-05 22:25:46',1,NULL,6,'2025-01-05 15:30:56'),
(12,15,'09060599592',11,'2025-01-30','08:00:00','mag papalaba po','2025-01-29 18:03:10',2,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent congue id dui non luctus. Vivamus sed fringilla mi. Nunc ullamcorper neque vitae lorem vestibulum mollis. Integer efficitur fringilla diam, vitae pretium diam finibus nec. Fusce euismod mauris vitae dolor hendrerit, id vulputate nibh vestibulum. In tincidunt, velit vel commodo mollis, ligula turpis consequat arcu, quis eleifend turpis ante a dolor. Curabitur mollis consectetur enim ut rutrum. Nullam non feugiat neque. Praesent non faucibus diam. Nullam sed risus pharetra, porttitor eros ac, finibus velit. Mauris bibendum neque at mollis venenatis.',13,'2025-01-29 11:37:57');

/*Table structure for table `tbladmin` */

DROP TABLE IF EXISTS `tbladmin`;

CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminName` varchar(50) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  `Business_name` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `isApproved_Business` int(11) DEFAULT 0,
  `usertype` int(11) DEFAULT 1 COMMENT '1- admin, 2=business_owner',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbladmin` */

insert  into `tbladmin`(`ID`,`AdminName`,`UserName`,`MobileNumber`,`Email`,`Password`,`AdminRegdate`,`Business_name`,`created_at`,`isApproved_Business`,`usertype`) values 
(1,'Admin','admin',8979555556,'adminuser@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2019-11-29 20:54:53',NULL,'2024-12-07 14:32:01',1,1),
(2,'Ytchy Lomi House','09999999999',9999999999,'ytchylomi@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-09 20:41:29','Ytchy Lomi House','2024-12-09 20:41:29',1,2),
(3,'','02222222222',2222222222,'eduardobarbers@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-11 13:05:04','Eduardo Barberia','2024-12-11 13:05:04',1,2),
(4,'','Mechanic Guru',1234567890,'Mg.autoworx@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-11 22:42:09','Mechanic Guru','2024-12-11 22:42:09',1,2),
(5,'','Lihim Cafe',12345678900,'lihimcafe@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-11 23:43:55','Lihim Cafe','2024-12-11 23:43:55',1,2),
(6,'','Kwatogs',12345678901,'kwatogssanvicente@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-11 23:51:54','Kwatogs','2024-12-11 23:51:54',1,2),
(7,'','Blair Wings',12345678902,'blairwingsprojectpremiumsauce@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-11 23:53:48','Blair Wings','2024-12-11 23:53:48',1,2),
(8,'','Sunnyside',9275219424,'sunnysidehotel2021@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-12 00:01:57','Sunnyside','2024-12-12 00:01:57',1,2),
(9,'','KJR AIRCON & REFRIGERATION SERVICES',9398802003,'kjrairconservices@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-19 23:14:46','KJR AIRCON & REFRIGERATION SERVICES','2024-12-19 23:14:46',1,2),
(10,'','SOUTHGATE VETERINARY CLINIC',9498025466,'southgatevetph@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-19 23:15:17','SOUTHGATE VETERINARY CLINIC','2024-12-19 23:15:17',1,2),
(11,'','BARK VILLAGE GROOMING AND VETERINARY SERVICES',9175052925,'barkvillageservices@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-19 23:15:50','BARK VILLAGE GROOMING AND VETERINARY SERVICES','2024-12-19 23:15:50',0,2),
(12,'','FLOWER LINK',9121451352,'flowerlinkph@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-19 23:38:03','FLOWER LINK','2024-12-19 23:38:03',1,2),
(13,'','KALYE LAUNDRY',9124152362,'kalyelaundry@gmail.com','a3fe41c36274fa31157d64bd152c8eeb','2024-12-19 23:39:55','KALYE LAUNDRY','2024-12-19 23:39:55',1,2);

/*Table structure for table `tblcategory` */

DROP TABLE IF EXISTS `tblcategory`;

CREATE TABLE `tblcategory` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Category` varchar(100) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `Category` (`Category`),
  KEY `CreationDate` (`CreationDate`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcategory` */

insert  into `tblcategory`(`ID`,`Category`,`CreationDate`) values 
(1,'Restaurant','2019-11-29 08:42:22'),
(2,'Hotel','2019-11-30 13:43:18'),
(6,'Car Service','2019-11-30 13:43:58'),
(21,'Laundry','2019-11-30 13:47:29'),
(23,'Flower Shop','2019-11-30 13:47:55'),
(24,'Tailor','2019-11-30 13:48:10'),
(25,'Other','2019-11-30 13:48:22'),
(29,'Resort','2024-12-19 23:27:35'),
(30,'Clinic','2024-12-19 23:27:45');

/*Table structure for table `tblcontact` */

DROP TABLE IF EXISTS `tblcontact`;

CREATE TABLE `tblcontact` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Message` mediumtext DEFAULT NULL,
  `EnquiryDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsRead` int(5) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblcontact` */

insert  into `tblcontact`(`ID`,`Name`,`Email`,`Message`,`EnquiryDate`,`IsRead`,`isDeleted`) values 
(1,'Kiran','kran@gmail.com','cost of volvo place pritampura to dwarka','2021-07-05 15:26:24',1,1),
(2,'Sarita Pandey','sar@gmail.com','huiyuihhjjkhkjvhknv iyi tuyvuoiup','2021-07-09 20:48:40',1,0),
(3,'Test','test@gmail.com','Want to know price of forest cake','2021-07-16 20:51:06',1,0),
(4,'Shanu','shanu@gmail.com','hkjhkjhk','2021-07-17 15:32:14',1,0),
(5,'Taanu Sharma','tannu@gmail.com','ytjhdjguqwgyugjhbjdsuy\r\nkjhjkwhkd\r\nljkkljwq\r\nmlkjol ','2021-07-28 20:09:22',1,0),
(6,'Harish Kumar','hari@gmail.com','rytweiuyeiouoipoipo[po\r\njknkjlkdsflkjflkdjslk;lsdkf;l\r\nn,mn,ncxm.,m.m.,.','2021-08-01 00:34:16',NULL,0),
(7,'test','test@gmail.com','Test','2021-08-26 00:56:19',1,0),
(8,'jkhjk','kjhjk@abc.com','kjhkj','2021-10-01 18:13:11',NULL,0),
(9,'Anuj','ak@gmail.com','This is for test.','2021-10-22 01:55:51',NULL,0);

/*Table structure for table `tbllisting` */

DROP TABLE IF EXISTS `tbllisting`;

CREATE TABLE `tbllisting` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `UserID` int(10) DEFAULT NULL,
  `ListingTitle` varchar(200) DEFAULT NULL,
  `Keyword` varchar(200) DEFAULT NULL,
  `Category` int(10) DEFAULT NULL,
  `Website` varchar(200) DEFAULT NULL,
  `Address` mediumtext DEFAULT NULL,
  `Address_location` text DEFAULT NULL,
  `TemporaryAddress` mediumtext DEFAULT NULL,
  `City` varchar(200) DEFAULT NULL,
  `State` varchar(200) DEFAULT NULL,
  `Country` varchar(200) DEFAULT NULL,
  `Zipcode` int(10) DEFAULT NULL,
  `OwnerName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `CompanyWebsite` varchar(200) DEFAULT NULL,
  `OwnerDesignation` varchar(200) DEFAULT NULL,
  `Company` varchar(200) DEFAULT NULL,
  `FacebookLink` varchar(200) DEFAULT NULL,
  `TweeterLink` varchar(200) DEFAULT NULL,
  `Googlepluslink` varchar(200) DEFAULT NULL,
  `Linkedinlink` varchar(200) DEFAULT NULL,
  `Description` longtext DEFAULT NULL,
  `Logo` text DEFAULT NULL,
  `ListingDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `n_name_of_business` varchar(60) DEFAULT NULL,
  `n_name_of_business_owner` varchar(60) DEFAULT NULL,
  `n_name_of_business_address` text DEFAULT NULL,
  `n_line_of_business` varchar(60) DEFAULT NULL,
  `n_email` varchar(50) DEFAULT NULL,
  `n_phone_number` varchar(25) DEFAULT NULL,
  `isDeleted` int(11) DEFAULT 0,
  `longitude` varchar(200) DEFAULT NULL,
  `latitude` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `userid` (`UserID`),
  KEY `catid` (`Category`),
  CONSTRAINT `catid` FOREIGN KEY (`Category`) REFERENCES `tblcategory` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tbllisting_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbladmin` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tbllisting` */

insert  into `tbllisting`(`ID`,`UserID`,`ListingTitle`,`Keyword`,`Category`,`Website`,`Address`,`Address_location`,`TemporaryAddress`,`City`,`State`,`Country`,`Zipcode`,`OwnerName`,`Email`,`Phone`,`CompanyWebsite`,`OwnerDesignation`,`Company`,`FacebookLink`,`TweeterLink`,`Googlepluslink`,`Linkedinlink`,`Description`,`Logo`,`ListingDate`,`n_name_of_business`,`n_name_of_business_owner`,`n_name_of_business_address`,`n_line_of_business`,`n_email`,`n_phone_number`,`isDeleted`,`longitude`,`latitude`) values 
(2,3,' Eduardo\'s Barbershop','',24,'','PH1 2ND FLOOR LIFE STYLE STRIP, MAHARLIKA HIGHWAY, BARANGAY SAN ANTONIO, STO. TOMAS, BATANGAS','PH1 2ND FLOOR LIFE STYLE STRIP, MAHARLIKA HIGHWAY, BARANGAY SAN ANTONIO, STO. TOMAS, BATANGAS','','STO. TOMAS','SAN ANTONIO','',4234,'Eduardo','eduardosbarbers@gmail.com','0962346846','','','','','','','','Eduardo\'s Barbershop provides top-notch grooming services for men. From classic haircuts to modern styles, we ensure you leave feeling confident and sharp.','../images/6764c6b443670_416886307_386512267077921_7735419848573831071_n.jpg,../images/6764c6b44393c_467302073_582817450780734_2114197719452580967_n.jpg,../images/6764c6b447f22_251671380_130857862643364_3988939005741098980_n.jpg,../images/6764c6b4480e1_2024-01-28.jpg','2025-01-25 15:06:58',NULL,NULL,NULL,'Barbershop',NULL,'0962346846',0,NULL,NULL),
(3,2,'Ytchy Lomi House','',1,'','BARANGAY SAN VICENTE, CITY OF STO TOMAS, BATANGAS','BARANGAY SAN VICENTE, CITY OF STO TOMAS, BATANGAS','','STO. TOMAS','SAN VICENTE','',4234,'RICHARD V. ARGUELLES','ytchylomihouse@gmail.com','9186911486','','','','','','','','ARAT NA! \r\nAno pang hinihintay mo? Halina’t tikman ang masarap na LOMI at PANSIT. ','../images/6764c7c28e6a4_467471397_1825798821559219_2755864144702614872_n.jpg,../images/6764c7c28e847_467195695_1825798828225885_5509574079026946545_n.jpg,../images/6764c7c28e919_461523698_1785224895616612_9180451737540987876_n.jpg,../images/6764c7c28ea59_464708131_1809033516569083_4938249710390481312_n.jpg','2025-01-29 08:48:04',NULL,NULL,NULL,'Lomi House',NULL,'9186911486',0,NULL,NULL),
(4,4,'Mechanic Guru - MG Autoworx Automotive Care Services','',6,'','102 MAHARLIKA HWAY, BARANGAY SAN VICENTE, STO. TOMAS, BATANGAS','102 MAHARLIKA HWAY, BARANGAY SAN VICENTE, STO. TOMAS, BATANGAS','','STO. TOMAS','SAN VICENTE','',4234,'PAULA KATRINA P. TORRES','Mg.autoworx@gmail.com','9171482381','','','','','','','','Mechanic Guru MG Autoworx offers expert automotive care services to keep your vehicles running at their best. From routine maintenance to complex repairs, trust us for quality and reliability every time.','../images/6764c856b2468_469895724_1000842798743119_7294348878233184704_n.jpg,../images/6764c856b261c_469929082_1000842762076456_1769643373729116743_n.jpg,../images/6764c856b2715_469854960_1000842022076530_3776910549927803350_n.jpg,../images/6764c856b2808_357707285_653548380139231_8292623313677266117_n.jpg','2024-12-20 09:28:54',NULL,NULL,NULL,'Automotive',NULL,'9171482381',0,NULL,NULL),
(5,5,'Lihim Cafe','',1,'','BARANGAY SAN FRANCISCO, STO. TOMAS, BATANGAS','BARANGAY SAN FRANCISCO, STO. TOMAS, BATANGAS','','STO. TOMAS','SAN FRANCISCO','',4234,'AIKON FOOD CORPORATION','lihimcafe@gmail.com','9171440533','','','','','','','','Treat yourself to our delicious rice bowls. Our chicken florentine roulade is filled with savory spinach and cheese and served over warm rice for a comforting meal. Dine with us this weekend! ','../images/6764c834da308_467752393_557252157063461_9033463393465381805_n.jpg,../images/6764c834da4e9_462562490_1129137132209853_4620127663048907643_n.jpg,../images/6764c834da605_462581105_1119394563213764_577600139173642560_n.jpg,../images/6764c834da7eb_462581807_599842579365810_3914970856546237101_n.jpg','2024-12-20 09:28:20',NULL,NULL,NULL,'Cafe',NULL,'9171440533',0,NULL,NULL),
(6,6,'Kwatogs Lomi House','',1,'','374 San Vicente, Santo Tomas, Batngas, Santo Tomas, Philippines','374 San Vicente, Santo Tomas, Batngas, Santo Tomas, Philippines','','STO. TOMAS','SAN VICENTE','',4234,'Ramos','kwatogssanvicente@gmail.com','0953937253','','','','','','','','Kwatog\'s Restaurant in San Vicente, Sto. Tomas, Batangas, is a cozy and inviting dining spot known for its mouthwatering Filipino dishes and warm hospitality. Perfectly blending traditional flavors with a modern touch, Kwatog’s offers a menu featuring classic favorites like sizzling sisig, bulalo, and fresh seafood, alongside innovative takes on local cuisine.','../images/6764c814131ce_462650112_602870255601886_6755516078514743255_n.png,../images/6764c81413321_462653187_2854707071371722_2479098062595978594_n.png,../images/6764c81413422_465674750_1657144958234650_9171770920114801278_n.png','2024-12-20 09:27:48',NULL,NULL,NULL,'Lomi House',NULL,'0953937253',0,NULL,NULL),
(7,7,'BLAIR WINGS PROJECT FOOD HUB','',1,'','SUMARU BLDG., BARANGAY IV, STO TOMAS, BATANGAS','SUMARU BLDG., BARANGAY IV, STO TOMAS, BATANGAS','','STO. TOMAS','POBLACION IV','',4234,'JENNIBETH C. SANTOS','blairwingsprojectpremiumsauce@gmail.com','9554870094','','','','','','','','Blair\'s Kitchen, located in Sto. Tomas, Batangas, is a hidden gem celebrated for its modern take on comfort food. Known for its diverse menu that fuses international flavors with Filipino favorites, Blair\'s Kitchen offers dishes that cater to every palate, from hearty pasta and savory steaks to indulgent desserts and refreshing beverages.','../images/6764c7e4b607a_457792542_862671675961675_2581267764940369208_n.jpg,../images/6764c7e4b62c6_459650556_870765735152269_8552221935447221841_n.jpg,../images/6764c7e4b641c_460151514_870765828485593_6866465764880527331_n.jpg,../images/6764c7e4b654e_459866512_870764981819011_2350837415200007445_n.jpg,../images/6764c7e4b66ac_454475631_845341914361318_2313416897082639501_n.jpg,../images/6764c7e4b6848_271874710_455910216184993_823058413042089823_n.jpg','2024-12-20 09:27:00',NULL,NULL,NULL,'Food Hub',NULL,'9554870094',0,NULL,NULL),
(8,8,'SUNNYSIDE HOTEL','',2,'','SUNNYSIDE HOTEL SAMPALOCAN ROAD, BARANGAY SAN MIGUEL, STO TOMAS, BATANGAS','SUNNYSIDE HOTEL SAMPALOCAN ROAD, BARANGAY SAN MIGUEL, STO TOMAS, BATANGAS','','STO. TOMAS','SAN MIGUEL','',4234,'MA. DARLENE D. LAURENA','sunnysidehotel2021@gmail.com','0927521942','','','','','','','','Sunnyside Hotel in San Miguel, Sto. Tomas, Batangas, offers a cozy and welcoming stay for travelers seeking comfort and convenience. Located near the heart of the city, it provides easy access to local attractions and amenities. With its modern facilities, friendly service, and relaxing ambiance, Sunnyside Hotel ensures a memorable and enjoyable experience for every guest.','../images/6764c649a264b_241351944_125615236486901_3061795438367922969_n.jpg,../images/6764c649a296d_241136811_123510226697402_865075206863457698_n.jpg,../images/6764c649a2abd_440036262_1340127673499026_5122280091290866568_n.jpg,../images/6764c649a53d5_462651996_868157912151923_3957593402086541240_n.png,../images/6764c649a5658_465886079_559547000367816_6440168953129702507_n.png','2025-01-29 08:48:05',NULL,NULL,NULL,'Hotel',NULL,'0927521942',0,NULL,NULL),
(9,10,'SOUTHGATE VETERINARY CLINIC','',30,'','SIERRA MAKILING COMMERCIAL COMPLEX MAHARLIKA HI-WAY, BARANGAY SAN ANTONIO, STO. TOMAS, BATANGAS','SIERRA MAKILING COMMERCIAL COMPLEX MAHARLIKA HI-WAY, BARANGAY SAN ANTONIO, STO. TOMAS, BATANGAS','','STO. TOMAS','SAN ANTONIO','',4234,'BEVERLY F. BALUME','southgatevetph@gmail.com','9498025466','','','','','','','','Located in Sto. Tomas, Batangas, Southgate Veterinary Clinic offers expert care for pets, including:\r\n•	Veterinary consultations and diagnostics\r\n•	Vaccination and deworming services\r\n•	Pet surgery and emergency care\r\n•	Preventive healthcare and wellness programs\r\n•	Grooming and pet hygiene services\r\n','../images/3e82691da5aeb015927af6d5abf8b7701734622154.jpg','2024-12-19 23:29:14',NULL,NULL,NULL,'Clinic',NULL,'9498025466',0,NULL,NULL),
(10,9,'KJR AIRCON & REFRIGERATION SERVICES','',25,'','BARANGAY SAN PABLO, STO. TOMAS, BATANGAS','BARANGAY SAN PABLO, STO. TOMAS, BATANGAS','','STO. TOMAS','SAN PABLO','',4234,'DONALD M. GELING','kjrariconservices@gmail.com','9398802003','','','','','','','','Located in San Pablo, Sto. Tomas, Batangas, KJR offers a wide range of services, including:\r\n•	Aircon installation, cleaning, and repair\r\n•	Refrigeration system repair and maintenance\r\n•	Troubleshooting for residential and commercial cooling systems\r\n•	Freon charging and system reconditioning\r\nProviding quality and reliable solutions tailored to your cooling needs.','../images/7fdc1a630c238af0815181f9faa190f51734622349.jpg','2024-12-19 23:32:29',NULL,NULL,NULL,'Aircon Services',NULL,'9398802003',0,NULL,NULL),
(11,13,'KALYE LAUNDRY','',21,'','#098 A. Bonifacio Street, Poblacion 2, Santo Tomas, Philippines','#098 A. Bonifacio Street, Poblacion 2, Santo Tomas, Philippines','','STO. TOMAS','POBLACION II','',4234,'JEROME C. SY','kalyelaundry@gmail.com','0912423523','','','','','','','','Kalye Laundry is a laundromat located at 098 A. Bonifacio Street, Poblacion 2, Santo Tomas, Batangas, Philippines.  Situated near the Polytechnic University of the Philippines (PUP) Santo Tomas campus, it offers convenient and affordable laundry services to the local community.  The laundromat is open daily, with operating hours from 7:00 AM to 10:00 PM on weekdays and extended hours until 11:00 PM on weekends.  Kalye Laundry emphasizes eco-friendly practices, ensuring that customers\' laundry needs are met with minimal environmental impact. ','../images/1e65b62d6b63f406a0e7cc12b541fddc1734622935.png','2025-01-29 20:16:18',NULL,NULL,NULL,'Laundry Shop',NULL,'0912423523',0,NULL,NULL),
(12,12,'FLOWER LINK','',23,'','PALENGKE-PALENGKE BARANGAY II, STO. TOMAS, BATANGAS','PALENGKE-PALENGKE BARANGAY II, STO. TOMAS, BATANGAS','','STO. TOMAS','POBLACION II','',4234,'JOANA R. LEGADA','flowerlinkph@gmail.com','9156326526','','','','','','','','Flower Link in Santo Tomas, Batangas, offers beautifully handcrafted flower arrangements for all occasions, using fresh blooms to create meaningful connections. They provide free delivery to Batangas and Laguna and are located at Santo Tomas Public Market.','../images/6764406488648_262906611_1320302515104458_5100773508952791257_n.jpg','2025-01-29 20:22:56',NULL,NULL,NULL,'Flower',NULL,'9156326526',0,'120.9716736764056','14.71398760755681');

/*Table structure for table `tblpage` */

DROP TABLE IF EXISTS `tblpage`;

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `PageType` varchar(50) DEFAULT NULL,
  `PageTitle` varchar(200) DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblpage` */

insert  into `tblpage`(`ID`,`PageType`,`PageTitle`,`PageDescription`,`Email`,`MobileNumber`,`UpdationDate`) values 
(1,'aboutus','About Us','<span style=\"font-weight: bold; color: rgb(106, 106, 106); font-family: arial, sans-serif; font-size: 14px;\">DLMS is local</span><span style=\"color: rgb(84, 84, 84); font-family: arial, sans-serif; font-size: 14px;\">&nbsp;search is the use of specialized Internet&nbsp;</span><span style=\"font-weight: bold; color: rgb(106, 106, 106); font-family: arial, sans-serif; font-size: 14px;\">search engines</span><span style=\"color: rgb(84, 84, 84); font-family: arial, sans-serif; font-size: 14px;\">&nbsp;that allow users to submit geographically constrained searches against a structured database of&nbsp;</span><span style=\"font-weight: bold; color: rgb(106, 106, 106); font-family: arial, sans-serif; font-size: 14px;\">local business.</span><div><br></div>',NULL,NULL,'2021-10-22 01:55:10'),
(2,'contactus','Contact Us','Poblacion IV, Sto. Tomas City, Batangas','tomasiknows@gmail.com',9454782879,'2024-12-19 23:03:48');

/*Table structure for table `tblreview` */

DROP TABLE IF EXISTS `tblreview`;

CREATE TABLE `tblreview` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ListingID` int(10) DEFAULT NULL,
  `ReviewTitle` varchar(200) DEFAULT NULL,
  `Review` mediumtext DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `DateofReview` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Remark` varchar(200) DEFAULT NULL,
  `Status` varchar(200) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `ListingID` (`ListingID`),
  CONSTRAINT `listingid` FOREIGN KEY (`ListingID`) REFERENCES `tbllisting` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tblreview` */

insert  into `tblreview`(`ID`,`ListingID`,`ReviewTitle`,`Review`,`Name`,`DateofReview`,`Remark`,`Status`,`UpdationDate`) values 
(1,12,'Feedback Test','the flowers are fresh and cute designs','bryan','2024-12-20 00:39:15',NULL,'Review Accept','2024-12-20 00:39:15'),
(2,2,'Ito ang title','ang lamig','mori tech','2024-12-28 15:06:40',NULL,'Review Accept','2024-12-28 15:06:40');

/*Table structure for table `tbluser` */

DROP TABLE IF EXISTS `tbluser`;

CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `FullName` varchar(200) DEFAULT NULL,
  `MobileNumber` varchar(15) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `isDeleted` int(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `tbluser` */

insert  into `tbluser`(`ID`,`FullName`,`MobileNumber`,`Password`,`RegDate`,`isDeleted`) values 
(7,'Bryan','09686898534','f925916e2754e5e03f75dd58a5733251','2024-12-10 10:14:01',0),
(8,'Scylla','09204696634','f925916e2754e5e03f75dd58a5733251','2024-12-11 13:03:30',0),
(9,'henry','09999999999','f925916e2754e5e03f75dd58a5733251','2024-12-11 21:10:28',0),
(10,'jhonby','09999888877','f925916e2754e5e03f75dd58a5733251','2024-12-20 00:14:07',0),
(11,'jonhlex','09999888777','f925916e2754e5e03f75dd58a5733251','2024-12-20 00:14:31',0),
(12,'kisha','09998887777','f925916e2754e5e03f75dd58a5733251','2024-12-20 00:14:58',0),
(14,'kisha','09124125124','f925916e2754e5e03f75dd58a5733251','2024-12-20 08:29:51',0),
(15,'Mori Tech','09060599592','a3fe41c36274fa31157d64bd152c8eeb','2024-12-21 14:15:21',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
