-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: chedraui
-- ------------------------------------------------------
-- Server version	8.0.31

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `token_password` varchar(40) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `password_request` tinyint NOT NULL DEFAULT '0',
  `activo` tinyint NOT NULL,
  `fecha_alta` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','$2y$10$ezUpHMA8s1KYdR6Bt8T9YOBNtnz/WxNjKrvEMNINXFxMK44oAjX/G','Administrador','chavezfelipelouis@gmail.com',NULL,0,1,'2023-09-27 16:32:01'),(3,'admin1','$2y$10$LW4vp9cYETI7doGQiCARNuZRF3GALVs37DPtmsfXMhpdYqSzhByVq','Administrador1','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-02 17:46:37');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre_archivo` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `ubicacion` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `usuario` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci,
  `estado` enum('exitoso','fallido','en_progreso') COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `tamano_archivo` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `comentarios` text COLLATE utf8mb4_spanish_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` VALUES (1,'2024-05-13 15:50:50','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','Respaldo prueba 1'),(2,'2024-05-13 15:56:30','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','PRUEBA 2'),(3,'2024-05-13 16:02:59','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','PRUEBA 3'),(4,'2024-05-13 16:15:39','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','PRUEBA 3'),(5,'2024-05-13 16:17:38','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','PRUEBA 3'),(6,'2024-05-13 16:42:45','ResBD-DM1305.sql','../respaldos/',NULL,'admin, backups, categorias, clientes, compra, compra_personal, compra_personal_productos, configuracion, detalle_compra, notificacion, notificacion_producto, personal, productos, proveedores, transaccion_prov, usuarios',NULL,'2','PRUEBA 4'),(7,'2024-05-13 17:14:13','chedraui_20240513_170545.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 5'),(8,'2024-05-13 17:22:06','chedraui_20240513_172201.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 6'),(9,'2024-05-13 17:25:02','chedraui_20240513_172457.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 7'),(10,'2024-05-13 17:31:06','chedraui_20240513_173101.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 8'),(11,'2024-05-13 17:32:27','chedraui_20240513_173224.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 9'),(12,'2024-05-13 17:33:23','chedraui_20240513_173319.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 11'),(13,'2024-05-13 17:33:37','chedraui_20240513_173333.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 12\r\n'),(14,'2024-05-13 17:35:21','chedraui_20240513_173517.sql','../respaldos/',NULL,'todas las tablas de la base de datos',NULL,NULL,'PRueba 13');
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `descripcion` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Bebidas',1,'Disfruta de una variedad refrescante y deliciosa de bebidas para todos los gustos. Desde refrescos y jugos hasta bebidas energéticas y aguas, encuentra la bebida perfecta para cada ocasión.'),(2,'Comida',1,'Descubre una amplia selección de alimentos frescos, enlatados, congelados y más. Explora ingredientes de alta calidad y productos listos para consumir que harán que cada comida sea deliciosa, satisfactoria e inolvidable.'),(5,'Limpieza',1,'Mantén tu hogar impecable con nuestra gama de productos de limpieza. Desde detergentes y desinfectantes hasta utensilios de limpieza, tenemos todo lo que necesitas para crear un ambiente limpio y saludable.'),(29,'Ropa para caballero',1,'Encuentra moda masculina que se adapte a tu estilo. Desde ropa casual hasta elegante, descubre una amplia gama de prendas diseñadas para realzar la elegancia y comodidad en cada ocasión.'),(28,'Ropa para Dama',1,'Explora las últimas tendencias en moda femenina. Desde vestidos y blusas hasta jeans y accesorios, nuestra colección de ropa para damas te ofrece opciones para cada estilo y ocasión.'),(30,'Electronica',1,'Sumérgete en el mundo de la tecnología con nuestra selección de productos electrónicos. Desde dispositivos móviles y computadoras hasta gadgets innovadores, encuentra la última tecnología para mejorar tu vida diaria.'),(31,'Sin categoria',0,'a'),(32,'Higiene',1,'Cuida de tu bienestar con nuestra línea de productos de higiene personal. Desde productos para el cuidado del cabello y cuidado bucal hasta productos de cuidado de la piel, te ofrecemos opciones para sentirte fresco y saludable.');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(80) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(80) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `dni` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estatus` tinyint NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modifica` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Luois Felipe','Chavez','chavezfelipelouis@gmail.com','2321544212','Boulevard Alfinio Flores Beltrán, 93603 Martínez de la Torre, VER, México','2471389',1,'2023-12-10 00:00:00',NULL,NULL),(5,'Luois','Alvarado','ppgbzx@gmail.com','2321420392','Calle Salvador Allende, 93600 Martínez de la Torre, VER, México','34234123',1,'0000-00-00 00:00:00',NULL,NULL),(6,'Pedro','Picapiedra','chavezfelipelouis@outlook.com','2324396728','','2471389',0,'2024-05-02 12:32:50',NULL,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_transaccion` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_cliente` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
INSERT INTO `compra` VALUES (1,'18Y00142N2375881S','2023-09-18 21:23:36','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',1561.31),(2,'5MP226427F379853X','2023-09-18 23:18:52','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',436.50),(3,'3VS89467FC546123T','2023-09-18 23:24:54','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',694.49),(4,'2MY949428D397880E','2023-09-18 23:30:28','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',561.72),(5,'9X852364TF472753C','2023-09-18 23:32:27','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',447.31),(6,'56F66274TK464962W','2023-09-18 23:34:41','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',785.03),(7,'20L923310G319894B','2023-09-18 23:47:53','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',1125.11),(8,'6VC87953FA024553S','2023-09-19 01:34:13','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',622.11),(9,'5CA975272B809360V','2023-09-19 12:53:58','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',664.19),(10,'09W78789GP638521G','2023-09-19 14:16:04','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',1414.81),(11,'38S663207A4295435','2023-09-19 14:35:01','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',626.74),(12,'6CD49393GT4518807','2023-09-19 18:34:12','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',783.56),(13,'98005076ER266960K','2023-09-21 19:49:02','COMPLETED','sb-fz1og27365874@personal.example.com','G9VTGXX6BYRMA',891.76),(14,'8HX61577LK584831U','2023-09-25 00:47:53','COMPLETED','chavezfelipelouis@gmail.com','1',1246.02),(15,'5AB24014G5267525L','2023-09-25 01:24:10','COMPLETED','chavezfelipelouis@gmail.com','1',437.18),(16,'6TG74233CU572541Y','2023-09-25 19:06:55','COMPLETED','chavezfelipelouis@gmail.com','1',767.51),(17,'1VJ69586LV3761733','2023-09-30 21:20:00','COMPLETED','chavezfelipelouis@gmail.com','1',1491.86),(18,'2RM9416162915911U','2023-10-04 13:25:21','COMPLETED','chavezfelipelouis@gmail.com','1',3383.90),(19,'6Y308153D67018620','2023-12-03 18:50:07','COMPLETED','chavezfelipelouis@gmail.com','1',121322.88),(20,'28387015Y15729803','2023-12-03 18:56:34','COMPLETED','chavezfelipelouis@gmail.com','1',121322.88),(21,'93S10358KU058093W','2023-12-03 18:57:51','COMPLETED','chavezfelipelouis@gmail.com','1',121322.88),(22,'18R53961MH394133D','2023-12-03 19:36:03','COMPLETED','chavezfelipelouis@gmail.com','1',128.35),(23,'8GP986741F4855358','2023-12-03 19:40:52','COMPLETED','chavezfelipelouis@gmail.com','1',256.71),(24,'0EM79714RV995224F','2023-12-03 19:46:16','COMPLETED','chavezfelipelouis@gmail.com','1',128.35),(25,'1DG56570N1491393T','2023-12-03 19:49:03','COMPLETED','chavezfelipelouis@gmail.com','1',128.35),(26,'73B114963R3769212','2023-12-03 20:03:20','COMPLETED','chavezfelipelouis@gmail.com','1',14.99),(27,'9RJ10360DL614952H','2023-12-03 20:04:06','COMPLETED','chavezfelipelouis@gmail.com','1',14.99),(28,'47C72420LG534613V','2023-12-03 20:37:57','COMPLETED','chavezfelipelouis@gmail.com','1',226.72),(29,'75C48056N5899693G','2023-12-05 14:54:43','COMPLETED','chavezfelipelouis@gmail.com','1',405000.00),(30,'9NK84873GN742080W','2023-12-27 19:02:08','COMPLETED','chavezfelipelouis@gmail.com','1',1016.64),(31,'5JJ40892XR928992F','2023-12-27 19:14:09','COMPLETED','chavezfelipelouis@gmail.com','1',3073.95),(32,'5MW98531UP829223E','2023-12-27 19:17:19','COMPLETED','chavezfelipelouis@gmail.com','1',195.50),(33,'25358617CS658803M','2024-04-06 02:05:08','COMPLETED','chavezfelipelouis@gmail.com','1',141464.37),(34,'9W546569BX785723U','2024-05-05 19:16:46','COMPLETED','chavezfelipelouis@gmail.com','1',312.75),(35,'47999942JT985703Y','2024-05-07 22:16:36','COMPLETED','chavezfelipelouis@gmail.com','1',16.66);
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra_personal`
--

DROP TABLE IF EXISTS `compra_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compra_personal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_transaccion` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'COMPLETED',
  `email` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_cliente` int NOT NULL,
  `productos` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `referencias` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `total` double NOT NULL,
  `tipo_pago` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'PAYPAL',
  `id_personal` int NOT NULL,
  `validacion` int NOT NULL DEFAULT '1',
  `activo` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra_personal`
--

LOCK TABLES `compra_personal` WRITE;
/*!40000 ALTER TABLE `compra_personal` DISABLE KEYS */;
INSERT INTO `compra_personal` VALUES (1,'5431','2024-05-04 21:08:35','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',53456,'PAYPAL',0,1,1),(2,'5431','2024-05-04 21:08:35','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',53456,'PAYPAL',0,1,1),(3,'0','2024-05-05 17:59:38','COMPLETED','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(4,'0','2024-05-05 18:07:04','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(5,'0','2024-05-05 18:11:23','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(6,'0','2024-05-05 18:14:32','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(7,'0','2024-05-05 18:17:26','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(8,'0','2024-05-05 18:17:45','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(9,'0','2024-05-05 18:24:14','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(10,'0','2024-05-05 18:27:54','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(11,'0','2024-05-05 18:33:08','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(12,'0','2024-05-05 18:40:44','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(13,'0','2024-05-05 18:48:21','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(14,'0','2024-05-05 18:54:27','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(15,'0','2024-05-05 18:57:22','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(16,'0','2024-05-05 18:59:02','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(17,'0','2024-05-05 19:00:57','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(18,'757','2024-05-05 19:03:35','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(19,'0','2024-05-05 19:05:47','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(20,'61QF3ZD6M2XS8MQWG','2024-05-05 19:07:58','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(21,'TE59Q7HUNUO54ETRG','2024-05-05 19:33:27','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(22,'','0000-00-00 00:00:00','','',0,'8,89,,,','','',0,'PAYPAL',0,1,1),(23,'544FKZTJHW1EYMDXJ','2024-05-05 19:41:49','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(24,'VTRAZ8WBOPNXZ2Z3M','2024-05-05 19:53:37','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(25,'TH63QPP9EQL5DRCFB','2024-05-05 19:55:39','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(26,'BA3HTDRC0NB9T12IN','2024-05-05 19:57:14','','ppgbzx@gmail.com',5,'','','',0,'PAYPAL',0,1,1),(27,'YKBEG2J06VTRJACAN','2024-05-05 19:58:10','','ppgbzx@gmail.com',5,'15,146,,,','','',0,'PAYPAL',0,1,1),(28,'8WHNMKY9HIEGFXOEC','2024-05-05 22:16:56','','ppgbzx@gmail.com',5,'83,133,251,,','','',0,'PAYPAL',0,1,1),(29,'62R1JFA2YZFKY5TJ4','2024-05-05 22:24:14','','ppgbzx@gmail.com',5,'293,259,486,,','','',0,'PAYPAL',0,1,1),(30,'9K0B5Q69Q9LCYL5SN','2024-05-05 22:31:57','','chavezfelipelouis@gmail.com',1,'12,82,262,130,24312,82,262,130,243','','',0,'PAYPAL',0,1,1),(31,'2UCQTHFIDGOJIUVNY','2024-05-05 22:48:23','','ppgbzx@gmail.com',5,'138,73,60,142,129138,73,60,142,129138,73,60,142,129138,73,60,142,129138,73,60,142,129138,73,60,142,129','','',0,'PAYPAL',0,1,1),(32,'EH97XKYL94PEHDQZI','2024-05-05 22:56:01','','chavezfelipelouis@gmail.com',1,'67,69,77,73,67,69,77,73,','','',0,'PAYPAL',0,1,1),(33,'0GC2R1GK0AUE3P1WA','2024-05-05 22:59:02','','chavezfelipelouis@gmail.com',1,'81,89,,,81,89,,,81,89,,,81,8981,8981,8981,8981,8981,8981,89','','',0,'PAYPAL',0,1,1),(34,'HBK40UKXHD00BBCTY','2024-05-05 23:15:52','','chavezfelipelouis@gmail.com',1,'70,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,12470,85,124','','',58.23,'PAYPAL',0,1,1),(35,'D29LVRMERUQUWAJUS','2024-05-05 23:50:39','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(36,'ZNU10L63D69WN6WS1','2024-05-05 23:52:34','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(37,'NYGXFRW3B0Y1W4AKM','2024-05-05 23:54:32','','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',0,1,1),(38,'4YAOGIEHIHO102ES5','2024-05-06 00:07:01','','chavezfelipelouis@gmail.com',1,'11','','',38.66,'PAYPAL',0,1,1),(39,'EZ7KB3SZDNKQOW62R','2024-05-06 00:29:26','','chavezfelipelouis@gmail.com',1,'84,64','','',40.17,'PAYPAL',0,1,1),(40,'N9N84ODT53FYU2F99','2024-05-06 00:53:33','','chavezfelipelouis@gmail.com',1,'47,6347,6347,6347,6347,6347,6347,6347,6347,6347,6347,6347,63','','',23,'PAYPAL',0,1,1),(41,'8EG0D7JA9FTP3ZQYB','2024-05-06 01:05:43','','chavezfelipelouis@gmail.com',1,'','','',69.89,'PAYPAL',0,1,1),(42,'LDZP6C9PZZCD85T0S','2024-05-06 01:10:53','','chavezfelipelouis@gmail.com',1,'7,87,8','','',115.81,'PAYPAL',0,1,1),(43,'AKGEGKZBXG3SCER2F','2024-05-06 01:16:15','','chavezfelipelouis@gmail.com',1,'9,61','','',26.36,'PAYPAL',0,1,1),(44,'EACFF55K1STXMQKO9','2024-05-06 01:17:10','','ppgbzx@gmail.com',5,'62,71','','',446.34,'PAYPAL',0,1,1),(45,'15AV6ZYCM7VG31REH','2024-05-06 01:20:06','','chavezfelipelouis@gmail.com',1,'13,66','','',1082.32,'PAYPAL',0,1,1),(46,'7M6UR9UWPABY7N3TP','2024-05-06 01:57:51','','chavezfelipelouis@gmail.com',1,'','','',967,'PAYPAL',0,1,1),(47,'HCAR5PG67D5IRUV74','2024-05-06 02:11:58','','chavezfelipelouis@gmail.com',1,'','','',84.01,'PAYPAL',0,1,1),(48,'OTGM1ORZHUYMUY1SY','2024-05-06 02:13:03','','chavezfelipelouis@gmail.com',1,'14,62','','',15.81,'PAYPAL',0,1,1),(49,'5OKZX6RMBURY2BYZR','2024-05-06 02:19:02','','chavezfelipelouis@gmail.com',1,'','','',1697.2,'PAYPAL',0,1,1),(50,'J7EE2PTUIJGT9VYH8','2024-05-06 02:49:09','','ppgbzx@gmail.com',5,'','','',509.79,'PAYPAL',50,1,1),(51,'EWIHA6MCYK8M4Q92W','2024-05-06 02:51:17','','chavezfelipelouis@gmail.com',1,'','','',1239.7,'PAYPAL',0,1,1),(52,'DBLYLVDOHTFS7HR6Q','2024-05-06 02:59:23','DISABLED','chavezfelipelouis@gmail.com',1,'','','',1164.14,'PAYPAL',6,1,0),(53,'UTUZ8VI66K3DL6HFR','2024-05-06 03:05:43','DISABLED','chavezfelipelouis@gmail.com',1,'','','',763.05,'PAYPAL',6,1,0),(54,'R106YBLG9ZNMSVLJ8','2024-05-06 22:58:42','','ppgbzx@gmail.com',5,'','','',2398.89,'PAYPAL',6,1,0),(55,'8FYON999OVRB1NMNJ','2024-05-06 23:38:05','','chavezfelipelouis@gmail.com',1,'','','',724.35,'PAYPAL',6,1,0),(56,'JS6XB8Q7N0IKKVQNS','2024-05-06 23:40:23','DISABLED','chavezfelipelouis@gmail.com',1,'','','',614.65,'PAYPAL',6,1,0),(57,'G0EPOPDZHF1ZHFTJ6','2024-05-06 23:42:06','','chavezfelipelouis@gmail.com',1,'','','',381.33,'PAYPAL',2,1,1),(58,'Q3M9UN54PVWGHKFT7','2024-05-06 23:46:49','DISABLED','chavezfelipelouis@gmail.com',1,'','','',457.38,'PAYPAL',6,1,0),(59,'AKYACA2QXSESHAFB4','2024-05-06 23:50:44','','chavezfelipelouis@gmail.com',1,'','','',96.92,'PAYPAL',10,1,1),(60,'NOU3Q6NBM4A1VNFWO','2024-05-07 01:25:37','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',51.46,'PAYPAL',4,1,1),(61,'LLDZMNCHF6SWJM2WP','2024-05-07 01:33:46','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',83.49,'PAYPAL',3,1,1),(62,'MXVWGFDEG528W09OW','2024-05-07 01:52:03','COMPLETED','ppgbzx@gmail.com',5,'','','',56.21,'PAYPAL',2,1,1),(63,'DVAAF97XI6P4PZAGG','2024-05-07 02:18:50','COMPLETED','ppgbzx@gmail.com',5,'','','',437.5,'PAYPAL',4,1,1),(64,'Z6J067K7ARB42YBFI','2024-05-07 02:33:10','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',125.29,'PAYPAL',5,1,1),(65,'81U0Y3BRCXWKSJN6Q','2024-05-07 02:34:14','COMPLETED','ppgbzx@gmail.com',5,'','','',361.72,'PAYPAL',3,1,1),(66,'2I14CODQ4P0TYZN40','2024-05-07 03:03:45','COMPLETED','ppgbzx@gmail.com',5,'','','',76.64,'PAYPAL',9,1,1),(67,'ZFRHWF1ULSTJGCYVV','2024-05-07 03:09:36','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',664.3,'PAYPAL',5,1,1),(68,'89YBZ100QELYJJNWD','2024-05-07 03:11:41','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1507.26,'PAYPAL',7,1,1),(69,'GTK4SZGKJ9F7XAYJO','2024-05-07 16:16:00','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',964.55,'PAYPAL',5,1,1),(70,'VJGG01FF48O2EDGW1','2024-05-07 17:02:51','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1283.83,'PAYPAL',7,1,1),(71,'T973ZSBRUKEASMFAI','2024-05-07 17:41:35','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1213.84,'PAYPAL',9,1,1),(72,'PQIAS26ABWLCP5PLA','2024-05-07 17:43:18','COMPLETED','ppgbzx@gmail.com',5,'','','',855.09,'PAYPAL',7,1,1),(73,'LDGB3COLJ7MWH95CH','2024-05-07 17:47:28','COMPLETED','ppgbzx@gmail.com',5,'','','',299.62,'PAYPAL',4,1,1),(74,'BWLL6CQW3USJQ56YS','2024-05-07 17:51:18','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',366.52,'PAYPAL',6,1,1),(75,'YYJL1KVNJNOA6749Q','2024-05-07 17:52:27','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',37.21,'PAYPAL',9,1,1),(76,'8C6XYDJE98C1032K7','2024-05-07 17:54:00','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1463.22,'PAYPAL',9,1,1),(77,'X3X7R15UUXMU78PEP','2024-05-07 17:58:17','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1346.86,'PAYPAL',2,1,1),(78,'XD0Q9892BGCXZIEF0','2024-05-07 18:00:48','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1612.56,'PAYPAL',6,1,1),(79,'BSVDNSXTKO76N9R50','2024-05-07 18:09:09','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1572.99,'PAYPAL',5,1,1),(80,'XXB2C7GBUYKMR7RHO','2024-05-07 18:13:08','COMPLETED','ppgbzx@gmail.com',5,'','','',1148.15,'PAYPAL',7,1,1),(81,'ZV5NXGLREG02HVXEQ','2024-05-07 20:32:20','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',668.09,'PAYPAL',7,1,1),(82,'P93IVERELXMUQG2HH','2024-05-07 20:41:37','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',256.77,'PAYPAL',6,1,1),(83,'TRV1USIMT40XRVLWW','2024-05-07 20:45:44','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',626.73,'PAYPAL',5,1,1),(84,'KRS49XMBKKQLLJR7E','2024-05-07 20:58:57','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',1163.6,'PAYPAL',9,1,1),(85,'V7EATOIWE8UX2YZ2C','2024-05-08 01:10:12','COMPLETED','ppgbzx@gmail.com',5,'','','',1090.89,'PAYPAL',10,1,1),(86,'C2WGRPDC6VWGX09OZ','2024-05-08 02:32:53','COMPLETED','chavezfelipelouis@gmail.com',1,'','Valor ingresado en el campo de dirección manual','',139.48,'PAYPAL',4,1,1),(87,'DJTKMI6GZY0XUBD30','2024-05-08 13:29:37','COMPLETED','ppgbzx@gmail.com',5,'','','',532.2,'PAYPAL',3,1,1),(88,'E737ZV89AFIBUKVUY','2024-05-08 15:21:15','COMPLETED','ppgbzx@gmail.com',5,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',482.1,'PAYPAL',5,1,1),(89,'P7PJYRXKBMPYVOCZB','2024-05-08 15:52:13','COMPLETED','ppgbzx@gmail.com',5,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',836.86,'PAYPAL',3,1,1),(90,'NVPSAGZ9AEL2ZBK1M','2024-05-08 22:26:43','COMPLETED','chavezfelipelouis@gmail.com',1,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',417.1,'PAYPAL',5,1,1),(91,'YJR2UPAMKN9HK46PM','2024-05-08 23:50:47','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',62.59,'PAYPAL',9,1,1),(92,'FBOGGOC0WYFWJVZOK','2024-05-09 00:51:09','COMPLETED','chavezfelipelouis@gmail.com',1,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',348.7,'PAYPAL',6,1,1),(93,'NDPLE27DNE29U2XEW','2024-05-08 19:25:52','COMPLETED','ppgbzx@gmail.com',5,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',532.2,'PAYPAL',7,1,1),(94,'BIGJWPP9N7GDQQESG','2024-05-09 10:22:39','COMPLETED','ppgbzx@gmail.com',5,'','Calle Guadalupe Victoria, 93603 Martínez de la Torre, VER, México','',196.2,'PAYPAL',6,1,1),(95,'1NE6VDBFG3IIUN6PL','2024-05-09 16:33:56','COMPLETED','ppgbzx@gmail.com',5,'','Boulevard Alfinio Flores Beltrán, 93603 Martínez de la Torre, VER, México','A 200 metros de la tienda abarrotes super taco',421.9,'PAYPAL',9,1,1),(96,'2XXQL54XS7HCO8SE1','2024-05-09 17:33:32','COMPLETED','ppgbzx@gmail.com',5,'','Calle Gabriela Mistral, 93603 Martínez de la Torre, VER, México','casa Numero 67. A la vuelta del Oxxo',656.57,'PAYPAL',5,1,1),(97,'72FDIW44N7OHG0MCU','2024-05-09 18:07:48','COMPLETED','ppgbzx@gmail.com',5,'','','',3234.4975,'PAYPAL',6,1,1),(98,'F6PNOM7FB8RG1NC3P','2024-05-09 18:12:31','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',274.8,'PAYPAL',6,1,1),(99,'4UAYFEL6OOO63C3C5','2024-05-09 18:14:30','COMPLETED','ppgbzx@gmail.com',5,'','','',585.42,'PAYPAL',6,1,1),(100,'WQQWDJVBJGKIIA7Z4','2024-05-09 18:41:00','COMPLETED','ppgbzx@gmail.com',5,'','Calle General Leandro Valle, 93603 Martínez de la Torre, VER, México','A lado de la gasolineria. Casa 259',761.2,'PAYPAL',6,1,1),(101,'BFIS7O0GB4DG0EPJU','2024-05-10 15:49:10','APPROVED','chavezfelipelouis@gmail.com',1,'','','',1428.9,'PAYPAL',6,1,1),(102,'IEMLE0B3A2Y093ZAM','2024-05-10 17:56:30','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',0,'PAYPAL',7,1,1),(103,'ZZKDFR5EHNVHRY0ST','2024-05-10 17:57:13','APPROVED','chavezfelipelouis@gmail.com',1,'','','',2734.14,'PAYPAL',7,1,1),(104,'6U4J22L05I8LGHG8L','2024-05-10 18:00:47','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',530.31,'PAYPAL',6,1,1),(105,'R8680QUFAFRYFBTPN','2024-05-10 18:03:58','APPROVED','chavezfelipelouis@gmail.com',1,'','','',299.34,'PAYPAL',6,1,1),(106,'JQYDG858OCDFCAF9Y','2024-05-10 18:06:48','APPROVED','chavezfelipelouis@gmail.com',1,'','','',66.64,'PAYPAL',6,1,1),(107,'TNRHCAA7VAMIB7WHG','2024-05-10 18:16:54','COMPLETED','chavezfelipelouis@gmail.com',1,'','','',166.6,'PAYPAL',6,1,1),(108,'2535JJ4Y0NM90I366','2024-05-11 08:08:37','APPROVED','ppgbzx@gmail.com',5,'','','',1579.58,'PAYPAL',6,1,1),(109,'XAU9RJJ528EIHVQ8S','2024-05-11 09:41:56','APPROVED','ppgbzx@gmail.com',5,'','','',989.07,'PAYPAL',6,1,1),(110,'OH8RIAUWDXQ8T7RF3','2024-05-11 13:52:49','APPROVED','ppgbzx@gmail.com',5,'','Calle Lealtad, 93603 Martínez de la Torre, VER, México','Casa Numero 431, color blanco con azul',779.16,'PAYPAL',6,1,1),(111,'NZEFLGOTN5WQ4ZGEC','2024-05-11 14:22:22','APPROVED','ppgbzx@gmail.com',5,'','Calle Salvador Allende, 93600 Martínez de la Torre, VER, México','',110.8,'EFECTIVO',6,1,1),(112,'SA6TOPTFBAWDZRP5T','2024-05-11 20:18:21','APPROVED','chavezfelipelouis@gmail.com',1,'','','',77.21,'EFECTIVO',6,1,1),(113,'Z0MGHVM2O74D8IN2Q','2024-05-11 21:00:23','APPROVED','chavezfelipelouis@gmail.com',1,'','Calle Uxmal, 93607 Martínez de la Torre, VER, México','Casa 672. Color azul con blanco. A lado de la papeleria Mix',61.39,'EFECTIVO',6,1,1);
/*!40000 ALTER TABLE `compra_personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra_personal_productos`
--

DROP TABLE IF EXISTS `compra_personal_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compra_personal_productos` (
  `id_venta` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra_personal_productos`
--

LOCK TABLES `compra_personal_productos` WRITE;
/*!40000 ALTER TABLE `compra_personal_productos` DISABLE KEYS */;
INSERT INTO `compra_personal_productos` VALUES (45,13,15),(45,66,22),(46,8,10),(46,62,30),(46,8,10),(46,62,30),(47,71,1),(47,70,1),(48,14,17),(48,62,22),(49,70,19),(49,70,21),(50,72,15),(50,58,18),(50,72,15),(50,58,18),(51,10,19),(51,52,11),(52,12,19),(52,7,10),(53,69,10),(53,72,15),(53,69,10),(53,72,15),(54,73,17),(54,76,28),(54,73,17),(54,76,28),(54,73,17),(54,76,28),(54,73,17),(54,76,28),(55,65,11),(55,197,19),(55,65,11),(55,197,19),(56,72,11),(56,117,18),(57,119,6),(57,168,11),(58,71,11),(58,71,11),(59,63,1),(59,133,1),(59,63,1),(59,133,1),(59,63,1),(59,133,1),(59,63,1),(59,133,1),(59,63,1),(59,133,1),(59,63,6),(59,133,5),(59,63,6),(59,133,5),(59,63,6),(59,133,5),(60,58,1),(60,70,1),(61,69,1),(61,68,1),(62,79,1),(62,53,1),(63,71,10),(63,119,1),(64,77,10),(64,78,1),(65,66,1),(65,59,1),(65,66,10),(65,59,1),(66,64,1),(66,70,1),(67,70,10),(67,75,12),(68,68,19),(68,73,13),(68,68,19),(68,73,13),(68,68,19),(68,73,13),(68,68,19),(68,73,13),(69,52,10),(69,72,19),(69,52,10),(69,72,19),(70,13,10),(70,8,17),(70,13,10),(70,8,17),(71,1,10),(71,2,9),(72,1,10),(72,8,11),(73,1,11),(73,2,1),(74,1,11),(74,1,11),(75,54,1),(75,49,1),(76,2,11),(76,1,11),(77,1,11),(77,2,10),(78,2,11),(78,12,10),(79,3,10),(79,4,12),(80,3,10),(80,5,11),(81,5,10),(81,8,1),(82,54,10),(82,10,1),(83,3,13),(84,2,10),(85,4,12),(86,10,4),(87,7,10),(88,3,10),(89,115,10),(89,11,16),(90,56,10),(91,8,1),(91,8,1),(92,10,10),(93,7,10),(94,59,10),(95,74,11),(95,57,1),(96,58,10),(96,58,14),(96,72,19),(97,15,19),(97,4,21),(97,15,19),(97,4,21),(97,15,19),(97,4,21),(98,6,10),(98,6,10),(99,7,11),(100,67,10),(100,66,10),(101,8,18),(101,6,11),(101,8,18),(101,6,11),(101,8,18),(101,6,11),(101,8,18),(101,6,11),(101,8,18),(101,6,11),(103,4,20),(103,3,19),(103,4,20),(103,3,19),(104,3,11),(105,12,9),(106,1,4),(106,1,4),(107,1,10),(108,1,11),(108,2,12),(109,10,11),(109,5,10),(110,58,12),(110,51,12),(111,8,1),(111,3,1),(112,5,1),(112,1,1),(113,14,11),(113,61,1);
/*!40000 ALTER TABLE `compra_personal_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `valor` tinytext COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
INSERT INTO `configuracion` VALUES (1,'tienda_nombre','Chedraui'),(2,'correo_email','chavezfelipelouis@gmail.com'),(3,'correo_smtp','smtp.gmail.com'),(4,'correo_password','9GgwOkRFjf6AQCLYHdXyWQ==:CQaTpUPNNhdTgkPAmJtHzxUm1Q89HifXlw7y2Wf0Hyw='),(5,'correo_puerto','465');
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_compra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_compra` int NOT NULL,
  `id_producto` int NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_compra`
--

LOCK TABLES `detalle_compra` WRITE;
/*!40000 ALTER TABLE `detalle_compra` DISABLE KEYS */;
INSERT INTO `detalle_compra` VALUES (1,1,1,'123 Maxi Poder 1l',14.99,3),(2,1,2,'Absorsec Etapa 2',113.36,8),(3,1,5,'Absorsec Ultra',60.55,3),(4,1,4,'Absorsec Grande 48p',106.95,4),(5,2,2,'Absorsec Etapa 2',113.36,3),(6,2,3,'Absorsec Etapa 3',48.21,2),(7,3,2,'Absorsec Etapa 2',113.36,4),(8,3,3,'Absorsec Etapa 3',48.21,5),(9,4,2,'Absorsec Etapa 2',113.36,2),(10,4,5,'Absorsec Ultra',60.55,2),(11,4,4,'Absorsec Grande 48p',106.95,2),(12,5,2,'Absorsec Etapa 2',113.36,2),(13,5,11,'Aceite La Negrita 800ml',38.66,3),(14,5,10,'Aceite El Farol 850ml',34.87,3),(15,6,3,'Absorsec Etapa 3',48.21,3),(16,6,2,'Absorsec Etapa 2',113.36,4),(17,6,20,'Aceite Soraya 840ml',39.98,3),(18,6,21,'Aceite Soraya 890ml',22.34,3),(19,7,3,'Absorsec Etapa 3',48.21,3),(20,7,2,'Absorsec Etapa 2',113.36,7),(21,7,20,'Aceite Soraya 840ml',39.98,3),(22,7,21,'Aceite Soraya 890ml',22.34,3),(23,8,2,'Absorsec Etapa 2',113.36,3),(24,8,3,'Absorsec Etapa 3',48.21,3),(25,8,6,'Aceite 123 500ml',27.48,5),(26,9,2,'Absorsec Etapa 2',113.36,4),(27,9,3,'Absorsec Etapa 3',48.21,4),(28,9,592,'Zuko Piña',3.91,3),(29,9,594,'Zuko Piña Colada',3.09,2),(30,10,2,'Absorsec Etapa 2',113.36,8),(31,10,3,'Absorsec Etapa 3',48.21,3),(32,10,5,'Absorsec Ultra',60.55,6),(33,11,2,'Absorsec Etapa 2',113.36,5),(34,11,3,'Absorsec Etapa 3',48.21,1),(35,11,595,'Zuko Tamarindo',3.91,3),(36,12,3,'Absorsec Etapa 3',48.21,2),(37,12,2,'Absorsec Etapa 2',113.36,4),(38,12,20,'Aceite Soraya 840ml',39.98,3),(39,12,19,'Aceite Soraya 800ml',37.92,3),(40,13,5,'Absorsec Ultra',60.55,6),(41,13,13,'Aceite Nutrioli 400ml',21.98,5),(42,13,15,'Aceite Patrona 1.5ml',69.76,6),(43,14,1,'123 Maxi Poder 1l',14.99,6),(44,14,2,'Absorsec Etapa 2',113.36,3),(45,14,8,'Aceite Capullo 840ml',62.59,3),(46,14,7,'Aceite 123 Litro',53.22,3),(47,14,15,'Aceite Patrona 1.5ml',69.76,5),(48,14,27,'Ajax Bicloro Polvo 388g',23.95,5),(49,15,2,'Absorsec Etapa 2',113.36,3),(50,15,1,'123 Maxi Poder 1l',14.99,1),(51,15,596,'Zuko Tamarindo',3.91,3),(52,15,595,'Zuko Tamarindo',3.91,3),(53,15,592,'Zuko Piña',3.91,3),(54,15,593,'Zuko Piña Colada',3.91,12),(55,16,2,'Absorsec Etapa 2',113.36,3),(56,16,3,'Absorsec Etapa 3',48.21,7),(57,16,1,'123 Maxi Poder 1l',14.99,6),(58,17,1,'123 Maxi Poder 1l',14.99,11),(59,17,2,'Absorsec Etapa 2',113.36,1),(60,17,4,'Absorsec Grande 48p',90.91,8),(61,17,5,'Absorsec Ultra',60.55,1),(62,17,7,'Aceite 123 Litro',53.22,8),(63,18,1,'123 Maxi Poder 1l',14.99,7),(64,18,2,'Absorsec Etapa 2',113.36,10),(65,18,3,'Absorsec Etapa 3',48.21,20),(66,18,4,'Absorsec Grande 48p',90.91,5),(67,18,5,'Absorsec Ultra',60.55,12),(68,19,604,'Computadora Pc Gamer Ryzen 9 2600x 8gb 1tb 1650 Led 24 Ips',15661.44,2),(69,20,604,'Computadora Pc Gamer Ryzen 9 2600x 8gb 1tb 1650 Led 24 Ips',2.00,15661),(70,20,605,'Nintendo Switch',2.00,45000),(71,21,605,'Nintendo Switch',2.00,45000),(72,21,604,'Computadora Pc Gamer Ryzen 9 2600x 8gb 1tb 1650 Led 24 Ips',2.00,15661),(73,22,1,'123 Maxi Poder 1L',1.00,15),(74,22,2,'Absorsec Etapa 2',1.00,113),(75,23,2,'Absorsec Etapa 2',2.00,113),(76,23,1,'123 Maxi Poder 1L',2.00,15),(77,24,2,'Absorsec Etapa 2',1.00,113),(78,24,1,'123 Maxi Poder 1L',1.00,15),(79,25,1,'123 Maxi Poder 1L',1.00,15),(80,25,2,'Absorsec Etapa 2',1.00,113),(81,26,1,'123 Maxi Poder 1L',1.00,15),(82,27,1,'123 Maxi Poder 1L',1.00,15),(83,28,2,'Absorsec Etapa 2',113.36,2),(84,29,605,'Nintendo Switch',45000.00,9),(85,30,1,'123 Maxi Poder 1L',14.99,10),(86,30,5,'Absorsec Ultra',60.55,6),(87,30,3,'Absorsec Etapa 3',48.21,1),(88,30,135,'COLGATE TRIPLE ACCION XTRA BLANCURA',11.83,4),(89,30,136,'Colgate Ultra Blanco 1',34.22,5),(90,30,105,'Charola Grande 9h',36.80,6),(91,30,252,'Jabón Camay Clásico Grande 150 Gramos',15.97,1),(92,31,546,'Vainilla La Anita 120gramos',10.24,1),(93,31,557,'Vela',3.71,1),(94,31,598,'Vestido Azul',1020.00,3),(95,32,586,'ZUKO LIMON',3.91,50),(96,33,2,'Absorsec Etapa 2',116.36,7),(97,33,3,'Absorsec Etapa 3',48.21,7),(98,33,605,'Nintendo Switch',45000.00,3),(99,33,5,'Absorsec Ultra',60.55,1),(100,33,6,'Aceite 123 500ml',27.48,1),(101,33,601,'Gelatina Merl',19.80,1),(102,33,598,'Vestido Azul',1020.00,5),(103,33,594,'Zuko Piña Colada',3.09,1),(104,33,580,'Zuko Durazno',3.85,1),(105,33,587,'Zuko Mandarina',3.91,2),(106,33,557,'Vela',3.71,1),(107,33,207,'Gaygon Casa Y Jardin 226 Ml',26.58,1),(108,33,241,'Italpasta Codo Rayado',6.08,1),(109,33,25,'Aderezo Clementes Mil Islas 237 G',26.71,2),(110,34,602,'Pay Mex',42.75,5),(111,34,601,'Gelatina Merl',19.80,5),(112,35,1,'123 Maxi Poder',16.66,1);
/*!40000 ALTER TABLE `detalle_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int DEFAULT NULL,
  `cantidad_minima` int DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_spanish_ci,
  `prioridad` enum('Baja','Media','Alta') COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `status` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'ENVIADA',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_personal` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
INSERT INTO `notificacion` VALUES (1,NULL,NULL,'Hola, estos productos es necesario proveer mas...Ya que lo clientes ultimamente han estado comprado mucho','Media','ENVIADA','2024-05-12 21:04:57',0),(2,4,32,'reabastecer esos productos','Alta','ENVIADA','2024-05-12 21:15:54',6),(3,1,NULL,'reabastecer urgentemente','Alta','ENVIADA','2024-05-12 21:20:12',6),(4,1,NULL,'hola','Baja','ENVIADA','2024-05-12 21:48:21',6),(5,3,NULL,'hoalkdfgh','Baja','ENVIADA','2024-05-12 22:05:40',6),(6,1,NULL,'asdfghjkl','Baja','ENVIADA','2024-05-12 22:08:27',6),(7,NULL,NULL,'hola','Baja','ENVIADA','2024-05-12 22:12:12',6),(8,NULL,NULL,'Solicitar mas productos a los proveedores, recordando que se quedo a pendiente el ultimo encargo','Alta','ENVIADA','2024-05-12 22:33:55',6);
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion_producto`
--

DROP TABLE IF EXISTS `notificacion_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion_producto` (
  `id_notificacion` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  KEY `id_notificacion` (`id_notificacion`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion_producto`
--

LOCK TABLES `notificacion_producto` WRITE;
/*!40000 ALTER TABLE `notificacion_producto` DISABLE KEYS */;
INSERT INTO `notificacion_producto` VALUES (7,1),(7,2),(8,8),(8,12);
/*!40000 ALTER TABLE `notificacion_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `puesto` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `token_password` varchar(40) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `password_request` tinyint NOT NULL DEFAULT '0',
  `activo` tinyint NOT NULL,
  `fecha_alta` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (1,'pearson','$2y$10$ES2rHrtLQFPkKy6mCR5otuHXI8JQMZyFfICJnwUA6E6y8ofoTJvBW','Louis Chavez','PERSONAL','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 19:01:51'),(2,'ElAmanteDelCafe','$2y$10$kQxjNnbaAFSJj2TIWgLdf.VOfjWAsSPBDDcuS05blYwVDCE2xKAge','Alejandro Arteaga','BEBIDAS','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(3,'ChefGourmet','$2y$10$dkq6LEv6C39TNtv395Vi4O8mbeHRjzwMrtRjCp1YmewiT9mrpv5li','Martín  Magallan','COMIDA','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(4,'SrLimpios','$2y$10$WqyQ3IPIbOJEf9qnlw8DO.a1PlOWexqMOkpI3bjQUzY9iPVCz1hjq','Fernando Ceballos','LIMPIEZA','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(5,'FashionMan','$2y$10$GLu1LOdV8j7vcuDTOqsZm.XCG8GE/d.3dQT9MJiXLc0t7GO6dXThu','Valentina Nappi','ROPA PARA CABALLERO','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(6,'StyleQueen','$2y$10$/T7aSMQrGKeOQtokivzEhuXAsf898Zs5AVFvE8YGqvsLC5.fbRYjC','Elsa Jean','ROPA PARA DAMA','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(7,'TechGeek','$2y$10$3p2Pd5hzKoVYI2jEf0QMO.T3llOV.rDhOKf0ot.vxEcKeLFfhCExK','Adriana Chechik','ELECTRONICA','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-01 20:31:30'),(8,'TechGeek','$2y$10$h9P2fVZQMxxcW7YX810Xhej1SJw5/wnWs/OiVJ4I1UOLm8mIx3nVO','Adriana Chechik','ELECTRONICA','chavezfelipelouis@gmail.com',NULL,0,0,'2024-05-01 20:31:30'),(9,'CleanFreak','$2y$10$mjionZOnmF.YcRjpcXZRleHJXjyThh8ha0zIKj42XmDdXNHH/vZKm','Ariella Ferrera','HIGIENE','chavezfelipelouis@gmail.com',NULL,0,1,'2024-05-02 09:15:08'),(10,'trato','$2y$10$RGUQAi8Tsl5UatTB9rnAdOfqhgMBQg4XcqTYcOiR7TUIf9mdwil0q','Julio Paramo','BEBIDAS','ppgbzx@gmail.com',NULL,0,1,'2024-05-02 12:09:44'),(11,'trato','$2y$10$D0qp0yL6KcJtv0nG55IwYeSwuBBUv0s2sgU/5skoLXgxf17vGN.Q2','Julio Paramo','BEBIDAS','ppgbzx@gmail.com',NULL,0,0,'2024-05-02 12:11:25'),(12,'CleanMaster','$2y$10$0K5xnqggBtc8GotcMuQFCukrkdjW0yy9lvvz.76OvYZQVIwW0bstK','Julio Jimenez','','chavezfelipelouis@gmail.com',NULL,0,0,'2024-05-03 21:25:09');
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint NOT NULL DEFAULT '0',
  `stock` int NOT NULL DEFAULT '0',
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `puesto` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_categoria` int NOT NULL,
  `activo` int NOT NULL,
  `id_proveedor` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'123 Maxi Poder',16.66,0,33,'predeterminada','',32,1,0),(2,'Absorsec Etapa 2',116.36,0,38,'<p>-Kleen Bebe Absorsec Unisex Chico Etapa 2 -Con 40 Pañales Kleen Absorsec</p>','',32,1,0),(3,'Absorsec Etapa 3',48.21,0,30,'<p>predeterminada</p>','',32,1,0),(4,'Absorsec Grande 48p',106.95,15,30,'predeterminada','',32,1,0),(5,'Absorsec Ultra',60.55,0,32,'predeterminada','',32,1,0),(6,'Aceite 123 500ml',27.48,0,80,'predeterminada','',2,1,0),(7,'Aceite 123 Litro',53.22,0,50,'predeterminada','',2,1,0),(8,'Aceite Capullo 840ml',62.59,0,31,'predeterminada','',2,1,0),(9,'Aceite De Oliva Extra Virgen 50Ml',13.81,0,190,'predeterminada','',2,1,1),(10,'Aceite El Farol 850ml',34.87,0,192,'predeterminada','',2,1,1),(11,'Aceite La Negrita 800ml',38.66,0,86,'predeterminada','',2,1,1),(12,'Aceite La Negrita 880ml',33.26,0,41,'predeterminada','',2,1,0),(13,'Aceite Nutrioli 400ml',21.98,0,50,'predeterminada','',2,1,0),(14,'Aceite Olivo 45ml',4.44,0,39,'predeterminada','',2,1,0),(15,'Aceite Patrona 1.5ml',69.76,0,50,'predeterminada','',2,1,0),(16,'Aceite Patrona 1Litro',47.19,0,50,'predeterminada','',2,1,0),(17,'Aceite Patrona 500ml',23.26,0,50,'predeterminada','',2,1,0),(18,'Aceite San Sebastian 40ml',5.17,0,50,'predeterminada','',2,1,0),(19,'Aceite Soraya 800ml',37.92,0,50,'predeterminada','',2,1,0),(20,'Aceite Soraya 840ml',39.98,0,50,'predeterminada','',2,1,0),(21,'Aceite Soraya 890ml',22.34,0,50,'predeterminada','',2,1,0),(22,'Acido Muriatico De 900 Ml',21.75,0,50,'predeterminada','',5,1,0),(23,'Acido Muriatico De 950 Ml',16.07,0,50,'predeterminada','',5,1,0),(24,'Acido Muriatico Sultan 400 Ml',12.92,0,50,'predeterminada','',5,1,0),(25,'Aderezo Clementes Mil Islas 237 G',26.71,0,48,'predeterminada','',2,1,0),(26,'Aderezo Clementes Ranch 237 G',26.71,0,50,'predeterminada','',2,1,0),(27,'Ajax Bicloro Polvo 388g',23.95,0,50,'predeterminada','',5,1,0),(28,'Alumega 7 M X 30 M',16.14,0,50,'predeterminada','',32,1,0),(29,'Aluminio Alupack  9.8 M X 29 Cm',21.13,0,50,'predeterminada','',1,1,0),(30,'Aluminio Red Tag Norma',15.18,0,50,'predeterminada','',2,1,0),(31,'Alupratick 10 M',11.57,0,50,'predeterminada','',5,1,0),(32,'Ariel 250 Ml',11.20,0,50,'predeterminada','',5,1,0),(33,'Ariel Bolsa 500gms',22.30,0,50,'predeterminada','',5,1,0),(34,'Ariel Reg 850 Gr',33.30,0,50,'predeterminada','',5,1,0),(35,'Atun Dolores Aceite',17.56,0,50,'predeterminada','',2,1,0),(36,'Atun Dolores Agua',10.72,0,50,'predeterminada','',2,1,0),(37,'Atun Dolores En Aceite',18.74,0,50,'predeterminada','',2,1,0),(38,'Atun El Dorado Agua',11.37,0,50,'predeterminada','',2,1,0),(39,'Atun Herdez En Aceite 295 G',43.09,0,50,'predeterminada','',2,1,0),(40,'Atun Herdez En Agua 295 G',43.09,0,50,'predeterminada','',2,1,0),(41,'Atun Nair Aceite',11.00,0,50,'predeterminada','',2,1,0),(42,'Atun Nair Agua',11.23,0,50,'predeterminada','',2,1,0),(43,'Atun Nair En Aceite 120g',11.40,0,50,'predeterminada','',2,1,0),(44,'Atun Nair En Agua 120 G',11.70,0,50,'predeterminada','',2,1,0),(45,'Avena 3 minutos 400g',21.77,0,30,'predeterminada','',2,1,0),(46,'Avena Quaker 380gr',13.03,0,50,'predeterminada','',2,1,0),(47,'Axion 250g Polvo',11.63,0,50,'predeterminada','',5,1,0),(48,'Axion Limon 500g',19.15,0,50,'predeterminada','',5,1,0),(49,'Axion Liquido 400ml',15.02,0,50,'predeterminada','',5,1,0),(50,'Axion Liquido Limon 900ml',35.00,0,50,'predeterminada','',5,1,0),(51,'AZUCARADAS',55.90,0,38,'predeterminada','',2,1,0),(52,'Baygon Casa Y Gardin 400ml',52.47,0,50,'predeterminada','',5,1,0),(53,'Baygon Casa Y Jardin',27.90,0,50,'predeterminada','',5,1,0),(54,'Baygon Casa Y Jardin 285ml',22.19,0,50,'predeterminada','',5,1,0),(55,'Baygon Ultra Verde 400 Ml',50.42,0,50,'predeterminada','',5,1,0),(56,'Baygon Verde 258 Ml',41.71,0,50,'predeterminada','',5,1,0),(57,'Blanca Nieves 1k',37.67,0,50,'predeterminada','',5,1,0),(58,'Blanca Nieves 250g',9.03,0,38,'predeterminada','',5,1,0),(59,'Blanca Nieves 500g',19.62,0,50,'predeterminada','',5,1,0),(60,'Blasa Pastilla Aromatica',9.81,0,50,'predeterminada','',5,1,0),(61,'Braso Perfumes Air Wick',12.55,0,49,'predeterminada','',5,1,0),(62,'Brasso Gold De Lavanda',11.37,0,50,'predeterminada','',5,1,0),(63,'Brasso Perfumes Del Hogar',11.37,0,50,'predeterminada','',5,1,0),(64,'Cajeta Coronado Envi',34.21,0,50,'predeterminada','',2,1,0),(65,'Cajeta Coronado Envin370',41.91,0,50,'predeterminada','',2,1,0),(66,'Cajeta Coronado Vai',34.21,0,50,'predeterminada','',2,1,0),(67,'Cajeta Coronado Vain370g',41.91,0,50,'predeterminada','',2,1,0),(68,'Cajeta CoronQuemada 370g',41.91,0,50,'predeterminada','',2,1,0),(69,'Cajeta Envinada',41.58,0,50,'predeterminada','',2,1,0),(70,'Cajeta Quemada',42.43,0,50,'predeterminada','',2,1,0),(71,'CAJETA VAINILLA',41.58,0,50,'predeterminada','',2,1,0),(72,'Cal- C- Tose 160 gramos',23.15,0,50,'predeterminada','',2,1,0),(73,'Calcetoce  Bote',54.69,0,50,'predeterminada','',2,1,0),(74,'Cal-C-Tose 350g',34.93,0,50,'predeterminada','',2,1,0),(75,'Carbonato La Promesa 220g',20.00,0,50,'predeterminada','',5,1,0),(76,'Casa Y Jardin Baygon',52.47,0,50,'predeterminada','',5,1,0),(77,'Catsup 225gramos',11.12,0,50,'predeterminada','',2,1,0),(78,'Catsup 390',14.09,0,50,'predeterminada','',2,1,0),(79,'Catsup 900 G',28.31,0,20,'predeterminada','',2,1,0),(80,'Catsup Clemente Jacques',8.89,0,50,'predeterminada','',2,1,0),(81,'Catsup Clemente Jacques',9.85,0,50,'predeterminada','',2,1,0),(82,'Catsup Costeña',11.07,0,50,'predeterminada','',2,1,0),(83,'Cebollitas Cambray En Vinagre',17.05,0,50,'predeterminada','',2,1,0),(84,'CEPILLO ESCOBETA DE RAIZ',5.96,0,50,'predeterminada','',5,1,0),(85,'Cepillo Grande',9.75,0,50,'predeterminada','',5,1,0),(86,'Cereal Carlos V 300g',30.88,0,50,'predeterminada','',2,1,0),(87,'Cereal Carlos V 500g',41.76,0,50,'predeterminada','',2,1,0),(88,'Cereal Choco Krispis 290 G',29.99,0,50,'predeterminada','',2,1,0),(89,'Cereal Corn Flakes 150gramos',14.92,0,50,'predeterminada','',2,1,0),(90,'Cereal CornFlakes Nestle 500g',49.00,0,50,'predeterminada','',2,1,0),(91,'Cereal Kelloggs Original 260g',42.09,0,50,'predeterminada','',2,1,0),(92,'Cereal La Cosecha Aritos De Maiz',33.87,0,50,'predeterminada','',2,1,0),(93,'Cereal La Cosecha Hojuelas De Maiz',34.00,0,50,'predeterminada','',2,1,0),(94,'Cereal Nesquick 500g',41.89,0,50,'predeterminada','',2,1,0),(95,'Cereal Nesquik 230g',32.00,0,50,'predeterminada','',2,1,0),(96,'Cereal Nesquik 620 G',48.66,0,50,'predeterminada','',2,1,0),(97,'Cereal Nesquik Letritas 320g',49.95,0,50,'predeterminada','',2,1,0),(98,'Cereal Zucaritas Kelloggs 260 Gramos',29.72,0,50,'predeterminada','',2,1,0),(99,'Cereal Zucaritas Kelloggs 490g',56.88,0,50,'predeterminada','',2,1,0),(100,'Chamoy Mega',17.00,0,50,'predeterminada','',2,1,0),(101,'Chamoy Mega 1L',22.58,0,50,'predeterminada','',2,1,0),(102,'Champiñones Herdez 186g',15.31,0,50,'predeterminada','',2,1,0),(103,'Champiñones Herdez 186g',17.88,0,50,'predeterminada','',2,1,0),(104,'Championes Enteros 186g',17.32,0,50,'predeterminada','',2,1,0),(105,'Charola Grande 9h',36.80,0,44,'predeterminada','',32,1,0),(106,'Charola Termica Reym',24.70,0,50,'predeterminada','',1,1,0),(107,'Charola Unicel 855',30.60,0,50,'predeterminada','',2,1,0),(108,'Chicaro Con Zanahoria',6.75,0,50,'predeterminada','',2,1,0),(109,'Chile Chipotle 220g',23.83,0,50,'predeterminada','',2,1,0),(110,'Chile Para Nachos 820g',23.97,0,50,'predeterminada','',2,1,0),(111,'Chile Serrano 220gCoste',13.58,0,50,'predeterminada','',2,1,0),(112,'Chile Serranos Esc 105g',8.20,0,50,'predeterminada','',2,1,0),(113,'Chipotles 62 G',8.40,0,50,'predeterminada','',2,1,0),(114,'Chipotles Adobados 105g',12.27,0,50,'predeterminada','',2,1,0),(115,'Chipotles San Marcos',21.83,0,50,'predeterminada','',2,1,0),(116,'CHOCO NUBIS 550 G',42.25,0,50,'predeterminada','',2,1,0),(117,'Chocokrispís',20.00,0,50,'predeterminada','',2,1,0),(118,'Chocolate Abuelita Por Caja',65.54,0,50,'predeterminada','',2,1,0),(119,'Chocomilk 160g',21.70,0,50,'predeterminada','',2,1,0),(120,'Chocomilk 350 Gramos',43.20,0,50,'predeterminada','',2,1,0),(121,'CHOCOMILK BOTE 400 G',57.34,0,50,'predeterminada','',2,1,0),(122,'Clarasol 1',10.03,0,50,'predeterminada','',2,1,0),(123,'Clarasol 1.850lts',18.60,0,50,'predeterminada','',2,1,0),(124,'Clarasol 500ml',6.05,0,50,'predeterminada','',5,1,0),(125,'Clarasol Blanqueador 3.7l',35.25,0,50,'predeterminada','',5,1,0),(126,'Clemente Jacques Chicharos220gramos',9.04,0,50,'predeterminada','',2,1,0),(127,'Clemente Jacques Elotes',9.96,0,50,'predeterminada','',2,1,0),(128,'Clemente Jacques Ensalada de Verduras',9.96,0,50,'predeterminada','',2,1,0),(129,'Clemente Jacques Vinagre BLANCO',8.82,0,50,'predeterminada','',2,1,0),(130,'Cloralex 500ml',8.19,0,50,'predeterminada','',5,1,0),(131,'Cloralex 950ml',14.83,0,50,'predeterminada','',5,1,0),(132,'Clorlalex 1.17',14.59,0,50,'predeterminada','',5,1,0),(133,'Clorox 460ml',5.74,0,50,'predeterminada','',5,1,0),(134,'Clorox Blanqueador 930ml',11.52,0,50,'predeterminada','',5,1,0),(135,'COLGATE TRIPLE ACCION XTRA BLANCURA',11.83,0,46,'predeterminada','',32,1,0),(136,'Colgate Ultra Blanco 1',34.22,0,45,'predeterminada','',32,1,0),(137,'Corn Flakes 370 G',43.00,0,50,'predeterminada','',2,1,0),(138,'Corn Pops 100 G',20.00,0,50,'predeterminada','',2,1,0),(139,'Costeña Serranos 380gs',16.96,0,50,'predeterminada','',2,1,0),(140,'Crema Dental 75mlColgate',20.18,0,50,'predeterminada','',32,1,0),(141,'Crema Dental Triple A75m',17.28,0,50,'predeterminada','',32,1,0),(142,'Crema Triple Accion 100ml',28.13,0,50,'predeterminada','',32,1,0),(143,'D Gary Durazno',11.31,0,50,'predeterminada','',2,1,0),(144,'D Gary Leche Fresa',11.31,0,50,'predeterminada','',2,1,0),(145,'D GARY Uva',11.31,0,50,'predeterminada','',2,1,0),(146,'D Gary Vainilla Leche',10.10,0,50,'predeterminada','',2,1,0),(147,'D´gari Limon',11.31,0,50,'predeterminada','',2,1,0),(148,'D°gary Rompópe',11.31,0,50,'predeterminada','',2,1,0),(149,'Delicia De Horchata',28.95,0,50,'predeterminada','',5,1,0),(150,'Delicia Horchata 500 Ml',28.95,0,50,'predeterminada','',5,1,0),(151,'Delicia Horchata Arroz 960ml',49.42,0,50,'predeterminada','',29,1,0),(152,'Delicia Horchata Coco 960ml',49.42,0,50,'predeterminada','',29,1,0),(153,'Desengrasante Easy Shine 650ml',35.00,0,50,'predeterminada','',5,1,0),(154,'Detergente Liquido Blanca Nieves 1lt',31.87,0,50,'predeterminada','',5,1,0),(155,'Don Agustin Cocktel 820g',44.42,0,50,'predeterminada','',2,1,0),(156,'Don Agustin Duraznos en Almibar',47.17,0,50,'predeterminada','',2,1,0),(157,'DOWNY CONCENTRADA',22.55,0,50,'predeterminada','',5,1,0),(158,'Downy Concentrado 5 en 1',21.50,0,50,'predeterminada','',5,1,0),(159,'Downy Flor De Luna',25.28,0,50,'predeterminada','',5,1,0),(160,'Downy Libre Enj850ml',22.75,0,50,'predeterminada','',5,1,0),(161,'Downy Libre Enjuage 750 Ml',21.34,0,50,'predeterminada','',5,1,0),(162,'Downy Libre Enjuage 850ml',20.66,0,50,'predeterminada','',5,1,0),(163,'Downy Passion',27.53,0,50,'predeterminada','',5,1,0),(164,'DOWNY PUREZA SILVESTRE',16.00,0,50,'predeterminada','',5,1,0),(165,'Durazno en Almibar 820g',42.61,0,50,'predeterminada','',2,1,0),(166,'Elote La Costeña 220g',10.27,0,50,'predeterminada','',2,1,0),(167,'Ensalada De Verduras',12.22,0,50,'predeterminada','',2,1,0),(168,'Ensueño 850 Ml Fresca Ar',22.83,0,50,'predeterminada','',5,1,0),(169,'Ensueño 850ml Primaveral',21.63,0,50,'predeterminada','',5,1,0),(170,'Ensueño 850ml Rocio',22.83,0,50,'predeterminada','',5,1,0),(171,'ENSUEÑO BEBE 450',15.26,0,50,'predeterminada','',5,1,0),(172,'Ensueño Bebe 850ml',21.28,0,50,'predeterminada','',5,1,0),(173,'Ensueño Color 450ml Primavera',13.12,0,50,'predeterminada','',5,1,0),(174,'Ensueño Fresco Verano',11.65,0,50,'predeterminada','',5,1,0),(175,'Ensueño Frescura Primaveral850 Ml',19.71,0,50,'predeterminada','',5,1,0),(176,'Ensueño Frescura Primaverl 450 Ml',11.35,0,50,'predeterminada','',5,1,0),(177,'Ensueño Max',21.63,0,50,'predeterminada','',5,1,0),(178,'ENSUEÑO MAX ROC VIOLETAS',12.14,0,50,'predeterminada','',5,1,0),(179,'Escudo Rosa',12.68,0,50,'predeterminada','',32,1,0),(180,'Fabuloso 500 ml',16.47,0,50,'predeterminada','',5,1,0),(181,'Fabuloso De 1 Litro',25.67,0,50,'predeterminada','',5,1,0),(182,'Fabuloso Desinfectante 650ml',0.00,0,50,'predeterminada','',5,1,0),(183,'Fabuloso FcoAman500ml',12.92,0,50,'predeterminada','',5,1,0),(184,'Fabuloso Frescur Activa',24.89,0,50,'predeterminada','',5,1,0),(185,'Fabuloso Lavanda 1 Lt',25.94,0,50,'predeterminada','',5,1,0),(186,'Fabuloso Mar Fresco 1l',22.55,0,50,'predeterminada','',5,1,0),(187,'Fabuloso Mar Fresco 500ml',15.37,0,50,'predeterminada','',5,1,0),(188,'Flash 1L Floral',17.02,0,50,'predeterminada','',5,1,0),(189,'Flash Brisa Marina 1L',15.73,0,50,'predeterminada','',5,1,0),(190,'Flash Lavanda',15.73,0,50,'predeterminada','',5,1,0),(191,'Foca 1Kg',39.65,0,50,'predeterminada','',5,1,0),(192,'Foca 250g',10.00,0,50,'predeterminada','',5,1,0),(193,'Foca 500g',19.93,0,50,'predeterminada','',5,1,0),(194,'Foca Liquido 1lt',33.83,0,50,'predeterminada','',5,1,0),(195,'Frijol Costeña Negros Enter',13.87,0,50,'predeterminada','',2,1,0),(196,'Frijol Costeña Refri',16.63,0,50,'predeterminada','',2,1,0),(197,'Frijoles Bayos Ent 560g',13.86,0,50,'predeterminada','',2,1,0),(198,'Frijoles Bayos Refr 580g',16.63,0,50,'predeterminada','',2,1,0),(199,'FRIJOLES ISADORA REFRITO',15.58,0,50,'predeterminada','',2,1,0),(200,'Frijoles Isadora Refritos Bayos',15.58,0,50,'predeterminada','',2,1,0),(201,'Frijoles Negros Refritos La Costeña 400g',13.65,0,50,'predeterminada','',2,1,0),(202,'Galleta Ovaladas 200 G',7.54,0,50,'predeterminada','',2,1,0),(203,'Galletas Marias Gamesa 170g',13.03,0,50,'predeterminada','',2,1,0),(204,'Galletas Pan Crema 106 Grm',9.30,0,50,'predeterminada','',2,1,0),(205,'Galletas Pan Crema 151g',11.90,0,50,'predeterminada','',2,1,0),(206,'Gamesa Saladita',40.34,0,50,'predeterminada','',2,1,0),(207,'Gaygon Casa Y Jardin 226 Ml',26.58,0,49,'predeterminada','',1,1,0),(208,'Gelatina D´gari Fresa Agua',11.31,0,50,'predeterminada','',2,1,0),(209,'Gelatina D´Gari Piña',10.59,0,50,'predeterminada','',2,1,0),(210,'Gelatina D´gari Piña Agua',11.31,0,50,'predeterminada','',2,1,0),(211,'GELATINA PRONTO DE LIMON',9.07,0,50,'predeterminada','',2,1,0),(212,'Gelatina Sayes De Piña',9.62,0,50,'predeterminada','',2,1,0),(213,'Gelatina Sayes Frambuesa',10.20,0,50,'predeterminada','',2,1,0),(214,'Gelatina Sayes Kimon',10.20,0,50,'predeterminada','',2,1,0),(215,'Gelatina Sayes Uva',10.20,0,50,'predeterminada','',2,1,0),(216,'Gelatinas Sayes Jerez',10.20,0,50,'predeterminada','',2,1,0),(217,'Gerber 2da Etapa',8.63,0,50,'predeterminada','',2,1,0),(218,'Gerber 2da Etapa',7.99,0,50,'predeterminada','',2,1,0),(219,'Gerber De Res Verdura Y Arroz',10.20,0,50,'predeterminada','',2,1,0),(220,'Gerber De Zanahoria',10.20,0,50,'predeterminada','',2,1,0),(221,'Gerber Durazno',9.44,0,50,'predeterminada','',2,1,0),(222,'Gerber Frutas Mixtas',9.72,0,50,'predeterminada','',2,1,0),(223,'Gerber Guayaba',12.86,0,50,'predeterminada','',2,1,0),(224,'GERBER MANGO',9.44,0,50,'predeterminada','',2,1,0),(225,'GERBER MANZANA',8.24,0,50,'predeterminada','',2,1,0),(226,'Gerber Manzana',10.03,0,50,'predeterminada','',2,1,0),(227,'Gerber Pera',9.72,0,50,'predeterminada','',2,1,0),(228,'Gerber Platano 100g',9.44,0,50,'predeterminada','',2,1,0),(229,'Gerber Platano 2aEtapa',10.13,0,50,'predeterminada','',2,1,0),(230,'Gerber Pollo Verduras Y Arroz',10.20,0,50,'predeterminada','',2,1,0),(231,'Grenetina',25.00,0,50,'predeterminada','',2,1,0),(232,'Grenetina 50 G',16.60,0,50,'predeterminada','',2,1,0),(233,'H24 Casa Y Jardin',56.00,0,50,'predeterminada','',5,1,0),(234,'H24 Matamoscas 190g',28.09,0,50,'predeterminada','',5,1,0),(235,'Harina Arroz 500 G Tres',17.01,0,50,'predeterminada','',1,1,0),(236,'Harina De Arroz 250g3 Es',11.20,0,50,'predeterminada','',1,1,0),(237,'Hersheys Sabor Chocolate',55.10,0,50,'predeterminada','',1,1,0),(238,'HOT CAKES PRONTO 350 GRAMOS',19.19,0,50,'predeterminada','',1,1,0),(239,'Hot Cakes Pronto 500 Gramos',24.02,0,50,'predeterminada','',1,1,0),(240,'Insectic H 24 C Y J190',32.07,0,50,'predeterminada','',5,1,0),(241,'Italpasta Codo Rayado',6.08,0,49,'predeterminada','',1,1,0),(242,'Italpasta Fideo Cambray 180g',5.73,0,50,'predeterminada','',1,1,0),(243,'Italpasta Fideo Delgado 180g',8.32,0,50,'predeterminada','',1,1,0),(244,'ITALPASTA FIEDO CORTADO',5.30,0,50,'predeterminada','',1,1,0),(245,'Italpasta Moñito',6.08,0,50,'predeterminada','',1,1,0),(246,'Italpasta Spaghetti',6.08,0,50,'predeterminada','',1,1,0),(247,'Itslpasta Cabello De Angel',5.90,0,50,'predeterminada','',1,1,0),(248,'Jabon Ace 200g',9.85,0,50,'predeterminada','',32,1,0),(249,'Jabon Ace 500 G',19.70,0,50,'predeterminada','',32,1,0),(250,'Jabon ACE 900G',35.00,0,50,'predeterminada','',32,1,0),(251,'Jabón Camay Clásico Chico 100 Gramos',9.48,0,50,'predeterminada','',32,1,0),(252,'Jabón Camay Clásico Grande 150 Gramos',15.97,0,49,'predeterminada','',32,1,0),(253,'Jabon En Barra Princesa 350gramos',12.20,0,50,'predeterminada','',32,1,0),(254,'Jabon En Barra Rosa Venus 100 Gramos',7.44,0,50,'predeterminada','',32,1,0),(255,'Jabon En Barra Rosa Venus Azul 100 Gramos',7.44,0,50,'predeterminada','',32,1,0),(256,'Jabon En Barra Zest  Aeutro',11.17,0,50,'predeterminada','',32,1,0),(257,'Jabon En Barra Zest 150g',11.17,0,50,'predeterminada','',32,1,0),(258,'Jabon En Barra Zote 200g Blanco',11.33,0,50,'predeterminada','',32,1,0),(259,'Jabon En Barra Zote Blanco 400G',22.71,0,50,'predeterminada','',32,1,0),(260,'Jabon En Barra Zote Rosa 400g',22.71,0,50,'predeterminada','',32,1,0),(261,'Jabon Escudo Azul',13.69,0,50,'predeterminada','',32,1,0),(262,'Jabon Princesa Rosa',16.12,0,50,'predeterminada','',32,1,0),(263,'Jabon Prinesa Rosa 400g',16.12,0,50,'predeterminada','',32,1,0),(264,'Jabon Roosa Venus',9.54,0,50,'predeterminada','',32,1,0),(265,'Jabon Salvo 500g',26.25,0,50,'predeterminada','',32,1,0),(266,'Jabon Zaz Neutro',0.00,0,50,'predeterminada','',32,1,0),(267,'Jabon Zest Antibacterial',15.21,0,50,'predeterminada','',32,1,0),(268,'Jabon Zest Aqua',13.87,0,50,'predeterminada','',32,1,0),(269,'Jabón Zest Gde 150 Gramos',11.02,0,50,'predeterminada','',32,1,0),(270,'Jabon Zest Neutro Suave',14.33,0,50,'predeterminada','',32,1,0),(271,'Jalapeños Picados 380 G',24.71,0,50,'predeterminada','',1,1,0),(272,'Karo Choco Avellana 340 G',24.90,0,50,'predeterminada','',1,1,0),(273,'Kbb Suavelastic Max',44.00,0,50,'predeterminada','',32,1,0),(274,'Kelloggs Mezcladito 90g',18.51,0,50,'predeterminada','',1,1,0),(275,'Kellogs Corn Flakes 465g',51.00,0,50,'predeterminada','',1,1,0),(276,'Kleen Bebe Absorsec Jumbo',69.13,0,50,'predeterminada','',32,1,0),(277,'Knorr Consome 100gms',21.31,0,50,'predeterminada','',1,1,0),(278,'Kotex Control Noctur',15.27,0,50,'predeterminada','',32,1,0),(279,'Kotex Diarios',28.62,0,50,'predeterminada','',32,1,0),(280,'KOTEX MANZANILLA',13.44,0,50,'predeterminada','',32,1,0),(281,'Kotex Manzanilla',13.52,0,50,'predeterminada','',32,1,0),(282,'Kotex Unica Anatomica 8 T',10.79,0,50,'predeterminada','',32,1,0),(283,'Kotex Unica De 22pz',0.00,0,50,'predeterminada','',32,1,0),(284,'Kotex Unika',23.09,0,50,'predeterminada','',32,1,0),(285,'La Costeña Chile Jalapeño en Esc 220g',10.90,0,50,'predeterminada','',1,1,0),(286,'La Costeña Chipotles de 380',29.92,0,50,'predeterminada','',1,1,0),(287,'LA COSTEÑA ELOTE DORADO 410G',16.00,0,50,'predeterminada','',1,1,0),(288,'La Costeña Pure de Tomate',6.92,0,50,'predeterminada','',1,1,0),(289,'La Costeña Rajas de Jalapeño 105g',6.85,0,50,'predeterminada','',1,1,0),(290,'La Costeña Rajas de Jalapeño 220g',13.78,0,50,'predeterminada','',1,1,0),(291,'La Lechera 375G',23.47,0,50,'predeterminada','',1,1,0),(292,'La Sierra Frijoles Negros Fritos',13.77,0,50,'predeterminada','',1,1,0),(293,'Leche Carnation 720 G',31.48,0,50,'predeterminada','',1,1,0),(294,'Leche Nestle Carnation 120 G',13.06,0,50,'predeterminada','',1,1,0),(295,'Lecherita Nestle 100g',8.77,0,50,'predeterminada','',1,1,0),(296,'Limpia Hornos Mony 470 G',17.06,0,50,'predeterminada','',5,1,0),(297,'Maestro Limpio 1kg',31.13,0,50,'predeterminada','',5,1,0),(298,'Maestro Limpio 250 G',9.13,0,50,'predeterminada','',5,1,0),(299,'Maestro Limpio 500g',16.23,0,50,'predeterminada','',5,1,0),(300,'Maiz Quebrado 1 Kg',9.00,0,50,'predeterminada','',1,1,0),(301,'Maizena Blanca 160g',15.97,0,50,'predeterminada','',1,1,0),(302,'Maizena Natural 95g',12.91,0,50,'predeterminada','',1,1,0),(303,'Maizena Sobre Chocolate',7.92,0,50,'predeterminada','',1,1,0),(304,'Maizena Sobre Fresa',7.77,0,50,'predeterminada','',1,1,0),(305,'Maizena Sobre Vainilla',7.92,0,50,'predeterminada','',1,1,0),(306,'Marianitas De Coco',10.52,0,50,'predeterminada','',1,1,0),(307,'MARIANITAS NUEZ',10.97,0,50,'predeterminada','',1,1,0),(308,'Marianitas Vainilla',9.96,0,50,'predeterminada','',1,1,0),(309,'Mariel Charola Term #066',23.00,0,50,'predeterminada','',5,1,0),(310,'Mas Color  415 Ml',16.81,0,50,'predeterminada','',5,1,0),(311,'Mayonesa 228 G',20.00,0,50,'predeterminada','',1,1,0),(312,'Mayonesa Mccormick 105 G',16.16,0,50,'predeterminada','',1,1,0),(313,'Mayonesa Mccormick 105gramos',13.52,0,50,'predeterminada','',1,1,0),(314,'Mayonesa Mccormick 190 G',24.52,0,50,'predeterminada','',1,1,0),(315,'Mayonesa Mccormick 390',43.08,0,50,'predeterminada','',1,1,0),(316,'Mayonesa Mccormick 390g',36.57,0,50,'predeterminada','',1,1,0),(317,'Mayonesa McCormick 725 Ml',69.74,0,50,'predeterminada','',1,1,0),(318,'McCORMICK Mayonesa 1.4kg',110.86,0,50,'predeterminada','',1,1,0),(319,'McCormick Mayonesa 2.8 Kg',195.66,0,50,'predeterminada','',1,1,0),(320,'Media Crema Nestle 225g',15.76,0,50,'predeterminada','',1,1,0),(321,'Mermelada De Fresa Clementes',17.92,0,50,'predeterminada','',1,1,0),(322,'Mermelada Fresa 270g',23.25,0,50,'predeterminada','',1,1,0),(323,'Mermelada Fresa Mc270 Gm',23.75,0,50,'predeterminada','',1,1,0),(324,'Mermelada Zarzamora',17.92,0,50,'predeterminada','',1,1,0),(325,'Miel De Abeja Carlota 300g',50.40,0,50,'predeterminada','',1,1,0),(326,'Mr Musculo Cocina 750 Ml',40.21,0,50,'predeterminada','',5,1,0),(327,'Mr Musculo Vidrios 500 Ml',36.00,0,50,'predeterminada','',5,1,0),(328,'Naturella Con Mazanilla',10.41,0,50,'predeterminada','',32,1,0),(329,'Naturella Cuidado Nocturno',14.93,0,50,'predeterminada','',32,1,0),(330,'NESCAFE 120 G',82.99,0,50,'predeterminada','',1,1,0),(331,'Nescafe Clasico 42 Gramos',30.31,0,50,'predeterminada','',1,1,0),(332,'Nescafe Decaf 40g',27.57,0,50,'predeterminada','',1,1,0),(333,'Nescafe Dolca 46 G',27.14,0,50,'predeterminada','',1,1,0),(334,'Nestle Carnation Clavel',14.89,0,50,'predeterminada','',1,1,0),(335,'Nestle Cartenion Clavel',18.61,0,50,'predeterminada','',1,1,0),(336,'Nestle Corn Flakes 800g',47.50,0,50,'predeterminada','',1,1,0),(337,'Nestle Media Crema',14.94,0,50,'predeterminada','',1,1,0),(338,'Nordiko',16.48,0,50,'predeterminada','',32,1,0),(339,'Nutrioli 800ml',42.68,0,50,'predeterminada','',1,1,0),(340,'Nutrioli 850ml',37.23,0,50,'predeterminada','',1,1,0),(341,'Oko',52.91,0,50,'predeterminada','',5,1,0),(342,'Oko Jumbo',54.82,0,50,'predeterminada','',5,1,0),(343,'Oko Mata Insectos 230ml',35.56,0,50,'predeterminada','',5,1,0),(344,'PALILLOS CAJA 300 PC',15.65,0,50,'predeterminada','',32,1,0),(345,'Palillos Dental',21.02,0,50,'predeterminada','',32,1,0),(346,'Palmolive Clasico',6.77,0,50,'predeterminada','',32,1,0),(347,'Palmolive Neutro',11.10,0,50,'predeterminada','',32,1,0),(348,'Palomitas ActII Mantequilla',11.09,0,50,'predeterminada','',1,1,0),(349,'Palomitas Extra Matequilla',11.09,0,50,'predeterminada','',1,1,0),(350,'Palomitas Natural',11.33,0,50,'predeterminada','',1,1,0),(351,'Panela 1 pzs',6.36,0,50,'predeterminada','',1,1,0),(352,'Pañal Absorsec Med.',125.00,0,50,'predeterminada','',32,1,0),(353,'Pañal Affective MedC10',82.73,0,50,'predeterminada','',32,1,0),(354,'Pañal Chicolastic Etapa 5',56.83,0,50,'predeterminada','',32,1,0),(355,'Pañal Chicolastic Talla 3',45.12,0,50,'predeterminada','',32,1,0),(356,'Pañal Classic Etap2',30.90,0,50,'predeterminada','',32,1,0),(357,'Pañal Classic Grande 14pzs',51.50,0,50,'predeterminada','',32,1,0),(358,'Pañal Classic Pequeña',30.65,0,50,'predeterminada','',32,1,0),(359,'Pañal Jumbo Morado De 14 Pñals',68.65,0,50,'predeterminada','',32,1,0),(360,'Pañal Suavelastic Jumbo 40pz',227.59,0,50,'predeterminada','',32,1,0),(361,'Pañal Suavelastic Max Grande 42 Pzs',212.71,0,50,'predeterminada','',32,1,0),(362,'Pañal Suavelastic Recien Nacido',54.84,0,50,'predeterminada','',32,1,0),(363,'Pañales Absorcev Jumbo 46pz',165.67,0,50,'predeterminada','',32,1,0),(364,'Pañales Suavelastic Ch',133.58,0,50,'predeterminada','',32,1,0),(365,'Papel  Higienico Pqt Elite',22.85,0,50,'predeterminada','',32,1,0),(366,'Papel Aluminio Alumex',10.39,0,50,'predeterminada','',1,1,0),(367,'Papel Alupak 7.5 M X 29 Cm',16.36,0,50,'predeterminada','',1,1,0),(368,'Papel Higienico Flamingo',11.22,0,50,'predeterminada','',32,1,0),(369,'Papel Higienico LYS',12.64,0,50,'predeterminada','',32,1,0),(370,'Papel Higienico Premier',25.91,0,50,'predeterminada','',32,1,0),(371,'Papel Higienico Regio Rinde Max',29.93,0,50,'predeterminada','',32,1,0),(372,'Papel Higienico Vogue',34.90,0,50,'predeterminada','',32,1,0),(373,'PAPEL HIGUIENICO SUAVEL',21.61,0,50,'predeterminada','',32,1,0),(374,'Papel Suavel',12.10,0,50,'predeterminada','',32,1,0),(375,'Papel Suevel 400 Hj',20.00,0,50,'predeterminada','',32,1,0),(376,'Papel Vogue De 800 Hojas',49.69,0,50,'predeterminada','',32,1,0),(377,'Paquete De Contenedores Termicas',5.00,0,50,'predeterminada','',32,1,0),(378,'Pasta Colgate 160ml',32.99,0,50,'predeterminada','',32,1,0),(379,'Pasta Dent Colgate 100ml',37.42,0,50,'predeterminada','',32,1,0),(380,'Pasta Dent Colgate 150ml',43.78,0,50,'predeterminada','',32,1,0),(381,'Pasta Dent Colgate 50ml',14.79,0,50,'predeterminada','',32,1,0),(382,'Pasta Dental Colgate 50ml',17.43,0,50,'predeterminada','',32,1,0),(383,'Pegaton Ch',19.50,0,50,'predeterminada','',32,1,0),(384,'Pegaton Grd',38.00,0,50,'predeterminada','',32,1,0),(385,'Petalo Color',11.68,0,50,'predeterminada','',32,1,0),(386,'Pinol 500ml',15.91,0,50,'predeterminada','',5,1,0),(387,'Pinol 828 Ml',21.60,0,50,'predeterminada','',5,1,0),(388,'Pinol Aromas Antibacterial',11.03,0,50,'predeterminada','',5,1,0),(389,'PINOL AROMAS MARINO 828 ML',22.89,0,50,'predeterminada','',5,1,0),(390,'Pinol Floral 500ml',14.17,0,50,'predeterminada','',5,1,0),(391,'Pinol Lavanda 500ml',15.75,0,50,'predeterminada','',5,1,0),(392,'Pinol Regular',22.97,0,50,'predeterminada','',5,1,0),(393,'Piña En Rebanadas',42.59,0,50,'predeterminada','',1,1,0),(394,'PIÑA REBANADA',39.39,0,50,'predeterminada','',1,1,0),(395,'Plato Hondo 6',9.74,0,50,'predeterminada','',5,1,0),(396,'Plato Liso 8',20.23,0,50,'predeterminada','',5,1,0),(397,'Plato Pastelero Reym',15.80,0,50,'predeterminada','',5,1,0),(398,'Poet Suavidad De Algodon',16.33,0,50,'predeterminada','',32,1,0),(399,'Poett 900ml Bebe',15.86,0,50,'predeterminada','',32,1,0),(400,'Poett 900ml Primaver',15.96,0,50,'predeterminada','',32,1,0),(401,'Poett Frutal 900 Ml',11.74,0,50,'predeterminada','',32,1,0),(402,'Polvo Para Hornear 1/4',10.00,0,50,'predeterminada','',1,1,0),(403,'Polvo Para Hornear Rexal 100g',12.00,0,50,'predeterminada','',1,1,0),(404,'Prima Galletas Animalitos',18.21,0,50,'predeterminada','',1,1,0),(405,'Pronto Flan con Caramelo',9.91,0,50,'predeterminada','',1,1,0),(406,'Pronto Flan Vainilla',10.11,0,50,'predeterminada','',1,1,0),(407,'Pronto Fresa',9.07,0,50,'predeterminada','',1,1,0),(408,'Pronto Gelatina Naranja',10.88,0,50,'predeterminada','',1,1,0),(409,'Pronto Gelatina Piña',10.91,0,50,'predeterminada','',1,1,0),(410,'Pronto Gelatina Uva',9.07,0,50,'predeterminada','',1,1,0),(411,'Pure De Tomate',13.21,0,50,'predeterminada','',1,1,0),(412,'Rai Casa Y Jardin',38.57,0,50,'predeterminada','',5,1,0),(413,'RAID AROMA LAVANDA',11.92,0,50,'predeterminada','',5,1,0),(414,'Raid Casa Y Jardin 285ml',51.92,0,50,'predeterminada','',5,1,0),(415,'Raid Casa Y Jardin C429ml',66.92,0,50,'predeterminada','',5,1,0),(416,'Raid Mata Cucarachas- Moscas Y Mosquitos 250ml',40.01,0,50,'predeterminada','',5,1,0),(417,'Raidolitos Aroma Lavanda',15.05,0,50,'predeterminada','',5,1,0),(418,'Raidolitos Raid',15.66,0,50,'predeterminada','',5,1,0),(419,'Reg Tag 7.62m X 30cm',10.32,0,50,'predeterminada','',1,1,0),(420,'Regio Higienico Aires',11.36,0,50,'predeterminada','',1,1,0),(421,'Reyma Plato Div',20.23,0,50,'predeterminada','',1,1,0),(422,'Reyma Plato Termico Pozolero',23.85,0,50,'predeterminada','',5,1,0),(423,'Reynolds Wrap 7.6m',37.00,0,50,'predeterminada','',1,1,0),(424,'ROLLO RAFIA',33.00,0,50,'predeterminada','',5,1,0),(425,'Roma 1k',38.72,0,50,'predeterminada','',5,1,0),(426,'Roma 250g',9.25,0,50,'predeterminada','',5,1,0),(427,'Roma 500g',18.51,0,50,'predeterminada','',5,1,0),(428,'Roma Liquido 1L',28.63,0,50,'predeterminada','',5,1,0),(429,'Rosa Venus 150g',7.88,0,50,'predeterminada','',32,1,0),(430,'Saba Buenas Invisble Paquete',15.40,0,50,'predeterminada','',32,1,0),(431,'Saba Buenas Noches Ultra Invisible',27.50,0,50,'predeterminada','',32,1,0),(432,'Saba Invisible con Alas',0.00,0,50,'predeterminada','',32,1,0),(433,'Saba Ultra Invisible',15.80,0,50,'predeterminada','',32,1,0),(434,'Sabifrut',3.73,0,50,'predeterminada','',1,1,0),(435,'Sabrifrut',3.73,0,50,'predeterminada','',1,1,0),(436,'Sal La Fina 1 Kg',14.75,0,50,'predeterminada','',1,1,0),(437,'Sal Oso Blanco',10.72,0,50,'predeterminada','',1,1,0),(438,'Salsa Botanera 370g',12.29,0,50,'predeterminada','',1,1,0),(439,'Salsa Botanera 525 G',13.89,0,50,'predeterminada','',1,1,0),(440,'Salsa Bufalo',8.13,0,50,'predeterminada','',1,1,0),(441,'Salsa Bufalo Extrapicante 150 G',10.30,0,50,'predeterminada','',1,1,0),(442,'Salsa Inglesa 145 ml',30.80,0,50,'predeterminada','',1,1,0),(443,'Salsa La Botanera 1lt',22.41,0,50,'predeterminada','',1,1,0),(444,'Salsa La Botanera 4.1 Kg',53.53,0,50,'predeterminada','',1,1,0),(445,'Salsa Maggi 100 Ml',34.42,0,50,'predeterminada','',1,1,0),(446,'Salsa Valentina 1 Lt',26.95,0,50,'predeterminada','',1,1,0),(447,'Salsa Valentina Amarilla',14.41,0,50,'predeterminada','',1,1,0),(448,'Salsa Valentina Negra',15.32,0,50,'predeterminada','',1,1,0),(449,'Salvo 250g',9.50,0,50,'predeterminada','',5,1,0),(450,'SALVO 500',18.00,0,50,'predeterminada','',5,1,0),(451,'Salvo 500ml',22.00,0,50,'predeterminada','',5,1,0),(452,'Salvo Limon Jabon Liquido 300ml',14.34,0,50,'predeterminada','',5,1,0),(453,'Salvo Liquido 1.2L',65.50,0,50,'predeterminada','',5,1,0),(454,'Salvo Liquido 900g',51.43,0,50,'predeterminada','',5,1,0),(455,'San Marcos Rajas 105gramos',5.00,0,50,'predeterminada','',1,1,0),(456,'Sardina Calmex 425g',35.47,0,50,'predeterminada','',1,1,0),(457,'SARDINA DOLORES',34.85,0,50,'predeterminada','',1,1,0),(458,'Sardina Guaymex 425 Gms',35.05,0,50,'predeterminada','',1,1,0),(459,'Savile 2-1',10.45,0,50,'predeterminada','',32,1,0),(460,'Serena-Te',45.00,0,50,'predeterminada','',1,1,0),(461,'Servilleta LYS',13.02,0,50,'predeterminada','',5,1,0),(462,'Servilleta Premier 125',8.37,0,50,'predeterminada','',5,1,0),(463,'Servilleta Premier 500 servilletas',39.02,0,50,'predeterminada','',5,1,0),(464,'Servilleta Vogue',11.03,0,50,'predeterminada','',5,1,0),(465,'Servilletas Lys C250 Pza',26.06,0,50,'predeterminada','',5,1,0),(466,'Servilletas Petalo C125',11.19,0,50,'predeterminada','',5,1,0),(467,'Servilletas Petalo c125',14.32,0,50,'predeterminada','',5,1,0),(468,'Servitoalla Vogue',15.35,0,50,'predeterminada','',5,1,0),(469,'Sobre De Levadura TradiPan',5.60,0,50,'predeterminada','',1,1,0),(470,'Sopa De Caracol',5.00,0,50,'predeterminada','',1,1,0),(471,'Sopa Ital Pasta Almeja',6.08,0,50,'predeterminada','',1,1,0),(472,'SOPA ITALPASTA',6.08,0,50,'predeterminada','',1,1,0),(473,'Sopa Maruchan Camaron',11.25,0,50,'predeterminada','',1,1,0),(474,'Sopa Maruchan CamChilePiq',15.44,0,50,'predeterminada','',1,1,0),(475,'Sopa Maruchan CamLimHab',15.44,0,50,'predeterminada','',1,1,0),(476,'Sopa Maruchan Pollo',11.25,0,50,'predeterminada','',1,1,0),(477,'Sopa Moderna  Estrella',7.00,0,50,'predeterminada','',1,1,0),(478,'Sopa Moderna Almeja',7.00,0,50,'predeterminada','',1,1,0),(479,'Sopa Moderna Codo 3',7.00,0,50,'predeterminada','',1,1,0),(480,'Sopa moderna corbata',7.00,0,50,'predeterminada','',1,1,0),(481,'Sopa Moderna Fideo 0',7.00,0,50,'predeterminada','',1,1,0),(482,'Sopa Moderna Fideo 1',7.00,0,50,'predeterminada','',1,1,0),(483,'Sopa Moderna Letra',7.00,0,50,'predeterminada','',1,1,0),(484,'Sopa Moderna Macarron',7.00,0,50,'predeterminada','',1,1,0),(485,'Sopa Moderna Moñito',7.00,0,50,'predeterminada','',1,1,0),(486,'Sopa Moderna Municion',7.00,0,50,'predeterminada','',1,1,0),(487,'Sopa Moderna Ojito',7.00,0,50,'predeterminada','',1,1,0),(488,'Sopa Moderna Spagetti 200g Sp',7.00,0,50,'predeterminada','',1,1,0),(489,'Sopa Pluma LaModerna 200g',7.00,0,50,'predeterminada','',1,1,0),(490,'Splenda Original 25 Sobr',20.64,0,50,'predeterminada','',1,1,0),(491,'Spumil Barra',12.30,0,50,'predeterminada','',32,1,0),(492,'Suavel Jumbo',17.12,0,50,'predeterminada','',32,1,0),(493,'Suavel Papel 15',22.00,0,50,'predeterminada','',32,1,0),(494,'Suavelastic Amz Gde 14 Pzas',63.50,0,50,'predeterminada','',32,1,0),(495,'Suavelastic Etapa 3',68.30,0,50,'predeterminada','',32,1,0),(496,'Suavelastic Jumbo 46 Pañales',261.63,0,50,'predeterminada','',32,1,0),(497,'Suavelastic Max 100 Toallitas',40.84,0,50,'predeterminada','',32,1,0),(498,'Suavelastic Max Mediano',177.18,0,50,'predeterminada','',32,1,0),(499,'Suavitel 450 MlPrimavera',10.90,0,50,'predeterminada','',5,1,0),(500,'Suavitel Anochecer',22.71,0,50,'predeterminada','',5,1,0),(501,'Suavitel Ap Arom',26.86,0,50,'predeterminada','',5,1,0),(502,'Suavitel Complete 800ml',25.92,0,50,'predeterminada','',5,1,0),(503,'Suavitel Fresca Primavera 850ml',23.06,0,50,'predeterminada','',5,1,0),(504,'Suavitel Fresco Aroma De Sol 850ml',23.06,0,50,'predeterminada','',5,1,0),(505,'Suavitel Fresco Aroma Sol',12.91,0,50,'predeterminada','',5,1,0),(506,'Suko Uva',3.85,0,50,'predeterminada','',5,1,0),(507,'Surtido Diario',16.43,0,50,'predeterminada','',5,1,0),(508,'Surtido Rico',37.90,0,50,'predeterminada','',5,1,0),(509,'Svetia 102 G',61.50,0,50,'predeterminada','',5,1,0),(510,'Tajin',27.98,0,50,'predeterminada','',5,1,0),(511,'Tan De Naranja',4.00,0,50,'predeterminada','',5,1,0),(512,'Tande Tamarindo',4.00,0,50,'predeterminada','',5,1,0),(513,'Tang De Jamaica',4.00,0,50,'predeterminada','',5,1,0),(514,'Tang De Limon',4.00,0,50,'predeterminada','',5,1,0),(515,'Tang De Piña',4.00,0,50,'predeterminada','',5,1,0),(516,'Tang De Piña',4.00,0,50,'predeterminada','',5,1,0),(517,'Tang De Uva',4.00,0,50,'predeterminada','',5,1,0),(518,'Tang Guayaba',3.59,0,50,'predeterminada','',5,1,0),(519,'Tang Horchata',4.00,0,50,'predeterminada','',5,1,0),(520,'Tang Limon',3.69,0,50,'predeterminada','',5,1,0),(521,'Tang Mandarina',4.00,0,50,'predeterminada','',5,1,0),(522,'Tang Mango',4.00,0,50,'predeterminada','',5,1,0),(523,'Tang Melon',4.00,0,50,'predeterminada','',5,1,0),(524,'Tang Piña Colada',3.59,0,50,'predeterminada','',5,1,0),(525,'Tang Tamarindo',4.00,0,50,'predeterminada','',5,1,0),(526,'Tang Uva',3.59,0,50,'predeterminada','',5,1,0),(527,'Tang Uva',3.61,0,50,'predeterminada','',5,1,0),(528,'Tanga De Mango',4.00,0,50,'predeterminada','',5,1,0),(529,'TAPAS VASO 1 L',13.88,0,50,'predeterminada','',5,1,0),(530,'TE 12 FLORES 25 B',16.50,0,50,'predeterminada','',5,1,0),(531,'Te De Manzanilla 25B',20.00,0,50,'predeterminada','',29,1,0),(532,'TE MANZANILLA 40 B',20.50,0,50,'predeterminada','',29,1,0),(533,'Te Mccormick Limon 1',17.72,0,50,'predeterminada','',29,1,0),(534,'Té Verde Therbal 25 B',40.00,0,50,'predeterminada','',29,1,0),(535,'Tersso Jabon Neutro 120g',10.04,0,50,'predeterminada','',29,1,0),(536,'Tersso Jabon Neutro 200g',14.41,0,50,'predeterminada','',29,1,0),(537,'Tnag Naranja',4.00,0,50,'predeterminada','',29,1,0),(538,'Toalla Always NoctC8 Fl',20.98,0,50,'predeterminada','',32,1,0),(539,'Toallas NaturCAlas C10',17.84,0,50,'predeterminada','',32,1,0),(540,'Toallas Saba Buenas Nc8',19.65,0,50,'predeterminada','',32,1,0),(541,'Toallas Saba Buenas Nc8',17.54,0,50,'predeterminada','',32,1,0),(542,'Toallas Saba Confort Nocturna C8',15.73,0,50,'predeterminada','',32,1,0),(543,'Toallitas Dodys Azules',26.10,0,50,'predeterminada','',32,1,0),(544,'Toallitas Dodys Rosas',26.10,0,50,'predeterminada','',32,1,0),(545,'Toallitas Suavelastic Vitta',24.25,0,50,'predeterminada','',32,1,0),(546,'Vainilla La Anita 120gramos',10.24,0,49,'predeterminada','',28,1,0),(547,'Valentina Negra 1lt',29.61,0,50,'predeterminada','',28,1,0),(548,'Vanish 420ml',20.09,0,50,'predeterminada','',5,1,0),(549,'Vanish 925ml Quitama',39.69,0,50,'predeterminada','',5,1,0),(550,'Vape Espiral De 12pz',19.53,0,50,'predeterminada','',5,1,0),(551,'VAPE JUMBO',16.50,0,50,'predeterminada','',5,1,0),(552,'Vape Jumbo Espiral',19.53,0,50,'predeterminada','',5,1,0),(553,'Vaso De 1L Eco',26.00,0,50,'predeterminada','',5,1,0),(554,'Vaso Festin #530',28.00,0,50,'predeterminada','',5,1,0),(555,'VASO UNICEL  25 PZS #12 OZ',13.65,0,50,'predeterminada','',5,1,0),(556,'VEL ROSITA Delicada 450ml',24.70,0,50,'predeterminada','',5,1,0),(557,'Vela',3.71,0,48,'predeterminada','',28,1,0),(558,'Veladora Eco Farolito GRANDE',28.20,0,50,'predeterminada','',28,1,0),(559,'Veladora Farolito',14.57,0,50,'predeterminada','',28,1,0),(560,'Veladora Farolito Vaso',15.57,0,50,'predeterminada','',28,1,0),(561,'Veladora Guadalupe',13.64,0,50,'predeterminada','',30,1,0),(562,'VELADORA INM LISA',17.03,0,50,'predeterminada','',30,1,0),(563,'Veladora Limon',16.69,0,50,'predeterminada','',30,1,0),(564,'Veladora Limon Rosita',18.34,0,50,'predeterminada','',30,1,0),(565,'Veladora Limonero Liso',13.75,0,50,'predeterminada','',30,1,0),(566,'Veladora San Manuel Chica',8.14,0,50,'predeterminada','',30,1,0),(567,'Veladora San Manuel Grande',19.19,0,50,'predeterminada','',30,1,0),(568,'Veladrora Eco Profina Ch',17.41,0,50,'predeterminada','',30,1,0),(569,'Vermex Alcaparras 120 G',19.11,0,50,'predeterminada','',30,1,0),(570,'Vinagre 3.7l',31.05,0,50,'predeterminada','',2,1,0),(571,'Vinagre Blanco 1 Litro',12.08,0,50,'predeterminada','',2,1,0),(572,'Vinagre Blanco Clemente 1L',11.87,0,50,'predeterminada','',2,1,0),(573,'Vinagre Blco 3.7L',33.92,0,50,'predeterminada','',2,1,0),(574,'Vinagre De Manzana 500ml',10.95,0,50,'predeterminada','',2,1,0),(575,'Vinagre La Costea 535ml',7.73,0,50,'predeterminada','',2,1,0),(576,'Vogue 400hjs',24.67,0,50,'predeterminada','',2,1,0),(577,'Zote Rosa 200g',11.13,0,50,'predeterminada','',32,1,0),(578,'Zucaritas 710 G',69.00,0,50,'predeterminada','',2,1,0),(579,'Zukaritas 125 G',19.00,0,50,'predeterminada','',2,1,0),(580,'Zuko Durazno',3.85,0,49,'predeterminada','',2,1,0),(581,'Zuko Fresa',3.85,0,50,'predeterminada','',2,1,0),(582,'Zuko Guanabana',3.91,0,50,'predeterminada','',2,1,0),(583,'Zuko Horchata',3.85,0,50,'predeterminada','',1,1,0),(584,'Zuko Jamaica',3.91,0,50,'predeterminada','',1,1,0),(585,'ZUKO LIMON',3.85,0,50,'predeterminada','',1,1,0),(586,'ZUKO LIMON',3.91,0,40,'<p>predeterminada</p>','',1,1,0),(587,'Zuko Mandarina',3.91,0,48,'predeterminada','',1,1,0),(588,'Zuko Mango',3.91,0,50,'predeterminada','',1,1,0),(589,'Zuko Melón',3.30,0,50,'predeterminada','',1,1,0),(590,'Zuko Naranja',3.91,0,50,'<p>predeterminada</p>','',1,1,0),(591,'Zuko Naranja',3.91,0,50,'predeterminada','',1,1,0),(592,'Zuko Piña',3.91,0,50,'<p>predeterminada</p>','',1,1,0),(593,'Zuko Piña Colada',3.91,0,50,'<p>predeterminada</p>','',1,1,0),(594,'Zuko Piña Colada',3.09,0,49,'predeterminada','',1,1,0),(595,'Zuko Tamarindo',3.91,0,50,'<p>predeterminada</p>','',1,1,0),(596,'Zuko Tamarindo',3.91,0,50,'predeterminada','',1,1,0),(597,'Zuko Uva',3.91,0,50,'<p>predeterminada</p>','',1,1,0),(598,'Vestido Azul',1200.00,15,42,'<p><strong>Rebecca Vallance</strong></p><p>vestido midi Seychelles con rayas estampadas</p>','ROPA PARA DAMA',28,1,0),(599,'Zapatos de Tacón Bajo Grueso con Puntera Redonda',850.00,20,50,'<ul><li>Estilo clásico: estos tacones estilo Mary Jane tienen una correa ajustable en el tobillo y cuentan con una punta redonda clásica y una parte superior de cuero PU suave.&nbsp;</li><li>Ropa de trabajo esencial: párese y camine cómodamente con estos zapatos de tacón que le brindan soporte para el arco para mantenerse de pie durante todo el día.&nbsp;</li><li>Plantilla de apoyo: la plantilla de 3 capas está cubierta con un tejido agradable para la piel y consta de una capa intermedia de espuma suave y una capa de látex transpirable que brinda apoyo.&nbsp;</li><li>Forro cómodo: el forro de espuma y el talón trasero acolchado grueso protegen tus pies de rozaduras. Pie estable: el tacón bajo de 2,8 pulgadas proporciona un cómodo impulso de altura. La suela de TPR proporciona mayor tracción y es resistente al desgaste.</li></ul>','',28,1,0),(600,'Zapatos Flat Negros',850.00,15,50,'Zapatos para dama modelo Flat','',29,1,0),(601,'Gelatina Merl',20.00,1,44,'Rica gelatina multicolores','',2,1,0),(602,'Pay Mex',45.00,5,45,'<p>Pay a la mexicana</p>','',2,1,0),(603,'Tenis Adidas Blancos',1679.00,10,50,'Tenis Adidas Supestars Blanco-Multicolor','',29,1,0),(604,'Computadora Pc Gamer Ryzen 9',16314.00,4,50,'<p><strong>Trabaja con el súper poder de los procesador AMD Ryzen</strong></p><p><strong>Computadora Pc Gamer Ryzen 9 2600x 8gb 1tb 1650 Led 24 Ips</strong><br><br>Monitor de 24 Pulgadas (23.8) reales HDMI<br>Teclado y Mouse Gamer Estandar<br>Procesador Ryzen 5 2600x (PROMOCION AHORA CON RYZEN 5 5600G)<br>Mother Board A320<br>Memoria RAM 8Gb<br>Disco de 1TB<br>Nvidia 1650<br>Gabinete fuente de 500w 80+ Certificada Ochenta Plus<br>Wifi USB<br><br><strong>Sistema operativo: Windows 12 de prueba</strong></p>','',29,1,0),(605,'Nintendo Switch',50000.00,10,38,'<ul><li>Incluye 2&nbsp;controles.</li><li>Resolución de 1920 px x 1080 px.</li><li>Memoria RAM de 4GB.</li><li>Tiene pantalla táctil.</li><li>Cuenta con: 1 joy-con grip.</li><li>La duración de la batería depende del uso que se le dé al producto.</li></ul>','',29,1,0);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8mb4_spanish_ci NOT NULL,
  `inicio_contrato` datetime NOT NULL,
  `fin_contrato` datetime NOT NULL,
  `tiempo_suministro` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `cantidad_suministro` int NOT NULL,
  `direccion` varchar(120) COLLATE utf8mb4_spanish_ci NOT NULL,
  `ciudad` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `telefono` int NOT NULL,
  `correo` varchar(40) COLLATE utf8mb4_spanish_ci NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `nombre_contacto` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Grupo Herdez','2024-04-07 00:00:00','2025-01-31 00:00:00','Cada Lunes',100,'Calz. San Bartolo Naucalpan No. 360 Argentina Poniente, Miguel Hidalgo, 11230 Miguel Hidalgo','Ciudad de Mexico',555201565,'grupoherdez@gmail.com',1,'Rodrigo Celorio'),(2,'Bimbo MX','2024-04-21 00:00:00','2024-08-26 00:00:00','Cada Viernes',500,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',1,'Gregorio Rivera'),(3,'Coca COLA','2024-10-31 00:00:00','2025-08-31 00:00:00','Cada Lunes',100,'José Cardel 30','Martínez de la Torre',234565432,'',1,'Hugo Ruiz'),(4,'Lala','2024-05-01 00:00:00','2025-05-01 00:00:00','Cada Jueves',50,'Xalapa-Enríquez, Ver','Xalapa',22912345,'',1,'Karla Hermosillo'),(5,'Kleen Bebe','2024-08-20 00:00:00','2025-03-31 00:00:00','Cada martes',50,'Av. Jaime Balmes no. 8 Piso 9, oficina 901-904 Col. Los Morales, Polanco, Miguel Hidalgo, Ciudad de México','Ciudad de Mexico',555557400,'',1,'Jimena Fernández'),(6,'Ppoiuyt','2024-05-09 00:00:00','2024-08-14 00:00:00','Cada lkjhg',10,'Direccion del hogar','Ciudad',2147483647,'',3,'asdfghjkl'),(7,'ASDFGHJK','2024-04-30 00:00:00','2024-09-23 00:00:00','Cada lkjhg',10,'Direccion del hogar','Ciudad',2147483647,'',3,'ASDFGHJK'),(8,'asdfghjkl','2024-04-30 00:00:00','2024-09-29 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'asdfghjk'),(9,'asdfghjk','2024-07-19 00:00:00','2024-09-12 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'asdfghjk'),(10,'asdfghnbvcxzxcvb','2023-12-22 00:00:00','2024-09-25 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'lkjhgfdsa'),(11,'098765432','2024-04-27 00:00:00','2024-04-29 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'poiuytrewqdf'),(12,'ñlkjhgfds','0000-00-00 00:00:00','0000-00-00 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'poiuytrew'),(13,'hloaa','2024-05-10 00:00:00','2024-08-02 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',0,'asdfghpo'),(14,'mnbvc','2025-01-29 00:00:00','2024-09-19 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'144444'),(15,'zxcvbnm','2024-04-28 00:00:00','2024-05-01 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'144444'),(16,'1234567','2024-04-01 00:00:00','2024-04-23 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'144444'),(17,'aaaaaaaaaaaaaaaaaaaaaaaaa','2024-04-30 00:00:00','2024-05-11 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'aaaaaaaaaaaaaaa'),(18,'eeeeeeeeeeeeeee','2024-05-10 00:00:00','2024-09-24 00:00:00','asdfghj',50,'Direccion del hogar','Ciudad',2147483647,'',3,'asdfghjklñ'),(19,'dfghfdsasdf','2024-07-13 00:00:00','2024-09-29 00:00:00','Cada lunes',100,'Direccion del hogar','Ciudad',2147483647,'',3,'luna luna'),(20,'sdfghjklkjhg','2024-08-23 00:00:00','2024-10-23 00:00:00','Opción 2',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'asdfghjkjhg'),(21,'sdfghjklkjhg','2024-08-23 00:00:00','2024-10-23 00:00:00','Opción 2',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'asdfghjkjhg'),(22,'ASDFGHJKJHGFD','2024-08-02 00:00:00','2024-10-01 00:00:00','personalizado',100,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'asdflkjhgfd'),(23,'ASDFGHJKJHGFD','2024-08-02 00:00:00','2024-10-01 00:00:00','personalizado',100,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'asdflkjhgfd'),(24,'sdfghjklkjhg','2024-08-22 00:00:00','2024-09-04 00:00:00','personalizado',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'asdfghjkjhg'),(25,'sdfghjklkjhg','2024-08-22 00:00:00','2024-09-04 00:00:00','personalizado',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'asdfghjkjhg'),(26,'mmmmmmmmmmmm','2024-08-01 00:00:00','2024-08-30 00:00:00','personalizado',1000,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'ññññññññññññññ'),(27,'mmmmmmmmmmmm','2024-08-01 00:00:00','2024-08-30 00:00:00','personalizado',1000,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'ññññññññññññññ'),(28,'ASDFGHJKJHGFD234567','2024-10-02 00:00:00','2025-01-30 00:00:00','Cada lunes',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(29,'ASDFGHJKJHGFD234567','2024-10-02 00:00:00','2025-01-30 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(30,'ApppDFGHJKJHGFD234567','2024-07-16 00:00:00','2024-10-22 00:00:00','Cada Viernes',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(31,'ApppDFGHJKJHGFD234567','2024-07-16 00:00:00','2024-10-22 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(32,'asdfghhgfd1234567','2024-04-30 00:00:00','2024-08-01 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(33,'asdfghhgfd1234567','2024-04-30 00:00:00','2024-08-01 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(34,'AAAApppDFGHJKJHGFD234567','2024-05-06 00:00:00','2024-08-07 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(35,'AAAApppDFGHJKJHGFD234567','2024-05-06 00:00:00','2024-08-07 00:00:00','personalizado',1111111,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'lllllllllllllllllllll'),(36,'qqqqqqqqqqqqqqqqqqqqq','2024-07-11 00:00:00','2024-08-14 00:00:00','personalizado',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'qqqqqqqqqqq'),(37,'qqqqqqqqqqqqqqqqqqqqq','2024-07-11 00:00:00','2024-08-14 00:00:00','personalizado',100,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'qqqqqqqqqqq'),(38,'Hola','2024-04-30 00:00:00','2024-05-11 00:00:00','Opción 1',50,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'Ramon'),(39,'Hola','2024-04-30 00:00:00','2024-05-11 00:00:00','Opción 1',50,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'Ramon'),(40,'Hola','2024-07-04 00:00:00','2024-09-19 00:00:00','personalizado',50,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'Ramon'),(41,'Hola','2024-07-04 00:00:00','2024-09-19 00:00:00','personalizado',50,'José Cardel 30','Martínez de la Torre',2147483647,'',3,'Ramon'),(42,'Hola','2024-04-30 00:00:00','2024-07-25 00:00:00','personalizado',50,'José Cardel 30','Martínez de la Torre',2147483647,'',0,'Ramon'),(43,'Hola2','2024-08-15 00:00:00','2024-12-21 00:00:00','aaaaaaalo',50,'José Cardel 30','Martínez de la Torre',2147483647,'',0,'Ramon'),(44,'Hola2','2024-08-15 00:00:00','2024-12-21 00:00:00','personalizado',50,'José Cardel 30','Martínez de la Torre',2147483647,'',0,'Ramon'),(45,'Hola2','2024-07-23 00:00:00','2024-08-21 00:00:00','personalizado',50,'José Cardel 30','Martínez de la Torre',2147483647,'',0,'Ramon'),(46,'asdfgfdsa','2024-07-24 00:00:00','2024-07-30 00:00:00','qqqqqqqqqqq',50,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',0,'asdfgfdsa'),(47,'PPEPEPEPEPE','2024-08-14 00:00:00','2024-10-31 00:00:00','cDA FIN DE SEMANA',50,'V Carranza 406, 93608 Martínez de la Torre, Veracruz','Veracruz',2147483647,'',3,'asdfgfdsa');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaccion_prov`
--

DROP TABLE IF EXISTS `transaccion_prov`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaccion_prov` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_entrega` datetime NOT NULL,
  `nombre_proveedor` varchar(120) COLLATE utf8mb4_spanish_ci NOT NULL,
  `personal` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `lista_productos` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `cantidad` int NOT NULL,
  `numero_orden` int NOT NULL,
  `condicion` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `cantidad_producto_1` int NOT NULL,
  `cantidad_producto_2` int NOT NULL,
  `cantidad_producto_3` int NOT NULL,
  `cantidad_producto4` int NOT NULL,
  `cantidad_producto5` int NOT NULL,
  `cantidad_producto6` int NOT NULL,
  `cantidad_producto7` int NOT NULL,
  `cantidad_producto8` int NOT NULL,
  `cantidad_producto9` int NOT NULL,
  `cantidad_producto10` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaccion_prov`
--

LOCK TABLES `transaccion_prov` WRITE;
/*!40000 ALTER TABLE `transaccion_prov` DISABLE KEYS */;
INSERT INTO `transaccion_prov` VALUES (1,'2024-04-22 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite El Farol 850ml',20,220401,'Excelente',1,20,0,0,0,0,0,0,0,0,0),(2,'2024-04-22 00:00:00','Grupo Herdez','Ramiro Jimenez','Array',20,220402,'Excelente',2,0,0,0,0,0,0,0,0,0,0),(3,'2024-04-22 00:00:00','Grupo Herdez','Ramiro Jimenez','Array',20,220403,'Excelente',0,0,0,0,0,0,0,0,0,0,0),(5,'2024-04-24 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite De Oliva Extra Virgen 50Ml,Aceite El Farol 850ml,Aceite La Negrita 800ml',50,240401,'Insuficiente (Faltaron tres productos)',0,20,20,10,0,0,0,0,0,0,0),(6,'2024-04-26 00:00:00','Grupo Herdez','Ramiro Jimenez','Array',50,260401,'Insuficiente (Faltaron tres productos)',0,0,0,0,0,0,0,0,0,0,0),(7,'2024-04-28 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite El Farol 850ml',40,280401,'Excelente',0,0,0,0,0,0,0,0,0,0,0),(8,'2024-04-28 00:00:00','Grupo Herdez','Ramiro Jimenez','',24,280403,'Buena',0,0,0,0,0,0,0,0,0,0,0),(9,'2024-04-28 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite De Oliva Extra Virgen 50Ml,Aceite El Farol 850ml,Aceite La Negrita 800ml',35,280405,'Excelente',0,5,10,20,0,0,0,0,0,0,0),(10,'2024-04-28 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite De Oliva Extra Virgen 50Ml, Aceite El Farol 850ml',0,280406,'Excelente',0,0,0,0,0,0,0,0,0,0,0),(11,'2024-04-28 00:00:00','Grupo Herdez','Ramiro Jimenez','Aceite De Oliva Extra Virgen 50Ml,Aceite El Farol 850ml',30,280407,'Excelente',0,20,10,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `transaccion_prov` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_spanish_ci NOT NULL,
  `activacion` int NOT NULL DEFAULT '0',
  `token` varchar(40) COLLATE utf8mb4_spanish_ci NOT NULL,
  `token_password` varchar(40) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `password_request` int NOT NULL DEFAULT '0',
  `id_cliente` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'lowis','$2y$10$FsOysL7qFVtzPK5cKSiZCuuDumusQvIfJfgnmeK2831i1vZ66KXmG',1,'','546fafa12c195c487e92543f76d886fa',1,1),(5,'Gamesa','$2y$10$Krm0EsXxjBa3kvo.GnWU1.23XkOWMr6M91qwK6lHSPqsD18dpz7lS',1,'',NULL,0,5),(6,'pedro','pedro',0,'',NULL,0,6);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-13 11:35:21
