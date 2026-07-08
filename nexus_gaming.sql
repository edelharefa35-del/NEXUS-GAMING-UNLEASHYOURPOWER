-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2026 at 03:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nexus gaming`
--

-- --------------------------------------------------------

--
-- Table structure for table `data produk`
--

CREATE TABLE `data produk` (
  `id_produk` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `spesifikasi` text NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT 'placeholder.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data produk`
--

INSERT INTO `data produk` (`id_produk`, `nama`, `spesifikasi`, `kategori`, `harga`, `stok`, `gambar`) VALUES
(1, 'Acer Nitro V16 AI', 'Intel Core 14th Gen atau AMD Ryzen Seri 8000/AI GPU Nvidia GeForce RTX 40-Series\r\n', 'Entry Level', 12999000, 50, 'images/Acer Nitro.png'),
(2, 'Asus Tuf Gaming A15', 'AMD Ryzen™ 7 7445HS Processor 3.2GHz NVIDIA® GeForce RTX™ 3050 Laptop GPU 4GB GDDR6', 'Entry Level', 11500000, 50, 'images/tuf_plastic_15_08_1.png'),
(3, 'MSI Raider 18 HX', 'Intel Core Ultra 9 285HX NVIDIA GeForce RTX 5080 (16GB GDDR7) / RTX 5090 64 GB DDR5 6400 MHz', 'Flagship', 81999000, 50, 'images/MSI Raider 18 HX.jpeg'),
(4, 'MSI Katana 15 HX B14WEK', 'Intel Core i7-14650HX (16 Cores, 24 Threads) NVIDIA GeForce RTX 5050 8GB VRAM RAM 16GB DDR5-5600', 'Mid-Range', 22499000, 50, 'images/MSI Katana-15-HX-B14WEK.png'),
(5, 'Lenovo Legion Slim 5', 'AMD Ryzen 7 7840HS / Ryzen 7 8845HS  NVIDIA GeForce RTX 4050 6GB atau RTX 4060 8GB VRAM RAM 16 GB DDR5 5600 MHz', 'Mid-Range', 25000000, 50, 'images/legion.png'),
(6, 'ASUS ROG Strix SCAR 16 (2025)', 'Intel Core Ultra 9 275HX NVIDIA GeForce RTX 5090 VRAM 24 GB RAM 64 GB DDR5', 'High Performance', 93969000, 50, 'images/Asus ROG.png'),
(7, 'Razer Blade 14 (2024)', 'AMD Ryzen 9 8945HS RTX 4070 (8GB GDDR6 VRAM) RAM 32GB DDR5-5600MHz', 'Premium', 57000000, 50, 'images/Razer Blade 18.png'),
(8, 'Dell Alienware M16 R2', 'Intel Core Ultra 7 155H  Nvidia GeForce RTX 4060 (8GB) RAM 32GB DDR5 5600MHz', 'High Performance', 55000000, 50, 'images/BPMFS2507281A1Y6J34.png'),
(9, 'HP OMEN MAX 16', 'AMD Ryzen AI 9 HX 375  NVIDIA GeForce RTX 5070 Ti (12GB GDDR7) 32 GB DDR5', 'Flagship', 50400000, 50, 'images/HP-HyperX-Omen-Max-16-1.jpeg'),
(10, 'ASUS ROG Zephyrus G16', 'Intel Core Ultra 9 285H NVIDIA GeForce RTX 5070 Ti 32GB LPDDR5X & 1TB hingga 2TB PCIe 4.0 NVMe M.2 SSD.', 'High Performance', 35000000, 50, 'images/ROG-removebg-preview.png'),
(11, 'MSI Titan GT77 HX', 'Intel Core i9-13980HX  NVIDIA GeForce RTX 4090', 'High Performance', 45000000, 50, 'MSI Raider 18 HX.jpeg'),
(12, 'Razer Blade 18', 'Intel Core i9-13980HX 64GB 2TB RTX4090 18′′ QHD+ W11H', 'High Performance', 42000000, 50, 'images/Razer Blade 18.png'),
(13, 'Alienware M18 R2', 'Intel Core i9-14900HX NVIDIA GeForce RTX 4090 16GB GDDR6 (175W TGP)RAM 64GB DDR5', 'High Performance', 33000000, 50, 'images/Alienware M18 R2.png'),
(14, 'Lenovo Legion Pro 7i', 'Intel Core Ultra 9 275HX NVIDIA GeForce RTX 5080 (16GB GDDR7)32GB DDR5-6400 (', 'High Performance', 31000000, 50, 'images/Lenovo Legion Pro 7i.png'),
(15, 'Acer Predator Helios 18', 'Intel Core Ultra 9 275HX NVIDIA GeForce RTX 5090 RAM 64GB DDR5 (2x32GB)', 'High Performance', 29000000, 50, 'Lenovo Legion Pro 7i.png');

-- --------------------------------------------------------

--
-- Table structure for table `data produk komponen pc`
--

CREATE TABLE `data produk komponen pc` (
  `no_produk` int(11) NOT NULL,
  `nama produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data produk komponen pc`
--

INSERT INTO `data produk komponen pc` (`no_produk`, `nama produk`, `harga`, `stok`, `gambar`) VALUES
(1, 'Motherboard TUF GAMING X570-PLUS (WI-FI)', 3600000, 50, 'images komponen pc/TUF GAMING X570-PLUS (WI-FI).png'),
(2, 'Motherboard MSI PRO H610M-S WIFI DDR4', 1440000, 50, 'images komponen pc/MSI-Pro-H610M-S-DDR4-Wifi-2-removebg-preview.png'),
(3, 'Power Suply  ASUS ROG Strix 1000W Platinum', 3100000, 50, 'images komponen pc/POWER_SUPPLY_PSU_ASUS_ROG_STRIX_BLACK_PCIE_5.1_ATX_3.1_1000W_80+_PLATINUM_FULL_MODULAR-removebg-preview.png'),
(4, 'Power Suply MSI MEG Ai1300P PCIE5', 4599000, 50, 'images komponen pc/MSI_ATX3.0_GEN5_Compatible_850W_80PLUS_GOLD_Full_Modular_ATX_Power_Supply_MPG_A850G_PCIE5_1pc-removebg-preview.png'),
(5, 'MSI MPG A1000G PCIE5 1000W 80+ Gold Power Supply.', 5039000, 50, 'images komponen pc/MSI_ATX3.0_GEN5_Compatible_850W_80PLUS_GOLD_Full_Modular_ATX_Power_Supply_MPG_A850G_PCIE5_1pc-removebg-preview.png'),
(6, 'Power Suply Kuroutoshikou KRPW-GA850W/90', 1000000, 50, 'images komponen pc/Kurouto_Shikou_80PLUS_GOLD_Certified_Full_Plug-in_ATX_Power_Supply__850W_Model__3-Year_Warranty__KRPW-GA850W_or_90+_4988755-057578_1pc-removebg-preview.png'),
(7, 'CPU Cooler Master Hyper 212 RGB Black Edition', 730000, 50, 'images komponen pc/Master Hyper 212 RGB Black Edition.png'),
(8, 'RAM Kingston FURY Beast DDR4 RGB Kapasitas 32GB Kit', 5999999, 50, 'images komponen pc/Memori_RGB_DDR4_Kingston_FURY__Beast___8GB-128GB_2666MT_or_s-3733MT_or_s_-_Kingston_Technology_nn-removebg-preview.png'),
(9, 'RAM Patriot Viper Elite 5 TUF Gaming Alliance RGB DDR5 Kapasitas 48GB', 4500000, 50, 'images komponen pc/Memory_Viper_Elite_5_TUF_Gaming_RGB_DDR5_RAM_32GB__2X16GB__6600MT_or_s_CL34_1.4v-removebg-preview.png'),
(10, 'RAM Laptop Samsung DDR3 / DDR3L 8GB SODIMM', 380000, 50, 'images komponen pc/RAM_LAPTOP_SAMSUNG_DDR4_8GB_3200_MHz_25600-removebg-preview.png'),
(11, 'Corsair Hydro Series H100i Pro RGB', 2500000, 50, 'images komponen pc/Corsair_Hydro_Series_H100i_Pro-removebg-preview.png'),
(12, 'Speaker Audioengine A2+', 6000000, 50, 'images komponen pc/audioengine-removebg-preview.png'),
(13, 'JBL Quantum 910 Wireless', 3740000, 50, 'images komponen pc/JBL_Quantum_910_Wireless_Gaming_Headset_Headphone_Quantum_910_910-removebg-preview.png'),
(14, 'Logitech G733 Lightspeed Wireless Gaming Headset ', 1949000, 50, 'images komponen pc/Logitech_G733_LIGHTSPEED_Wireless_Lightsync_RGB_Gaming_Headset-removebg-preview.png'),
(15, 'Mouse Logitech G402 Hyperion Fury', 499000, 50, 'images komponen pc/Logitech-G402-removebg-preview.png'),
(16, 'SSD MSI SPATIUM M460 1TB NVMe M.2 PCIe Gen4 x4', 2130000, 50, 'images komponen pc/SSD_MSI_SPATIUM_M460_PCIe_4.0_NVMe_M.2_1TB_Gen4x4_-_SSD_1TB_NVMe-removebg-preview.png'),
(17, 'SSD Samsung M.2 NVME 990 PRO 1TB PCIE 4.0 GEN 4x4', 6250000, 50, 'images komponen pc/SSD_Samsung_M.2_NVME_990_PRO_1TB_PCIE_4.0_GEN_4x4-removebg-preview.png'),
(18, 'Western Digital - WD BLACK SN850X NVMe SSD | M.2 NVMe Gen 4 x4 - 1TB', 3799000, 50, 'images komponen pc/Western Digital - WD BLACK SN850X NVMe SSD M.2 NVMe Gen 4 x4 - 1TB.png'),
(19, 'ASUS Dual GeForce RTX 5060 Ti OC Edition', 12795000, 50, 'images komponen pc/VGA_Card_ASUS_GeForce_RTX_5060_Ti_Dual_8GB_OC_GDDDR7-removebg-preview.png'),
(20, 'Gigabyte GeForce RTX 5080 Gaming OC 16G.', 69399000, 50, 'images komponen pc/VGA_Card_Gigabyte_GeForce_RTX_5080_GAMING_OC_16G_-_16GB_GDDR7-removebg-preview.png'),
(21, 'MSI GeForce RTX 5090 GAMING TRIO OC', 78000003, 50, 'images komponen pc/VGA_MSI_RTX_5090_GAMING_TRIO_OC_32GB_GDDR7-removebg-preview.png'),
(22, 'images/Motherboard ASUS ROG Strix B360-G Gaming ', 3100000, 50, 'images komponen pc/ROG_STRIX_B360-G_GAMING-removebg-preview.png'),
(23, 'Keyboard GAMEN Titan VI', 430000, 50, 'images komponen pc/GAMEN_Keyboard_Gaming_Mechanical_RGB_Titan_6_Gasket_Mount_99_Keys_Hot-Swappable_Silver_Switch_Linear_Ergonomic_Design_Original-removebg-preview.png'),
(24, 'HyperX Alloy Origins 60', 1549000, 50, 'images komponen pc/HyperX_Keyboard_Gaming_Alloy_Origins-removebg-preview.png'),
(25, 'Casing GameMax Edge Mini Casing PC Mid Tower Micro ATX', 360000, 50, 'images komponen pc/705e63f702de4cd3b57f5634edad4adc_tplv-aphluv4xwc-white-pad-v1_1600_1600-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `tipe_item` varchar(50) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id_metode` int(11) NOT NULL,
  `nama_metode` varchar(50) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_metode` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','berhasil','gagal') DEFAULT 'pending',
  `bukti` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_pesanan` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status_pesanan` enum('baru','proses','selesai','batal') DEFAULT 'baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Sabar123', 'edelharefa11@gmail.com', '$2y$10$gTN5Hz7FuSoFyRUyAfhJvOZs1LOndLqWtHuHEmqxfjh2ty6vZZc/.', '2026-06-24 02:08:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data produk`
--
ALTER TABLE `data produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `data produk komponen pc`
--
ALTER TABLE `data produk komponen pc`
  ADD PRIMARY KEY (`no_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id_metode`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_metode` (`id_metode`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data produk`
--
ALTER TABLE `data produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `data produk komponen pc`
--
ALTER TABLE `data produk komponen pc`
  MODIFY `no_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id_metode` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_metode`) REFERENCES `metode_pembayaran` (`id_metode`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
