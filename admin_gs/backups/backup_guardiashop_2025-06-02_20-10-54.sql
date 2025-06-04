-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: guardiashop
-- ------------------------------------------------------
-- Server version 	10.4.28-MariaDB
-- Date: Mon, 02 Jun 2025 20:10:54 +0200

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(280) NOT NULL,
  `rol` enum('admin','superadmin') NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `admin` with 0 row(s)
--

--
-- Table structure for table `carrito`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_carrito`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `carrito` with 0 row(s)
--

--
-- Table structure for table `carrito_producto`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carrito_producto` (
  `id_carrito_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_detalles_productos` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id_carrito_producto`),
  KEY `id_carrito` (`id_carrito`),
  KEY `id_detalles_productos` (`id_detalles_productos`),
  CONSTRAINT `carrito_producto_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`),
  CONSTRAINT `carrito_producto_ibfk_2` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito_producto`
--

LOCK TABLES `carrito_producto` WRITE;
/*!40000 ALTER TABLE `carrito_producto` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `carrito_producto` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `carrito_producto` with 0 row(s)
--

--
-- Table structure for table `categoria`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `categoria` VALUES (1,'Blusas','Prendas superiores femeninas, variedad de estilos y tejidos.'),(2,'Shorts','Pantalones cortos para hombre y mujer, casuales.'),(3,'Gorras','Accesorios para la cabeza con visera, protección y estilo.'),(4,'Camisas','Prendas superiores con cuello y botones, formales y casuales.');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `categoria` with 4 row(s)
--

--
-- Table structure for table `color_productos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color_productos` (
  `id_color` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `codigo_hexadecimal` varchar(20) NOT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_productos`
--

LOCK TABLES `color_productos` WRITE;
/*!40000 ALTER TABLE `color_productos` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `color_productos` VALUES (1,'Negro','#000000'),(2,'Blanco','#FFFFFF'),(3,'Caqui','#C3B091'),(4,'Rosado','#FFC0CB'),(5,'Azul Marino','#000080'),(6,'Verde','#008000'),(7,'Marrón','#8f4c25'),(8,'Café','#694c2f'),(9,'Beige','#F5F5DC'),(10,'Crema','#FFFDD0'),(11,'Gris','#808080');
/*!40000 ALTER TABLE `color_productos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `color_productos` with 11 row(s)
--

--
-- Table structure for table `compras`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) NOT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_recepcion_esperada` date DEFAULT NULL,
  `fecha_recepcion_real` date DEFAULT NULL,
  `Número_de_factura` varchar(50) DEFAULT NULL,
  `total_compra` decimal(10,2) DEFAULT NULL,
  `estado_compra` enum('solicitada','confirmada','enviada','recibida_parcial','recibida_completa','cancelada') NOT NULL DEFAULT 'solicitada',
  PRIMARY KEY (`id_compra`),
  KEY `id_proveedor` (`id_proveedor`),
  CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `compras` with 0 row(s)
--

--
-- Table structure for table `contactanos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactanos` (
  `id_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `correo` varchar(50) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('Nuevo','Leído','Respondido','Cerrado') NOT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `contactanos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactanos`
--

LOCK TABLES `contactanos` WRITE;
/*!40000 ALTER TABLE `contactanos` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `contactanos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `contactanos` with 0 row(s)
--

--
-- Table structure for table `copias_seguridad`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `copias_seguridad` (
  `id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `tamano` int(11) NOT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copias_seguridad`
--

LOCK TABLES `copias_seguridad` WRITE;
/*!40000 ALTER TABLE `copias_seguridad` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `copias_seguridad` VALUES (6,'backup_guardiashop_2025-06-01_22-02-28.sql','2025-06-01 22:02:29',49321,'manual',NULL);
/*!40000 ALTER TABLE `copias_seguridad` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `copias_seguridad` with 1 row(s)
--

--
-- Table structure for table `descuentos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descuentos` (
  `id_descuento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_descuento` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_descuento` enum('porcentaje') NOT NULL DEFAULT 'porcentaje',
  `valor_descuento` decimal(10,2) NOT NULL,
  `codigo_cupon` varchar(50) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL COMMENT 'Fecha y hora de inicio de validez (NULL si siempre activo o activado manualmente)',
  `fecha_fin` datetime DEFAULT NULL COMMENT 'Fecha y hora de fin de validez (NULL si no expira o desactivado manualmente)',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `usos_maximos_global` int(11) DEFAULT NULL COMMENT 'Número máximo de veces que este descuento puede ser usado en total (NULL para ilimitado)',
  `usos_actuales_global` int(11) NOT NULL DEFAULT 0 COMMENT 'Número de veces que este descuento ha sido usado en total',
  `uso_unico_por_cliente` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'TRUE si cada cliente solo puede usarlo una vez',
  `monto_minimo_compra` decimal(10,2) DEFAULT NULL COMMENT 'Monto mínimo en el carrito para que aplique el descuento (NULL si no aplica)',
  `aplica_a_producto_id` int(11) DEFAULT NULL COMMENT 'FK a productos.id_producto (si el descuento es para un producto específico)',
  `es_por_cumpleanos` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'TRUE si este es un descuento especial de cumpleaños',
  `dias_validez_cumpleanos` int(3) DEFAULT NULL COMMENT 'Si es_por_cumpleanos, cuántos días antes/después es válido (ej. 7 días)',
  PRIMARY KEY (`id_descuento`),
  UNIQUE KEY `codigo_cupon` (`codigo_cupon`),
  KEY `idx_codigo_cupon` (`codigo_cupon`),
  KEY `idx_fechas_activo` (`activo`,`fecha_inicio`,`fecha_fin`),
  KEY `aplica_a_producto_id` (`aplica_a_producto_id`),
  CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`aplica_a_producto_id`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla para gestionar descuentos y promociones';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descuentos`
--

LOCK TABLES `descuentos` WRITE;
/*!40000 ALTER TABLE `descuentos` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `descuentos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `descuentos` with 0 row(s)
--

--
-- Table structure for table `detalles_compra`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_compra` (
  `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_compra` int(11) NOT NULL,
  `id_detalles_productos` int(11) NOT NULL,
  `cantidad_comprada` int(11) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad_comprada` * `costo_unitario`) STORED,
  PRIMARY KEY (`id_detalle_compra`),
  KEY `id_compra` (`id_compra`),
  KEY `id_detalles_productos` (`id_detalles_productos`),
  CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_compra`
--

LOCK TABLES `detalles_compra` WRITE;
/*!40000 ALTER TABLE `detalles_compra` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `detalles_compra` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detalles_compra` with 0 row(s)
--

--
-- Table structure for table `detalles_pedido`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_pedido` (
  `id_detalles_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_detalles_productos` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED,
  PRIMARY KEY (`id_detalles_pedido`),
  KEY `id_detalles_productos` (`id_detalles_productos`),
  KEY `detalles_pedido_ibfk_2` (`id_pedido`),
  CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`),
  CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_pedido`
--

LOCK TABLES `detalles_pedido` WRITE;
/*!40000 ALTER TABLE `detalles_pedido` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `detalles_pedido` (`id_detalles_pedido`, `id_pedido`, `id_detalles_productos`, `cantidad`, `precio_unitario`) VALUES (50,39,119,1,125000.00),(51,39,131,1,130000.00),(52,39,183,1,140000.00),(53,40,22,3,140000.00),(54,40,46,3,148000.00),(55,40,59,1,85000.00),(56,40,54,2,80000.00),(57,40,135,1,138000.00),(58,40,6,2,135000.00),(59,40,88,1,165000.00),(60,40,64,2,160000.00),(61,40,243,2,155000.00),(62,40,55,2,70000.00),(63,40,51,2,75000.00);
/*!40000 ALTER TABLE `detalles_pedido` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detalles_pedido` with 14 row(s)
--

--
-- Table structure for table `detalles_productos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_productos` (
  `id_detalles_productos` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `id_tallas` int(11) NOT NULL,
  `precio_producto` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_minimo_alerta` int(11) DEFAULT 5 COMMENT 'Umbral para alerta de bajo stock (NULL si no aplica)',
  PRIMARY KEY (`id_detalles_productos`),
  KEY `id_color` (`id_color`),
  KEY `id_producto` (`id_producto`),
  KEY `id_tallas` (`id_tallas`),
  CONSTRAINT `detalles_productos_ibfk_1` FOREIGN KEY (`id_color`) REFERENCES `color_productos` (`id_color`),
  CONSTRAINT `detalles_productos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `detalles_productos_ibfk_3` FOREIGN KEY (`id_tallas`) REFERENCES `talla_productos` (`id_talla`)
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_productos`
--

LOCK TABLES `detalles_productos` WRITE;
/*!40000 ALTER TABLE `detalles_productos` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `detalles_productos` (`id_detalles_productos`, `id_producto`, `id_color`, `id_tallas`, `precio_producto`, `stock`, `stock_minimo_alerta`) VALUES (1,1,1,1,125000.00,15,5),(2,1,1,2,125000.00,20,5),(3,1,1,3,135000.00,18,5),(4,1,1,4,135000.00,10,5),(5,1,2,1,125000.00,18,5),(6,1,2,2,125000.00,22,5),(7,1,2,3,135000.00,19,5),(8,1,2,4,135000.00,12,5),(9,1,3,1,125000.00,12,5),(10,1,3,2,125000.00,15,5),(11,1,3,3,135000.00,14,5),(12,1,3,4,135000.00,8,5),(13,2,2,1,130000.00,20,5),(14,2,2,2,130000.00,25,5),(15,2,2,3,140000.00,22,5),(16,2,2,4,140000.00,15,5),(17,2,1,1,130000.00,17,5),(18,2,1,2,130000.00,20,5),(19,2,1,3,140000.00,17,5),(20,2,1,4,140000.00,11,5),(21,2,4,1,130000.00,14,5),(22,2,4,2,130000.00,18,5),(23,2,4,3,140000.00,16,5),(24,2,4,4,140000.00,9,5),(25,3,2,1,145000.00,16,5),(26,3,2,2,145000.00,19,5),(27,3,2,3,155000.00,17,5),(28,3,2,4,155000.00,10,5),(29,3,1,1,145000.00,15,5),(30,3,1,2,145000.00,18,5),(31,3,1,3,155000.00,15,5),(32,3,1,4,155000.00,9,5),(33,3,3,1,145000.00,11,5),(34,3,3,2,145000.00,13,5),(35,3,3,3,155000.00,11,5),(36,3,3,4,155000.00,7,5),(37,4,1,1,138000.00,18,5),(38,4,1,2,138000.00,21,5),(39,4,1,3,148000.00,19,5),(40,4,1,4,148000.00,12,5),(41,4,2,1,138000.00,20,5),(42,4,2,2,138000.00,24,5),(43,4,2,3,148000.00,19,5),(44,4,2,4,148000.00,14,5),(45,4,5,1,138000.00,13,5),(46,4,5,2,138000.00,16,5),(47,4,5,3,148000.00,13,5),(48,4,5,4,148000.00,8,5),(49,5,1,5,75000.00,30,5),(50,5,2,5,75000.00,35,5),(51,5,6,5,75000.00,25,5),(52,6,1,5,80000.00,28,5),(53,6,2,5,80000.00,33,5),(54,6,9,5,80000.00,22,5),(55,7,1,5,70000.00,32,5),(56,7,2,5,70000.00,38,5),(57,7,3,5,70000.00,26,5),(58,8,1,5,85000.00,29,5),(59,8,2,5,85000.00,34,5),(60,8,8,5,85000.00,24,5),(61,9,2,1,150000.00,15,5),(62,9,2,2,150000.00,20,5),(63,9,2,3,160000.00,18,5),(64,9,2,4,160000.00,10,5),(65,9,1,1,150000.00,18,5),(66,9,1,2,150000.00,22,5),(67,9,1,3,160000.00,20,5),(68,9,1,4,160000.00,12,5),(69,9,3,1,150000.00,12,5),(70,9,3,2,150000.00,15,5),(71,9,3,3,160000.00,14,5),(72,9,3,4,160000.00,8,5),(73,10,3,1,165000.00,14,5),(74,10,3,2,165000.00,17,5),(75,10,3,3,175000.00,15,5),(76,10,3,4,175000.00,9,5),(77,10,2,1,165000.00,16,5),(78,10,2,2,165000.00,20,5),(79,10,2,3,175000.00,18,5),(80,10,2,4,175000.00,11,5),(81,10,1,1,165000.00,17,5),(82,10,1,2,165000.00,21,5),(83,10,1,3,175000.00,19,5),(84,10,1,4,175000.00,13,5),(85,11,7,1,155000.00,11,5),(86,11,7,2,155000.00,14,5),(87,11,7,3,165000.00,12,5),(88,11,7,4,165000.00,7,5),(89,11,1,1,155000.00,19,5),(90,11,1,2,155000.00,23,5),(91,11,1,3,165000.00,20,5),(92,11,1,4,165000.00,14,5),(93,11,2,1,155000.00,16,5),(94,11,2,2,155000.00,20,5),(95,11,2,3,165000.00,17,5),(96,11,2,4,165000.00,10,5),(97,12,8,1,170000.00,13,5),(98,12,8,2,170000.00,16,5),(99,12,8,3,180000.00,14,5),(100,12,8,4,180000.00,8,5),(101,12,1,1,170000.00,17,5),(102,12,1,2,170000.00,21,5),(103,12,1,3,180000.00,19,5),(104,12,1,4,180000.00,12,5),(105,12,2,1,170000.00,18,5),(106,12,2,2,170000.00,22,5),(107,12,2,3,180000.00,20,5),(108,12,2,4,180000.00,13,5),(109,13,1,1,115000.00,20,5),(110,13,1,2,115000.00,25,5),(111,13,1,3,125000.00,22,5),(112,13,1,4,125000.00,15,5),(113,13,3,1,115000.00,15,5),(114,13,3,2,115000.00,18,5),(115,13,3,3,125000.00,16,5),(116,13,3,4,125000.00,10,5),(117,13,5,1,115000.00,12,5),(118,13,5,2,115000.00,15,5),(119,13,5,3,125000.00,13,5),(120,13,5,4,125000.00,8,5),(121,14,1,1,120000.00,22,5),(122,14,1,2,120000.00,28,5),(123,14,1,3,130000.00,25,5),(124,14,1,4,130000.00,18,5),(125,14,3,1,120000.00,17,5),(126,14,3,2,120000.00,20,5),(127,14,3,3,130000.00,18,5),(128,14,3,4,130000.00,12,5),(129,14,2,1,120000.00,18,5),(130,14,2,2,120000.00,22,5),(131,14,2,3,130000.00,19,5),(132,14,2,4,130000.00,13,5),(133,15,1,1,128000.00,19,5),(134,15,1,2,128000.00,24,5),(135,15,1,3,138000.00,21,5),(136,15,1,4,138000.00,14,5),(137,15,3,1,128000.00,14,5),(138,15,3,2,128000.00,17,5),(139,15,3,3,138000.00,15,5),(140,15,3,4,138000.00,9,5),(141,15,5,1,128000.00,16,5),(142,15,5,2,128000.00,19,5),(143,15,5,3,138000.00,17,5),(144,15,5,4,138000.00,11,5),(145,16,1,1,140000.00,21,5),(146,16,1,2,140000.00,26,5),(147,16,1,3,150000.00,23,5),(148,16,1,4,150000.00,16,5),(149,16,3,1,140000.00,16,5),(150,16,3,2,140000.00,19,5),(151,16,3,3,150000.00,17,5),(152,16,3,4,150000.00,11,5),(153,16,5,1,140000.00,18,5),(154,16,5,2,140000.00,21,5),(155,16,5,3,150000.00,19,5),(156,16,5,4,150000.00,13,5),(157,17,1,1,105000.00,15,5),(158,17,1,2,105000.00,20,5),(159,17,1,3,115000.00,18,5),(160,17,1,4,115000.00,10,5),(161,17,2,1,105000.00,18,5),(162,17,2,2,105000.00,22,5),(163,17,2,3,115000.00,20,5),(164,17,2,4,115000.00,12,5),(165,17,6,1,105000.00,12,5),(166,17,6,2,105000.00,15,5),(167,17,6,3,115000.00,14,5),(168,17,6,4,115000.00,8,5),(169,18,2,1,110000.00,20,5),(170,18,2,2,110000.00,25,5),(171,18,2,3,120000.00,22,5),(172,18,2,4,120000.00,15,5),(173,18,1,1,110000.00,17,5),(174,18,1,2,110000.00,20,5),(175,18,1,3,120000.00,18,5),(176,18,1,4,120000.00,11,5),(177,18,8,1,110000.00,10,5),(178,18,8,2,110000.00,13,5),(179,18,8,3,120000.00,11,5),(180,18,8,4,120000.00,7,5),(181,19,2,1,130000.00,16,5),(182,19,2,2,130000.00,19,5),(183,19,2,3,140000.00,16,5),(184,19,2,4,140000.00,10,5),(185,19,1,1,130000.00,15,5),(186,19,1,2,130000.00,18,5),(187,19,1,3,140000.00,15,5),(188,19,1,4,140000.00,9,5),(189,19,3,1,130000.00,11,5),(190,19,3,2,130000.00,13,5),(191,19,3,3,140000.00,12,5),(192,19,3,4,140000.00,7,5),(193,20,2,1,98000.00,22,5),(194,20,2,2,98000.00,28,5),(195,20,2,3,108000.00,25,5),(196,20,2,4,108000.00,18,5),(197,20,1,1,98000.00,20,5),(198,20,1,2,98000.00,25,5),(199,20,1,3,108000.00,23,5),(200,20,1,4,108000.00,15,5),(201,20,3,1,98000.00,18,5),(202,20,3,2,98000.00,21,5),(203,20,3,3,108000.00,19,5),(204,20,3,4,108000.00,12,5),(205,21,2,1,160000.00,12,5),(206,21,2,2,160000.00,15,5),(207,21,2,3,170000.00,13,5),(208,21,2,4,170000.00,8,5),(209,21,1,1,160000.00,14,5),(210,21,1,2,160000.00,18,5),(211,21,1,3,170000.00,16,5),(212,21,1,4,170000.00,10,5),(213,21,3,1,160000.00,9,5),(214,21,3,2,160000.00,11,5),(215,21,3,3,170000.00,10,5),(216,21,3,4,170000.00,6,5),(217,22,4,1,140000.00,11,5),(218,22,4,2,140000.00,14,5),(219,22,4,3,150000.00,12,5),(220,22,4,4,150000.00,7,5),(221,22,2,1,140000.00,16,5),(222,22,2,2,140000.00,20,5),(223,22,2,3,150000.00,17,5),(224,22,2,4,150000.00,10,5),(225,22,1,1,140000.00,15,5),(226,22,1,2,140000.00,18,5),(227,22,1,3,150000.00,15,5),(228,22,1,4,150000.00,9,5),(229,23,8,1,148000.00,13,5),(230,23,8,2,148000.00,16,5),(231,23,8,3,158000.00,14,5),(232,23,8,4,158000.00,8,5),(233,23,2,1,148000.00,17,5),(234,23,2,2,148000.00,21,5),(235,23,2,3,158000.00,19,5),(236,23,2,4,158000.00,12,5),(237,23,1,1,148000.00,16,5),(238,23,1,2,148000.00,20,5),(239,23,1,3,158000.00,18,5),(240,23,1,4,158000.00,11,5),(241,24,2,1,155000.00,14,5),(242,24,2,2,155000.00,17,5),(243,24,2,3,165000.00,15,5),(244,24,2,4,165000.00,9,5),(245,24,1,1,155000.00,18,5),(246,24,1,2,155000.00,22,5),(247,24,1,3,165000.00,20,5),(248,24,1,4,165000.00,13,5),(249,24,3,1,155000.00,10,5),(250,24,3,2,155000.00,13,5),(251,24,3,3,165000.00,10,5),(252,24,3,4,165000.00,7,5);
/*!40000 ALTER TABLE `detalles_productos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detalles_productos` with 252 row(s)
--

--
-- Table structure for table `devoluciones_cliente`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devoluciones_cliente` (
  `id_devolucion_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_detalles_pedido` int(11) NOT NULL,
  `cantidad_devuelta` int(11) NOT NULL,
  `motivo` text DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_devolucion` enum('Solicitada','Aprobada','Rechazada','Producto Recibido','Reembolsado','Cambio Enviado','Cerrada') NOT NULL DEFAULT 'Solicitada',
  `fecha_actualizacion_estado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_resolucion` enum('Reembolso','Cambio Producto') DEFAULT NULL,
  `monto_reembolsado` decimal(10,2) DEFAULT NULL,
  `notas_admin` text DEFAULT NULL,
  PRIMARY KEY (`id_devolucion_cliente`),
  KEY `idx_id_pedido` (`id_pedido`),
  KEY `devoluciones_cliente_ibfk_2` (`id_detalles_pedido`),
  CONSTRAINT `devoluciones_cliente_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `devoluciones_cliente_ibfk_2` FOREIGN KEY (`id_detalles_pedido`) REFERENCES `detalles_pedido` (`id_detalles_pedido`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gestión de las devoluciones de productos por clientes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devoluciones_cliente`
--

LOCK TABLES `devoluciones_cliente` WRITE;
/*!40000 ALTER TABLE `devoluciones_cliente` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `devoluciones_cliente` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `devoluciones_cliente` with 0 row(s)
--

--
-- Table structure for table `direccion`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `direccion` text NOT NULL,
  `direccion_adiccional` text NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `codigo_postal` text NOT NULL,
  `telefono` int(20) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  PRIMARY KEY (`id_direccion`),
  KEY `direccion_ibfk_1` (`usuario_id`),
  CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direccion`
--

LOCK TABLES `direccion` WRITE;
/*!40000 ALTER TABLE `direccion` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `direccion` (`id_direccion`, `usuario_id`, `pais`, `direccion`, `direccion_adiccional`, `ciudad`, `departamento`, `codigo_postal`, `telefono`, `identificacion`) VALUES (3,7,'colombia','cascorba via cvi','san judas','quibdo','choco','2700001',2147483647,'10999222333');
/*!40000 ALTER TABLE `direccion` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `direccion` with 1 row(s)
--

--
-- Table structure for table `facturas_venta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturas_venta` (
  `id_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL COMMENT 'FK al pedido que se está facturando',
  `numero_factura` varchar(50) NOT NULL COMMENT 'Número legal y secuencial de la factura (Generado por la aplicación)',
  `fecha_emision` date NOT NULL COMMENT 'Fecha en que se emite la factura',
  `fecha_vencimiento` date DEFAULT NULL COMMENT 'Fecha límite de pago (si aplica)',
  `cliente_nombre_completo` varchar(150) DEFAULT NULL COMMENT 'Nombre del cliente en la factura',
  `cliente_direccion_fiscal` text DEFAULT NULL COMMENT 'Dirección fiscal del cliente en la factura',
  `cliente_identificacion_fiscal` varchar(50) DEFAULT NULL COMMENT 'NIF/CIF/RUC/NIT del cliente en la factura',
  `subtotal_base` decimal(12,2) NOT NULL COMMENT 'Suma de precios de productos ANTES de impuestos',
  `total_impuestos` decimal(12,2) NOT NULL COMMENT 'Suma de todos los impuestos (IVA, etc.)',
  `total_factura` decimal(12,2) NOT NULL COMMENT 'Monto final a pagar (subtotal_base + total_impuestos)',
  `estado_factura` enum('Emitida','Pagada','Vencida','Anulada') NOT NULL DEFAULT 'Emitida' COMMENT 'Estado actual de la factura',
  `metodo_pago_registrado` varchar(50) DEFAULT NULL COMMENT 'Método con el que se pagó (si aplica)',
  `notas_factura` text DEFAULT NULL COMMENT 'Notas o comentarios adicionales en la factura',
  `fecha_creacion_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_factura`),
  UNIQUE KEY `numero_factura` (`numero_factura`),
  KEY `idx_id_pedido_factura` (`id_pedido`),
  KEY `idx_fecha_emision` (`fecha_emision`),
  KEY `idx_estado_factura` (`estado_factura`),
  CONSTRAINT `facturas_venta_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Almacena los datos maestros de las facturas de venta emitidas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas_venta`
--

LOCK TABLES `facturas_venta` WRITE;
/*!40000 ALTER TABLE `facturas_venta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `facturas_venta` (`id_factura`, `id_pedido`, `numero_factura`, `fecha_emision`, `fecha_vencimiento`, `cliente_nombre_completo`, `cliente_direccion_fiscal`, `cliente_identificacion_fiscal`, `subtotal_base`, `total_impuestos`, `total_factura`, `estado_factura`, `metodo_pago_registrado`, `notas_factura`, `fecha_creacion_registro`) VALUES (22,39,'FAC-000039','2025-06-01','2025-07-01','santiago','cascorba via cvi, san judas','10999222333',395000.00,0.00,395000.00,'Pagada','paypal','','2025-06-02 00:14:24'),(23,40,'FAC-000040','2025-06-01','2025-07-01','Cliente','','',2602000.00,0.00,2602000.00,'Pagada','paypal','','2025-06-02 01:34:46');
/*!40000 ALTER TABLE `facturas_venta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `facturas_venta` with 2 row(s)
--

--
-- Table structure for table `movimientos_inventario`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_detalles_productos` int(11) NOT NULL COMMENT 'FK a la variante específica (producto+color+talla)',
  `tipo_movimiento` enum('Compra','Venta','Devolucion_Cliente','Devolucion_Proveedor','Ajuste_Manual_Positivo','Ajuste_Manual_Negativo','Inicial') NOT NULL COMMENT 'Razón del cambio de stock',
  `cantidad_cambio` int(11) NOT NULL COMMENT 'Positivo para entradas (+), Negativo para salidas (-)',
  `stock_resultante` int(11) DEFAULT NULL COMMENT 'Opcional: Stock total de la variante DESPUÉS de este movimiento',
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Momento exacto del movimiento',
  `referencia_origen` varchar(50) DEFAULT NULL COMMENT 'ID Origen: id_detalle_compra, id_detalles_pedido, id_devolucion, etc.',
  `id_admin` int(11) DEFAULT NULL COMMENT 'FK a Admin: Quién hizo el ajuste manual (si aplica)',
  `costo_unitario_movimiento` decimal(10,2) DEFAULT NULL COMMENT 'Costo unitario asociado a ESTE lote (importante para FIFO/LIFO/Promedio)',
  PRIMARY KEY (`id_movimiento`),
  KEY `idx_id_detalles_productos` (`id_detalles_productos`),
  KEY `idx_fecha_hora` (`fecha_hora`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_detalles_productos`) REFERENCES `detalles_productos` (`id_detalles_productos`) ON UPDATE CASCADE,
  CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Historial detallado de todos los cambios de inventario';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `movimientos_inventario` with 0 row(s)
--

--
-- Table structure for table `pago`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo_pago` enum('efectivo','tarjeta','paypal','transferencia') NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `estado_pago` enum('pendiente','completado','fallido') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `pago_ibfk_2` (`id_pedido`),
  CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pago` (`id_pago`, `fecha_pago`, `metodo_pago`, `id_pedido`, `estado_pago`, `monto`) VALUES (35,'2025-06-02 00:14:24','paypal',39,'completado',395000.00),(36,'2025-06-02 01:34:46','paypal',40,'completado',2602000.00);
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pago` with 2 row(s)
--

--
-- Table structure for table `pedido`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha_orden` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `pedido_ibfk_1` (`usuario_id`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pedido` (`id_pedido`, `usuario_id`, `fecha_orden`, `total`, `estado`) VALUES (39,7,'2025-06-02 00:14:24',395000.00,'confirmado'),(40,22,'2025-06-02 01:34:46',2602000.00,'confirmado');
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pedido` with 2 row(s)
--

--
-- Table structure for table `pedido_historial`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido_historial` (
  `id_historial` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') DEFAULT NULL COMMENT 'Estado antes del cambio (NULL si es el estado inicial del pedido)',
  `estado_nuevo` enum('pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido') DEFAULT NULL COMMENT 'Estado al que cambió el pedido',
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_admin_cambio` int(11) DEFAULT NULL COMMENT 'FK opcional al admin que realizó el cambio (NULL si fue automático o por el cliente)',
  `notas` text DEFAULT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `idx_id_pedido_fecha` (`id_pedido`,`fecha_cambio`),
  KEY `id_admin_cambio` (`id_admin_cambio`),
  CONSTRAINT `pedido_historial_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pedido_historial_ibfk_2` FOREIGN KEY (`id_admin_cambio`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Historial de los cambios de estado por los que pasa un pedido';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_historial`
--

LOCK TABLES `pedido_historial` WRITE;
/*!40000 ALTER TABLE `pedido_historial` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pedido_historial` (`id_historial`, `id_pedido`, `estado_anterior`, `estado_nuevo`, `fecha_cambio`, `id_admin_cambio`, `notas`) VALUES (36,39,NULL,'pendiente','2025-06-02 00:14:15',NULL,NULL),(37,39,'pendiente','confirmado','2025-06-02 00:14:24',NULL,NULL),(38,40,NULL,'pendiente','2025-06-02 01:34:32',NULL,NULL),(39,40,'pendiente','confirmado','2025-06-02 01:34:46',NULL,NULL);
/*!40000 ALTER TABLE `pedido_historial` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pedido_historial` with 4 row(s)
--

--
-- Table structure for table `productos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(11) NOT NULL,
  `id_sesion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_producto`),
  KEY `id_sesion` (`id_sesion`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`),
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `productos` (`id_producto`, `id_categoria`, `id_sesion`, `nombre`, `descripcion`, `codigo`, `fecha_registro`) VALUES (1,1,2,'Blusa “Encanto Volante”','Blusa Encanto Volante para mujer.','','2025-05-06 00:38:34'),(2,1,2,'Blusa “Asimétrica Chic”','Blusa Asimétrica Chic para mujer.','','2025-05-06 00:38:34'),(3,1,2,'Blusa “Línea Elegante”','Blusa Línea Elegante para mujer.','','2025-05-06 00:38:34'),(4,1,2,'Blusa “Corte Moderno”','Blusa Corte Moderno para mujer.','','2025-05-06 00:38:34'),(5,3,3,'Gorra “Shadow City”','Gorra Shadow City, estilo urbano unisex.','','2025-05-06 01:38:57'),(6,3,3,'Gorra “Estilo Vintage”','Gorra Estilo Vintage, look retro unisex.','','2025-05-06 01:38:57'),(7,3,3,'Gorra “Mood Rebel”','Gorra Mood Rebel, actitud desenfadada unisex.','','2025-05-06 01:38:57'),(8,3,3,'Gorra “Línea Clara”','Gorra Línea Clara, diseño minimalista unisex.','','2025-05-06 01:38:57'),(9,4,1,'Camisa “Botón Relax”','Camisa Botón Relax para hombre, comodidad y estilo.','','2025-05-06 01:38:57'),(10,4,1,'Camisa “Textura Urbana”','Camisa Textura Urbana para hombre, look moderno.','','2025-05-06 01:38:57'),(11,4,1,'Camisa “Estilo Casual”','Camisa Estilo Casual para hombre, versátil y cómoda.','','2025-05-06 01:38:57'),(12,4,1,'Camisa “Textura Serena”','Camisa Textura Serena para hombre, elegancia relajada.','','2025-05-06 01:38:57'),(13,2,1,'Short “Corte Ejecutivo”','Short Corte Ejecutivo para hombre, formalidad casual.','','2025-05-06 01:38:57'),(14,2,1,'Short “Smart Street”','Short Smart Street para hombre, estilo urbano inteligente.','','2025-05-06 01:38:57'),(15,2,1,'Short “Aventura Urbana”','Short Aventura Urbana para hombre, listo para la ciudad.','','2025-05-06 01:38:57'),(16,2,1,'Short “Cargo Urbano”','Short Cargo Urbano para hombre, funcionalidad y estilo.','','2025-05-06 01:38:57'),(17,2,2,'Shorts Estilo Noble','Shorts Estilo Noble para mujer, elegancia veraniega.','','2025-05-06 01:38:57'),(18,2,2,'Shorts Elegancia Suave','Shorts Elegancia Suave para mujer, comodidad sofisticada.','','2025-05-06 01:38:57'),(19,2,2,'Shorts Cintura Alta Belted','Shorts Cintura Alta Belted para mujer, define tu estilo.','','2025-05-06 01:38:57'),(20,2,2,'Shorts Casual Stretch','Shorts Casual Stretch para mujer, confort y movimiento.','','2025-05-06 01:38:57'),(21,4,2,'Camisa Esencia Satinada','Camisa Esencia Satinada para mujer, toque de lujo.','','2025-05-06 01:38:57'),(22,4,2,'Camisa Brisa Vintage','Camisa Brisa Vintage para mujer, encanto retro.','','2025-05-06 01:38:57'),(23,4,2,'Camisa Amanecer Casual','Camisa Amanecer Casual para mujer, estilo relajado.','','2025-05-06 01:38:57'),(24,4,2,'Camisa Estilo Profundo','Camisa Estilo Profundo para mujer, elegancia moderna.','','2025-05-06 01:38:57');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `productos` with 24 row(s)
--

--
-- Table structure for table `producto_imagen`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto_imagen` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_color_asociado` int(11) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id_imagen`),
  KEY `producto_imagen_ibfk_1` (`id_producto`),
  KEY `id_color_asociado` (`id_color_asociado`),
  CONSTRAINT `producto_imagen_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `producto_imagen_ibfk_2` FOREIGN KEY (`id_color_asociado`) REFERENCES `color_productos` (`id_color`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_imagen`
--

LOCK TABLES `producto_imagen` WRITE;
/*!40000 ALTER TABLE `producto_imagen` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `producto_imagen` (`id_imagen`, `id_producto`, `id_color_asociado`, `imagen`) VALUES (16,1,1,'images/blusas/lu1.png'),(17,1,2,'images/blusas/lu1-1.png'),(18,1,3,'images/blusas/lu1-2.png'),(19,2,2,'images/blusas/lu2.png'),(20,2,1,'images/blusas/lu2-1.png'),(21,2,4,'images/blusas/lu2-2.png'),(22,3,2,'images/blusas/lu3.png'),(23,3,1,'images/blusas/lu3-1.png'),(24,3,3,'images/blusas/lu3-2.png'),(25,4,1,'images/blusas/lu4.png'),(26,4,2,'images/blusas/lu4-1.png'),(27,4,5,'images/blusas/lu4-2.png'),(28,21,2,'images/camisa_damas/cda1.png'),(29,21,1,'images/camisa_damas/cda1-1.png'),(30,21,3,'images/camisa_damas/cda1-2.png'),(31,22,4,'images/camisa_damas/cda2.png'),(32,22,2,'images/camisa_damas/cda2-1.png'),(33,22,1,'images/camisa_damas/cda2-2.png'),(34,23,8,'images/camisa_damas/cda3.png'),(35,23,2,'images/camisa_damas/cda3-1.png'),(36,23,1,'images/camisa_damas/cda3-2.png'),(37,24,2,'images/camisa_damas/cda4.png'),(38,24,1,'images/camisa_damas/cda4-1.png'),(39,24,3,'images/camisa_damas/cda4-2.png'),(40,9,2,'images/camisa_hombre/sa1.png'),(41,9,1,'images/camisa_hombre/sa1-1.png'),(42,9,3,'images/camisa_hombre/sa1-2.png'),(43,10,3,'images/camisa_hombre/sa2.png'),(44,10,2,'images/camisa_hombre/sa2-1.png'),(45,10,1,'images/camisa_hombre/sa2-2.png'),(46,11,7,'images/camisa_hombre/sa3.png'),(47,11,1,'images/camisa_hombre/sa3-1.png'),(48,11,2,'images/camisa_hombre/sa3-2.png'),(49,12,8,'images/camisa_hombre/sa4.png'),(50,12,1,'images/camisa_hombre/sa4-1.png'),(51,12,2,'images/camisa_hombre/sa4-2.png'),(52,5,1,'images/gorras/rra1.png'),(53,5,2,'images/gorras/rra1-1.png'),(54,5,6,'images/gorras/rra1-2.png'),(55,6,1,'images/gorras/rra2.png'),(56,6,2,'images/gorras/rra2-1.png'),(57,6,9,'images/gorras/rra2-2.png'),(58,7,1,'images/gorras/rra3.png'),(59,7,2,'images/gorras/rra3-1.png'),(60,7,3,'images/gorras/rra3-2.png'),(61,8,1,'images/gorras/rra4.png'),(62,8,2,'images/gorras/rra4-1.png'),(63,8,8,'images/gorras/rra4-2.png'),(64,17,1,'images/short_damas/sda1.png'),(65,17,2,'images/short_damas/sda1-1.png'),(66,17,6,'images/short_damas/sda1-2.png'),(67,18,2,'images/short_damas/sda2.png'),(68,18,1,'images/short_damas/sda2-1.png'),(69,18,8,'images/short_damas/sda2-2.png'),(70,19,2,'images/short_damas/sda3.png'),(71,19,1,'images/short_damas/sda3-1.png'),(72,19,3,'images/short_damas/sda3-2.png'),(73,20,2,'images/short_damas/sda4.png'),(74,20,1,'images/short_damas/sda4-1.png'),(75,20,3,'images/short_damas/sda4-2.png'),(76,13,3,'images/shorts_hombre/sh1.jpg'),(77,13,1,'images/shorts_hombre/sh1-1.jpg'),(78,13,5,'images/shorts_hombre/sh1-2.jpg'),(79,14,1,'images/shorts_hombre/sh2.jpg'),(80,14,3,'images/shorts_hombre/sh2-1.jpg'),(81,14,2,'images/shorts_hombre/sh2-2.jpg'),(82,15,3,'images/shorts_hombre/sh3.jpg'),(83,15,1,'images/shorts_hombre/sh3-1.jpg'),(84,15,5,'images/shorts_hombre/sh3-2.jpg'),(85,16,3,'images/shorts_hombre/sh4.jpg'),(86,16,1,'images/shorts_hombre/sh4-1.jpg'),(87,16,5,'images/shorts_hombre/sh4-2.jpg');
/*!40000 ALTER TABLE `producto_imagen` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `producto_imagen` with 72 row(s)
--

--
-- Table structure for table `proveedores`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(100) NOT NULL,
  `nombre_contacto` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `nit_o_ruc` varchar(50) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_proveedor`),
  UNIQUE KEY `nit_o_ruc` (`nit_o_ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `proveedores` with 0 row(s)
--

--
-- Table structure for table `sesiones`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sesiones` (
  `id_sesion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  PRIMARY KEY (`id_sesion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sesiones` (`id_sesion`, `nombre`) VALUES (1,'Hombre'),(2,'Mujer'),(3,'Unisex');
/*!40000 ALTER TABLE `sesiones` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sesiones` with 3 row(s)
--

--
-- Table structure for table `talla_productos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talla_productos` (
  `id_talla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_talla` varchar(10) NOT NULL,
  PRIMARY KEY (`id_talla`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talla_productos`
--

LOCK TABLES `talla_productos` WRITE;
/*!40000 ALTER TABLE `talla_productos` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `talla_productos` (`id_talla`, `nombre_talla`) VALUES (1,'S'),(2,'M'),(3,'L'),(4,'XL'),(5,'Única');
/*!40000 ALTER TABLE `talla_productos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `talla_productos` with 5 row(s)
--

--
-- Table structure for table `usuario`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contraseña` varchar(280) NOT NULL,
  `fecha_de_cumpleaños` date NOT NULL,
  `proveedor_registro` varchar(50) NOT NULL,
  `proveedor_id` int(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rol` enum('cliente') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `usuario` (`id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `correo`, `contraseña`, `fecha_de_cumpleaños`, `proveedor_registro`, `proveedor_id`, `fecha_registro`, `rol`) VALUES (7,'eddy','','moreno','moreno','melanymorenom@gmail.com','$2y$10$yxjzpdIm31lyNvZHE6wtEOk9J4aHazikp1k2klHjJNpwzCE3tuEOC','0000-00-00','',0,'2025-06-01 16:54:13','cliente'),(8,'Melany','','Moreno','','melanymorenom@gmail.com','','0000-00-00','google',2147483647,'2025-05-27 06:44:07','cliente'),(22,'luz','','moreno','','melanymorenom@gmail.com','$2y$10$gat3ty4xyZXx4JObewJ0SejbsCLBVcP5upkpsI0BAemsXr9Ky7ZdC','0000-00-00','',0,'2025-06-02 01:34:32','cliente');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `usuario` with 3 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Mon, 02 Jun 2025 20:10:54 +0200
