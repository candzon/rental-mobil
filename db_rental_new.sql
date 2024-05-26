-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_rental.booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id_booking` int NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(255) NOT NULL,
  `id_login` int NOT NULL,
  `id_supir` int NOT NULL,
  `id_mobil` int NOT NULL,
  `ktp` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_tlp` varchar(15) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `lama_sewa` int NOT NULL,
  `total_harga` int NOT NULL,
  `konfirmasi_pembayaran` varchar(255) NOT NULL,
  `tgl_input` varchar(255) NOT NULL,
  PRIMARY KEY (`id_booking`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.booking: ~3 rows (approximately)
REPLACE INTO `booking` (`id_booking`, `kode_booking`, `id_login`, `id_supir`, `id_mobil`, `ktp`, `nama`, `alamat`, `no_tlp`, `tanggal`, `lama_sewa`, `total_harga`, `konfirmasi_pembayaran`, `tgl_input`) VALUES
	(1, '1716629125', 4, 1, 6, '98310238109', 'Heni', 'jaskldjas', '821903819083', '2024-05-25', 1, 500247, 'Sedang di proses', '2024-05-25'),
	(2, '1716629443', 4, 0, 6, '98120381', 'Heni', 'jasldka', '9812810', '2024-05-26', 7, 3500386, 'Sedang di proses', '2024-05-25');

-- Dumping structure for table db_rental.infoweb
CREATE TABLE IF NOT EXISTS `infoweb` (
  `id` int NOT NULL,
  `nama_rental` varchar(255) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text,
  `email` varchar(255) DEFAULT NULL,
  `no_rek` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.infoweb: ~0 rows (approximately)
REPLACE INTO `infoweb` (`id`, `nama_rental`, `telp`, `alamat`, `email`, `no_rek`, `updated_at`) VALUES
	(1, 'MobilSerbaRent', '081298669897', 'Ujung Harapan Kab. Bekasi', 'mobilserbarent@gmail.com', 'BRI A/N MobilSerba Rent <b>45182918</b>', '2022-01-24 04:57:29');

-- Dumping structure for table db_rental.login
CREATE TABLE IF NOT EXISTS `login` (
  `id_login` int NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  PRIMARY KEY (`id_login`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.login: ~2 rows (approximately)
REPLACE INTO `login` (`id_login`, `nama_pengguna`, `username`, `password`, `level`) VALUES
	(1, 'Admin', 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'admin'),
	(3, 'Budi', 'budi', 'fe01ce2a7fbac8fafaed7c982a04e229', 'pengguna'),
	(4, 'Heni', 'heni', 'fe01ce2a7fbac8fafaed7c982a04e229', 'pengguna');

-- Dumping structure for table db_rental.mobil
CREATE TABLE IF NOT EXISTS `mobil` (
  `id_mobil` int NOT NULL AUTO_INCREMENT,
  `no_plat` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  PRIMARY KEY (`id_mobil`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.mobil: ~2 rows (approximately)
REPLACE INTO `mobil` (`id_mobil`, `no_plat`, `merk`, `harga`, `deskripsi`, `status`, `gambar`) VALUES
	(5, 'N34234', 'Avanza', 200000, 'Apa aja', 'Tidak Tersedia', '1673593078toyota-all-new-avanza-2015-tangkapan-layar_169.jpeg'),
	(6, 'N 1232 BKT', 'New Xenia', 500000, 'Baru', 'Tersedia', 'all-new-xenia-exterior-tampak-perspektif-depan---varian-1.5r-ads.jpg');

-- Dumping structure for table db_rental.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_booking` int NOT NULL,
  `metode` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'NULL',
  `no_rekening` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'NULL',
  `nama_rekening` varchar(255) NOT NULL,
  `nominal` int NOT NULL,
  `tanggal` timestamp NOT NULL,
  PRIMARY KEY (`id_pembayaran`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.pembayaran: ~3 rows (approximately)
REPLACE INTO `pembayaran` (`id_pembayaran`, `id_booking`, `metode`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) VALUES
	(1, 1, 'Bank Transfer', '2131231', 'Heni', 500247, '2024-05-25 09:29:00'),
	(2, 2, 'Cash', 'NULL', 'Heni', 3500386, '2024-05-27 09:31:00');

-- Dumping structure for table db_rental.pengembalian
CREATE TABLE IF NOT EXISTS `pengembalian` (
  `id_pengembalian` int NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `denda` int NOT NULL,
  PRIMARY KEY (`id_pengembalian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table db_rental.pengembalian: ~0 rows (approximately)

-- Dumping structure for table db_rental.supir
CREATE TABLE IF NOT EXISTS `supir` (
  `id_supir` int NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_supir`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_rental.supir: ~0 rows (approximately)
REPLACE INTO `supir` (`id_supir`, `nama`) VALUES
	(1, 'Suyatno');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
