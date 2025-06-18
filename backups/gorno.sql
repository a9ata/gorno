-- MySQL dump 10.13  Distrib 9.2.0, for Win64 (x86_64)
--
-- Host: localhost    Database: gorno
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Current Database: `gorno`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `gorno` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `gorno`;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `stylist_id` int DEFAULT NULL,
  `type` enum('консультация','примерка на дому','индивидуальный заказ') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `address` text,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_bookings_user` (`user_id`),
  KEY `fk_bookings_stylist` (`stylist_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`stylist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bookings_stylist` FOREIGN KEY (`stylist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,3,8,'консультация','2025-05-08','12:00:00',NULL,NULL,'2025-04-07 08:19:30'),(2,3,NULL,'индивидуальный заказ','2025-04-07','11:26:00',NULL,'платье голубого цвета с рукавами типа фонариков','2025-04-07 08:26:00'),(3,3,NULL,'примерка на дому','2025-04-12','13:34:00','москва, планерная 35','летнее платье зеленого цвета. летние туфли на каблуке','2025-04-07 08:27:10'),(5,10,8,'консультация','2001-12-12','12:12:00',NULL,NULL,'2025-04-15 11:59:30'),(7,10,8,'консультация','3333-12-13','13:13:00',NULL,NULL,'2025-04-15 12:02:35'),(10,12,8,'консультация','2025-05-15','14:14:00',NULL,NULL,'2025-05-14 15:07:26'),(11,12,NULL,'примерка на дому','2025-05-16','19:00:00','горохина 15, 65','456789098 артикул, черные,44 размер, артикул 4567890987 35 размер розового цвета для ребёнка','2025-05-14 15:29:13'),(12,12,NULL,'индивидуальный заказ','2025-09-28','10:19:00',NULL,'мне необходимо сшить пиджак черного цвета из лучшей ткани, размеры:......','2025-05-14 15:31:12'),(13,14,8,'консультация','2025-06-01','13:40:00',NULL,NULL,'2025-05-29 20:42:54');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `color` enum('Белый','Чёрный','Синий','Красный','Зелёный','Голубой') NOT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `size_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_user` (`user_id`) USING BTREE,
  KEY `fk_cart_product` (`product_id`),
  KEY `fk_cart_size_id` (`size_id`),
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_size_id` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (15,4,1,'Зелёный',1,'2025-06-02 20:56:43',3),(16,4,5,'Голубой',1,'2025-06-02 20:57:08',29),(17,6,14,'Белый',2,'2025-06-02 21:31:21',4);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Одежда'),(2,'Обувь'),(3,'Аксессуары');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'1. О доставке','Какие у вас способы доставки?','Мы предлагаем курьерскую доставку на дом, самовывоз из наших пунктов выдачи, а также доставку через почтовые службы.'),(2,'2. О возвратах и обменах','Как оформить возврат или обмен товара?','Вы можете оформить возврат или обмен через личный кабинет на сайте, заполнив форму возврата, или обратиться в службу поддержки.'),(3,'3. О наличии товара и ассортименте','Как узнать, есть ли товар в наличии?','Статус наличия указан на странице товара. Если вы не нашли нужный размер или цвет, вы можете оставить заявку на уведомление о поступлении.'),(4,'1. О доставке','Сколько времени занимает доставка?','Средний срок доставки составляет 2–5 рабочих дней, в зависимости от вашего региона.'),(5,'1. О доставке','Сколько стоит доставка?','Стоимость доставки зависит от вашего региона и способа доставки. Подробную информацию вы найдете в корзине при оформлении заказа.'),(6,'1. О доставке','Можно ли изменить адрес доставки после оформления заказа?','Да, вы можете изменить адрес доставки, если заказ еще не отправлен. Для этого свяжитесь с нашей службой поддержки.'),(7,'2. О возвратах и обменах','В течение какого времени можно вернуть товар?','Вы можете вернуть товар в течение 14 дней с момента получения, если он не был в употреблении и сохранен товарный вид.'),(8,'2. О возвратах и обменах','Как быстро возвращаются деньги за возврат?','Возврат средств осуществляется в течение 5–10 рабочих дней после обработки вашего возврата.'),(9,'2. О возвратах и обменах','Можно ли обменять товар на другой размер?','Да, обмен возможен, если нужный размер есть в наличии. Для этого заполните форму обмена в личном кабинете.'),(10,'3. О наличии товара и ассортименте','Как часто обновляется ассортимент?','Мы добавляем новые товары каждую неделю, следите за обновлениями на сайте и подписывайтесь на наши рассылки, чтобы быть в курсе новинок.'),(11,'3. О наличии товара и ассортименте','Можно ли заказать товар, которого сейчас нет в наличии?','К сожалению, предзаказ недоступен. Рекомендуем подписаться на уведомление о поступлении.'),(12,'4. О заказах и оплате','Как оформить заказ?','Выберите товар, добавьте его в корзину, укажите контактные данные и способ доставки, после чего завершите покупку.'),(13,'4. О заказах и оплате','Какие способы оплаты вы принимаете?','Мы принимаем оплату банковскими картами, электронными кошельками, а также оплату при получении в пункте выдачи или курьеру.'),(14,'4. О заказах и оплате','Как я могу отследить свой заказ?','После отправки заказа мы отправим вам номер для отслеживания. Вы можете проверить статус доставки на сайте транспортной компании или в личном кабинете.'),(15,'5. О товарах и уходе за одеждой','Как ухаживать за одеждой, чтобы она служила дольше?','На каждом товаре указаны рекомендации по уходу. Также вы найдете их на вшитой бирке.'),(16,'5. О товарах и уходе за одеждой','Ваши размеры соответствуют стандартным?','Мы стараемся следовать международным стандартам. Рекомендуем ознакомиться с таблицей размеров на странице товара перед покупкой.');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorites_user` (`user_id`),
  KEY `fk_favorites_product` (`product_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (3,3,1),(6,6,1),(9,6,2),(10,10,2),(11,10,3),(12,6,4),(13,6,5),(14,6,11),(15,6,10),(16,6,9),(17,6,3),(18,14,1),(19,14,5),(20,14,4);
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_cards`
--

DROP TABLE IF EXISTS `loyalty_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_spent` decimal(10,2) DEFAULT '0.00',
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_loyalty_cards_user` (`user_id`),
  CONSTRAINT `fk_loyalty_cards_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `loyalty_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_cards`
--

LOCK TABLES `loyalty_cards` WRITE;
/*!40000 ALTER TABLE `loyalty_cards` DISABLE KEYS */;
INSERT INTO `loyalty_cards` VALUES (1,3,117995.00,10.00,'2025-05-12 18:48:58'),(3,6,22500.00,0.00,'2025-05-22 15:59:05'),(6,10,14800.00,0.00,'2025-04-15 11:56:12'),(7,14,10000.00,0.00,'2025-05-29 20:37:11'),(8,4,0.00,0.00,'2025-05-29 21:06:42');
/*!40000 ALTER TABLE `loyalty_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_user` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3,1500.00,'paid','2025-04-08 11:02:00'),(2,3,52500.00,'paid','2025-04-08 12:11:48'),(3,3,52500.00,'paid','2025-04-08 12:13:58'),(4,3,1500.00,'paid','2025-04-08 12:14:49'),(5,10,14800.00,'paid','2025-04-15 11:56:12'),(6,3,9995.00,'paid','2025-05-12 18:48:58'),(7,6,22500.00,'paid','2025-05-22 15:59:05'),(8,14,5000.00,'paid','2025-05-29 20:34:43'),(9,14,5000.00,'paid','2025-05-29 20:37:11');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `color` enum('Белый','Черный','Синий','Красный','Зеленый','Голубой') NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `size_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_attributes_product` (`product_id`),
  KEY `fk_size_id` (`size_id`),
  CONSTRAINT `fk_product_attributes_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_size_id` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`),
  CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attributes`
--

LOCK TABLES `product_attributes` WRITE;
/*!40000 ALTER TABLE `product_attributes` DISABLE KEYS */;
INSERT INTO `product_attributes` VALUES (1,1,'Голубой',100,4),(2,1,'Зеленый',30,3),(3,1,'Черный',30,7),(4,2,'Зеленый',5,34),(5,2,'Черный',5,37),(6,3,'Синий',5,4),(7,3,'Черный',5,3),(8,1,'Красный',5,7),(10,5,'Голубой',10,29),(11,11,'Черный',2,1),(13,12,'Красный',20,4),(14,9,'Зеленый',5,5),(16,4,'Красный',3,1),(17,14,'Белый',6,4);
/*!40000 ALTER TABLE `product_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_images_product` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'https://images.pexels.com/photos/20509221/pexels-photo-20509221.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',1),(2,1,'https://images.pexels.com/photos/20509221/pexels-photo-20509221.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',0),(3,1,'https://images.pexels.com/photos/20509221/pexels-photo-20509221.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',0),(4,2,'https://outmaxshop.ru/components/com_jshopping/files/img_products/30097/nike-sb-dunk-low-30097-1.jpg',1),(5,2,'https://outmaxshop.ru/components/com_jshopping/files/img_products/27465/nike-sb-dunk-low-27465-1.jpg',0),(6,2,'https://static.insales-cdn.com/images/products/1/7068/358063004/Nike_SB_Dunk_Low_%D0%BA%D1%83%D0%BF%D0%B8%D1%82%D1%8C-04.jpg',0),(7,3,'https://cdn.tkaner.com/wp/uploads/2020/04/ronjas-raeuberlaedchen.de_.jpg',1),(8,3,'https://cdn.laredoute.com/cdn-cgi/image/width=1200,height=1200,fit=pad,dpr=1/products/4/6/7/4672f3c83f3438217810d3a37896b652.jpg',0),(9,4,'https://basket-15.wbbasket.ru/vol2302/part230219/230219432/images/big/1.webp',1),(10,4,'https://basket-15.wbbasket.ru/vol2302/part230219/230219432/images/big/8.webp',0),(11,4,'https://basket-15.wbbasket.ru/vol2302/part230219/230219432/images/big/15.webp',0),(12,4,'https://basket-15.wbbasket.ru/vol2302/part230219/230219432/images/big/6.webp',0),(28,5,'https://ae01.alicdn.com/kf/S419119b3ef06495ba153427705528cb08.jpg',1),(29,5,'https://ae04.alicdn.com/kf/S871e41d686574740b2ebf8265c12ca08E.png',0),(30,5,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8ydgOqR0CWQflxzTK_52t-GNSrq7hcCehJbYdd7sXlEGow2t6bqL_S8tK74AgdzkO-5Y&usqp=CAU',0),(31,9,'https://ae04.alicdn.com/kf/H299d94f3546843e39f44ff760ef40948W.jpg_640x640.jpg',1),(32,9,'https://ae04.alicdn.com/kf/H299d94f3546843e39f44ff760ef40948W.jpg_640x640.jpg',0),(33,9,'https://ae04.alicdn.com/kf/H299d94f3546843e39f44ff760ef40948W.jpg_640x640.jpg',0),(34,9,'https://ae04.alicdn.com/kf/H299d94f3546843e39f44ff760ef40948W.jpg_640x640.jpg',0),(35,9,'https://ae04.alicdn.com/kf/H299d94f3546843e39f44ff760ef40948W.jpg_640x640.jpg',0),(39,11,'https://basket-12.wbbasket.ru/vol1705/part170568/170568793/images/c516x688/1.webp',1),(40,11,'https://basket-12.wbbasket.ru/vol1705/part170568/170568793/images/c516x688/1.webp',0),(41,11,'https://basket-12.wbbasket.ru/vol1705/part170568/170568793/images/c516x688/1.webp',0),(46,12,'https://basket-02.wbbasket.ru/vol164/part16447/16447758/images/big/1.webp',1),(47,12,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0L4KQJh6GIZiqZzA2sA7M9DgChDjMiWS_m2e6agPfaSdIQDLTH57t__-FEOThT7AXq0M&usqp=CAU',0),(48,12,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-_yko24JaM5ZrGY4vywDENlzRRXzZ2DKAyGHPn7MmNtOxdAVo1hqiWrbtXeTr-EJU8MQ&usqp=CAU',0),(49,12,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZz0TW7GVTGcpIjKWQ8kk0kJvPpswENrBYMsfwZ386dn2ktmbmMpuxwdXjsKDl4uZ1-x4&usqp=CAU',0),(53,10,'https://basket-05.wbbasket.ru/vol765/part76524/76524618/images/big/1.webp',1),(54,10,'https://basket-05.wbbasket.ru/vol765/part76524/76524618/images/big/1.webp',0),(55,10,'https://basket-05.wbbasket.ru/vol765/part76524/76524618/images/big/1.webp',0),(58,14,'https://basket-16.wbbasket.ru/vol2506/part250639/250639685/images/c516x688/1.webp',1),(59,14,'https://basket-12.wbbasket.ru/vol1812/part181278/181278008/images/c516x688/1.webp',0);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subcategory_id` int NOT NULL,
  `gender` enum('f','m','g','b') NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_products_subcategory` (`subcategory_id`),
  CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,20,'f','Летнее платье','Летнее платье выполнено в стиле корейских платьев',1500.00,'2025-04-06 15:46:47'),(2,29,'m','Nice Cross','мужские кросовки зеленого цвета',5000.00,'2025-04-08 20:08:09'),(3,37,'b','Клетчатая рубашка','Клетчатая рубашка для мальчика',2400.00,'2025-04-09 07:12:55'),(4,31,'f','Сумка Такса','Кожаная винного цвета женская сумка',5199.00,'2025-05-10 20:23:55'),(5,27,'f','Летние туфли','Выполнены из натуральной кожи, имеют хороший ступинатор после которого ноги не стают от ходьбы',2500.00,'2025-05-10 20:36:39'),(9,25,'f','Инопланетная толстовка','Удобная зеленая толстовка с классным принтом',3000.00,'2025-05-10 21:21:10'),(10,23,'f','МОМ\'s летние','джинсы',2600.00,'2025-05-10 21:29:34'),(11,32,'f','Cute bag','милый женский рюкзак',1999.00,'2025-05-10 21:37:11'),(12,25,'f','Розовый свитер','Незаменимая базовая модель вязаного свитера крупной вязки, которая отлично впишется в любой гардероб и позволит выглядеть стильно даже в самые сильные холода. Модель станет сдержанной и универсальной частью ваших образов, которую можно комбинировать в актуальных сейчас многослойных сочетаниях. Шерсть в составе сохранит тепло, а свободный крой подарит многообразие вариантов носки. ',6700.00,'2025-05-29 20:57:15'),(14,33,'f','Летние перчатки','Летние женские перчатки выполнены из нейлоновых нитей.Эти стильные аксессуары — незаменимая вещь для каждой модной девушки, стремящейся выделиться на праздниках и вечеринках. Выполненные из легкого материала с ажурным декором, они идеально подходят для создания имиджевых образов на хэллоуин, романтических свиданиях или незабываемых фотосессиях.',1000.00,'2025-06-02 21:31:07');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_questions_user` (`user_id`),
  CONSTRAINT `fk_questions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,4,'check it','2025-04-04 09:27:36'),(2,34,'рпорлоюб','2025-06-17 13:21:58'),(3,34,'рпорлоюб','2025-06-17 13:25:21');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sizes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `type` enum('clothing','shoes','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (1,'Без размера','none'),(2,'XS','clothing'),(3,'S','clothing'),(4,'M','clothing'),(5,'L','clothing'),(6,'XL','clothing'),(7,'XXL','clothing'),(8,'16','shoes'),(9,'17','shoes'),(10,'18','shoes'),(11,'19','shoes'),(12,'20','shoes'),(13,'21','shoes'),(14,'22','shoes'),(15,'23','shoes'),(16,'24','shoes'),(17,'25','shoes'),(18,'26','shoes'),(19,'27','shoes'),(20,'28','shoes'),(21,'29','shoes'),(22,'30','shoes'),(23,'31','shoes'),(24,'32','shoes'),(25,'33','shoes'),(26,'34','shoes'),(27,'35','shoes'),(28,'36','shoes'),(29,'37','shoes'),(30,'38','shoes'),(31,'39','shoes'),(32,'40','shoes'),(33,'41','shoes'),(34,'42','shoes'),(35,'43','shoes'),(36,'44','shoes'),(37,'45','shoes');
/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subcategories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subcategories_category` (`category_id`),
  CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (20,1,'Платья'),(21,1,'Юбки'),(22,1,'Блузки'),(23,1,'Джинсы'),(24,1,'Штаны'),(25,1,'Толстовки'),(26,1,'Футболки'),(27,2,'Туфли'),(28,2,'Кеды'),(29,2,'Кроссовки'),(30,2,'Сапоги'),(31,3,'Сумки'),(32,3,'Рюкзаки'),(33,3,'Перчатки'),(34,3,'Шапки и шарфы'),(35,1,'Костюмы'),(36,1,'Брюки'),(37,1,'Рубашки');
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `role` enum('user','admin','stylist') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  `verification_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'User1','user1@bk.ru','79999999999','$2y$10$fZa4qXZTz5DUVVvaRwirdeo87sFV0fnXByGGvOyBwOgbpZMvPNuf.','2000-08-08','user','2025-03-23 16:24:41',1,NULL),(3,'User2','user2@bk.ru','79662828575','$2y$10$fQbl/AxrsCQlkBk6v1yhx.sktTRCKgAACPKLvSXV0XKTG.xIEIrsq','2000-01-01','user','2025-03-30 20:09:26',1,NULL),(4,'User3new','user3@bk.ru','79666666666','$2y$10$y4L7THBW.vG751WX1g.h5.//4Zn4z/vTmfHvOq573bFbBw.jWF1nm','2000-02-01','user','2025-04-03 08:33:29',0,NULL),(5,'user4','user4@bk.ru','79999999999','$2y$10$QvBoFm3AWLxiSHMhqsznseaQRtfvdw4axYGbA0a95scoFp3dIER1W','2000-01-01','user','2025-04-03 11:51:49',0,NULL),(6,'admin','admin@bk.ru','79075553535','$2y$10$HgTDsSO9y.hWBzFhCCrDpuMY0yl41l8QxYXsUGAtIphqZwDdxH1.m','2005-02-18','admin','2025-04-06 19:36:30',1,NULL),(8,'Сюзанн Коллер','sui@bk.ru','79042578900','$2y$10$exli.7iT6RNTr0DYWRnKzea3fPYL9akvWc/UCsBwteKipqVyYVc3C',NULL,'stylist','2025-04-07 06:35:15',0,NULL),(10,'Олег','lg@bk.ru','79078989899','$2y$10$INAENvSLvuYNwz1/MjjqfOiE43zjssL5juhsr1YB1iDWR85o1dwiG','2000-01-01','user','2025-04-15 11:00:07',0,NULL),(12,'Алексей','alex@bk.ru','79663338888','$2y$10$Lf4JRO4P9/7UqYsrWIJtW.IwtvWZyOflmnqRPl6YIk/lZ7sqR2UEu','1990-09-09','user','2025-05-12 18:54:12',0,NULL),(13,'Яна','yana@bk.ru','79077778899','$2y$10$.0ojsAzUAUzMxGQynjQtuOtCj0AP0cBibPK05wbLNEUf5TQzX3NPW','2005-02-14','admin','2025-05-22 16:51:04',0,NULL),(14,'Элеонора','el@yandex.ru','79772998888','$2y$10$kJ4M1c5vXddNnqMtQTbqueL9mLfWPTLjIqyk0zgo5b7e3ICgL2osq','1993-11-23','user','2025-05-29 20:18:46',1,NULL),(34,'Агата Жеребцова','a9ata9er@gmail.com','78966282857','$2y$10$hu7wD1stll/SCH3t.0nAJuY1LEV//jVEhv5JMTp9y.DGMzkqjhvC6','2000-01-01','user','2025-06-17 11:25:38',1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-18 18:00:32
