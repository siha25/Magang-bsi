-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: pendidikan_ansuh
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anak_asuh`
--

DROP TABLE IF EXISTS `anak_asuh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anak_asuh` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asrama_id` bigint unsigned NOT NULL,
  `nomor_induk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenjang_pendidikan` enum('SD','SMP','SMA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anak_asuh_nomor_induk_unique` (`nomor_induk`),
  KEY `anak_asuh_asrama_id_foreign` (`asrama_id`),
  CONSTRAINT `anak_asuh_asrama_id_foreign` FOREIGN KEY (`asrama_id`) REFERENCES `asrama` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anak_asuh`
--

LOCK TABLES `anak_asuh` WRITE;
/*!40000 ALTER TABLE `anak_asuh` DISABLE KEYS */;
INSERT INTO `anak_asuh` VALUES (3,4,'AB-2026-001','Abid  Al Mubarok','L','Subang','2016-07-12','SD','4','2021-08-19',1,'Minus matanya','2026-11-10 22:05:05','2026-11-10 22:05:05');
/*!40000 ALTER TABLE `anak_asuh` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asrama`
--

DROP TABLE IF EXISTS `asrama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asrama` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_asrama` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_asrama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wilayah` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `penanggung_jawab` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_telepon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_operasional` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '08:00 - 17:00 WIB',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asrama_kode_asrama_unique` (`kode_asrama`),
  KEY `asrama_wilayah_index` (`wilayah`),
  KEY `asrama_status_aktif_index` (`status_aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asrama`
--

LOCK TABLES `asrama` WRITE;
/*!40000 ALTER TABLE `asrama` DISABLE KEYS */;
INSERT INTO `asrama` VALUES (4,'ASR-001','Asrama 01','Karawang','Jl. Panatayuda I No.15, Nagasari, Kec. Karawang Bar., Karawang, Jawa Barat 41314','Abi Drajat','081283517771',NULL,'08:00 - 17:00 WIB',1,'2026-11-03 23:35:57','2026-11-03 23:35:57'),(5,'ASR-002','Asrama 02','Purwakarta','Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211','Abi wawan','081312167152',NULL,'08:00 - 17:00 WIB',1,'2026-11-10 21:56:54','2026-11-10 21:57:28'),(6,'ASR-003','Asrama 03','Jakarta','Jl. Cilangkap Raya No.51, RT.6/RW.3, Cipayung, Kec. Cipayung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13840','Abi Asep','08115848086',NULL,'08:00 - 17:00 WIB',1,'2026-11-10 21:59:25','2026-11-10 21:59:25'),(7,'ASR-004','Asrama 04','Bekasi','Jalan Pandan Blok B.9 No, 5, Mekarsari, Kec. Tambun Sel., Kabupaten Bekasi, Jawa Barat 17510','Abi Jajat','081283517774',NULL,'08:00 - 17:00 WIB',1,'2026-11-10 22:01:31','2026-11-10 22:01:31');
/*!40000 ALTER TABLE `asrama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asrama_code_counters`
--

DROP TABLE IF EXISTS `asrama_code_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asrama_code_counters` (
  `id` tinyint unsigned NOT NULL,
  `last_number` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asrama_code_counters`
--

LOCK TABLES `asrama_code_counters` WRITE;
/*!40000 ALTER TABLE `asrama_code_counters` DISABLE KEYS */;
INSERT INTO `asrama_code_counters` VALUES (1,4);
/*!40000 ALTER TABLE `asrama_code_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dokumentasi_kegiatan`
--

DROP TABLE IF EXISTS `dokumentasi_kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dokumentasi_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan_id` bigint unsigned NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_asli` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ukuran_byte` int unsigned NOT NULL DEFAULT '0',
  `urutan` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dokumentasi_kegiatan_kegiatan_id_urutan_index` (`kegiatan_id`,`urutan`),
  CONSTRAINT `dokumentasi_kegiatan_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan_harian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dokumentasi_kegiatan`
--

LOCK TABLES `dokumentasi_kegiatan` WRITE;
/*!40000 ALTER TABLE `dokumentasi_kegiatan` DISABLE KEYS */;
INSERT INTO `dokumentasi_kegiatan` VALUES (1,3,'dokumentasi-kegiatan/Ihh4LbEjGmKacHWqlF7hGdMBiwJQyvl97F8GlDYM.jpg','WhatsApp Image 2026-11-02 at 14.04.25.jpeg',265474,0,'2026-11-11 22:17:21','2026-11-11 22:17:21');
/*!40000 ALTER TABLE `dokumentasi_kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluasi_diniyah`
--

DROP TABLE IF EXISTS `evaluasi_diniyah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluasi_diniyah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anak_asuh_id` bigint unsigned NOT NULL,
  `asrama_id` bigint unsigned DEFAULT NULL,
  `jenis_evaluasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_evaluasi` date DEFAULT NULL,
  `nilai` decimal(5,2) NOT NULL DEFAULT '0.00',
  `predikat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_pengaju` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disetujui',
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `catatan_evaluasi_pusat` text COLLATE utf8mb4_unicode_ci,
  `status_tindak_lanjut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'belum_ditindaklanjuti',
  `diajukan_oleh` bigint unsigned DEFAULT NULL,
  `diajukan_pada` timestamp NULL DEFAULT NULL,
  `dinilai_oleh` bigint unsigned DEFAULT NULL,
  `dinilai_pada` timestamp NULL DEFAULT NULL,
  `bulan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluasi_diniyah_anak_asuh_id_foreign` (`anak_asuh_id`),
  KEY `evaluasi_diniyah_asrama_id_foreign` (`asrama_id`),
  KEY `evaluasi_diniyah_diajukan_oleh_foreign` (`diajukan_oleh`),
  KEY `evaluasi_diniyah_dinilai_oleh_foreign` (`dinilai_oleh`),
  CONSTRAINT `evaluasi_diniyah_anak_asuh_id_foreign` FOREIGN KEY (`anak_asuh_id`) REFERENCES `anak_asuh` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluasi_diniyah_asrama_id_foreign` FOREIGN KEY (`asrama_id`) REFERENCES `asrama` (`id`) ON DELETE SET NULL,
  CONSTRAINT `evaluasi_diniyah_diajukan_oleh_foreign` FOREIGN KEY (`diajukan_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `evaluasi_diniyah_dinilai_oleh_foreign` FOREIGN KEY (`dinilai_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluasi_diniyah`
--

LOCK TABLES `evaluasi_diniyah` WRITE;
/*!40000 ALTER TABLE `evaluasi_diniyah` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluasi_diniyah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatan_harian`
--

DROP TABLE IF EXISTS `kegiatan_harian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegiatan_harian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asrama_id` bigint unsigned NOT NULL,
  `tanggal_kegiatan` date NOT NULL,
  `kategori` enum('belajar','muhadhoroh','olahraga','acara_donatur','bersih_bersih') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `jumlah_peserta` int unsigned NOT NULL DEFAULT '0',
  `catatan_tambahan` text COLLATE utf8mb4_unicode_ci,
  `foto_dokumentasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penginput_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kegiatan_harian_penginput_id_foreign` (`penginput_id`),
  KEY `kegiatan_harian_asrama_id_tanggal_kegiatan_index` (`asrama_id`,`tanggal_kegiatan`),
  KEY `kegiatan_harian_kategori_index` (`kategori`),
  CONSTRAINT `kegiatan_harian_asrama_id_foreign` FOREIGN KEY (`asrama_id`) REFERENCES `asrama` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kegiatan_harian_penginput_id_foreign` FOREIGN KEY (`penginput_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan_harian`
--

LOCK TABLES `kegiatan_harian` WRITE;
/*!40000 ALTER TABLE `kegiatan_harian` DISABLE KEYS */;
INSERT INTO `kegiatan_harian` VALUES (1,4,'2026-11-04','belajar','Belajar fikih wudhu','08:00:00','09:30:00','hadir semua',9,NULL,NULL,6,'2026-11-03 23:55:41','2026-11-03 23:55:41'),(2,4,'2026-11-11','acara_donatur','Acara di luar asrama','18:00:00','21:30:00',NULL,0,NULL,'kegiatan-harian/vE09JddXjCzRY374ATZRmxlU1DKC7vtipfYThlDs.jpg',6,'2026-11-10 22:22:39','2026-11-10 22:22:39'),(3,4,'2026-11-12','olahraga','Olahraga badminton','08:00:00','09:30:00','mereka di ajak main badminton bersama donatur',8,NULL,NULL,6,'2026-11-11 22:17:21','2026-11-11 22:17:21');
/*!40000 ALTER TABLE `kegiatan_harian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_14_070000_create_asrama_table',1),(5,'2026_10_15_155522_create_anak_asuh_table',1),(6,'2026_10_19_093447_add_role_and_asrama_id_to_users_table',1),(7,'2026_10_20_070642_migrate_user_roles_to_new_format',1),(8,'2026_10_20_071226_create_evaluasi_diniyah_table',1),(9,'2026_10_20_082000_update_user_roles_to_final_format',1),(10,'2026_10_20_083000_add_workflow_fields_to_evaluasi_diniyah_table',1),(11,'2026_10_21_000001_create_kegiatan_harian_table',1),(12,'2026_10_21_045154_create_asrama_code_counters_table',1),(13,'2026_10_21_071850_add_primary_admin_marker_to_users_table',1),(14,'2026_10_29_084118_add_encrypted_password_to_users_table',1),(15,'2026_10_29_092834_add_fields_to_evaluasi_diniyah_table',1),(16,'2026_11_03_050031_add_catatan_pusat_to_evaluasi_diniyah_table',2),(17,'2026_11_05_142416_add_profile_and_settings_fields_to_asrama_and_users_tables',2),(18,'2026_11_06_024038_add_preference_columns_to_users_table',3),(19,'2026_11_11_051520_add_foto_dokumentasi_to_kegiatan_harian_table',4),(20,'2026_11_11_061404_add_user_preferences_to_users',5),(21,'2026_11_12_043442_create_dokumentasi_kegiatan_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('sbR0ZaGKF3vQvhmeVVy4gxSUhv4vnBi33yFE90nk',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','eyJfdG9rZW4iOiIweHNJcjVEOGhxME9hUmdCcWg4dXplanBLTXFCdVRta3Vrc3pKbk90IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2NhYmFuZ1wvaGFmYWxhbi1oYWRpc3QiLCJyb3V0ZSI6ImNhYmFuZy5oYWZhbGFuLWhhZGlzdC5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6NiwicGFzc3dvcmRfaGFzaF93ZWIiOiI2MjJiYzEwOTQ2NDAyNTZjMGQ1ZDZlOTQ3N2Q5YWRhOGVmYjkyNTk1ZjI3MWEzM2ViNzczNGNmMjQyNzBmNzU5IiwiY2FiYW5nX2lkIjo0fQ==',1794466995);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary_admin` tinyint(1) NOT NULL DEFAULT '0',
  `asrama_id` bigint unsigned DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id',
  `theme` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `date_format` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'd/m/Y',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encrypted_password` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_asrama_id_foreign` (`asrama_id`),
  KEY `users_role_status_aktif_index` (`role`,`status_aktif`),
  CONSTRAINT `users_asrama_id_foreign` FOREIGN KEY (`asrama_id`) REFERENCES `asrama` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Siti Hasanah','Adminpusat1@gmail.com','admin_pusat',1,NULL,1,'id','light','Asia/Jakarta','d/m/Y','2026-10-29 03:10:56','$2y$12$8GuD/F/6JfcxqPS99h8A4uekvZ1Tlnbk2/pV3WtbL8VZAmWlBvLBe','eyJpdiI6ImZ4R0VMMmxUUkltYnVwNEJFZGwwSXc9PSIsInZhbHVlIjoiTS9qdFZua0tYc2Jkb2ZBT3M3OTBmVnJUSXNOaCt1SUE2alVtMUR3dzlrZz0iLCJtYWMiOiJkNTlhOTYxMGM4Nzk2MDI2YjVmYTE3ZGI2N2YyMjI3MmFhMWM5NmExNzM2NDNkNDEwZWIzNjJkYjMwNDI1MTcxIiwidGFnIjoiIn0=','RzIb3TBUmW3xhY8XOe4XyqTG7vbO6gxQh1bXEbB1aB78sGxcvfzvVOlfF9qj',NULL,'2026-10-29 03:10:56'),(5,'Admin Pusat Developer','admin.dev@simpena.test','admin_pusat',0,NULL,1,'id','light','Asia/Jakarta','d/m/Y','2026-10-29 03:10:56','$2y$12$QB9mihJN2J05g58UbO2WJuCGR1GAVcazkPPHXUEuEHlJ3ta2R/mk.','eyJpdiI6IjhiUG0zSERxMlRaQm9hc3Y2VDVKYlE9PSIsInZhbHVlIjoiZnd2RDVhYkRWYW5pWmtRQ1NnN0FOdGQ3MFBXYXRGM1RyMUh0NVN2T2ZPOD0iLCJtYWMiOiJmZGE0ZmM0ZGEyNDNjNTFlYmY2YjRlZTdjOTUzYTA0ODUzMzkyMmMzOWYzZjVjODY0YzEwMmVjNTIxNjA3MGM2IiwidGFnIjoiIn0=',NULL,'2026-10-29 03:10:56','2026-10-29 03:10:56'),(6,'Admin cabang 1','admcabang01@gmail.com','admin_cabang',0,4,1,'id','light','Asia/Jakarta','d/m/Y','2026-11-03 23:36:40','$2y$12$qdR.FwMHy/ESVrjyS4EfDeU71lxpnc4tIsFXvmv8HMntC4l1FrtAy','eyJpdiI6IlhpNHlmWWZkVzlUd0tnb1ZEakRBY1E9PSIsInZhbHVlIjoiRG02NldKMkIwRjJxaUdySWhra1dRdz09IiwibWFjIjoiYWFhMTQyMDRjNjAzMjcwM2Q4NmNhOWExY2E3N2IxNjBmOGE1ZjUzNTU2Y2UxMDFiOTk4NThmYmZlN2UzYmIwYSIsInRhZyI6IiJ9','TmmZz2iKwWb0MXIwFebUFXquCn3U0ysSKtpDZjBWNYbtfc91AEUfOPLtmIFr','2026-11-03 23:36:40','2026-11-03 23:36:40');
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

-- Dump completed on 2026-11-12 14:25:25
