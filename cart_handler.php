<?php
include 'config.php';
session_start();

// Mengambil data input JSON dari Fetch API
$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

// Pastikan tabel keranjang ada di database
$conn->query("CREATE TABLE IF NOT EXISTS `keranjang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_produk` INT NOT NULL,
    `tipe_item` VARCHAR(50) NOT NULL,
    `nama_produk` VARCHAR(255) NOT NULL,
    `harga` INT NOT NULL,
    `jumlah` INT NOT NULL,
    `total_harga` INT NOT NULL
)");

$response = ['status' => 'error', 'message' => 'Aksi tidak valid'];

if ($action == 'add') {
    $id_produk   = intval($input['id_produk']);
    $tipe_item   = $conn->real_escape_string($input['tipe_item']);
    $nama_produk = $conn->real_escape_string($input['nama_produk']);
    $harga       = intval($input['harga']);
    $jumlah      = isset($input['jumlah']) ? intval($input['jumlah']) : 1;

    // Cek apakah produk tersebut sudah ada di keranjang belanja
    $check = $conn->query("SELECT * FROM `keranjang` WHERE `id_produk` = $id_produk AND `tipe_item` = '$tipe_item'");
    
    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $jumlah_baru = $row['jumlah'] + $jumlah;
        $total_baru  = $jumlah_baru * $harga;
        $update = $conn->query("UPDATE `keranjang` SET `jumlah` = $jumlah_baru, `total_harga` = $total_baru WHERE `id` = " . $row['id']);
    } else {
        $total_harga = $jumlah * $harga;
        $update = $conn->query("INSERT INTO `keranjang` (`id_produk`, `tipe_item`, `nama_produk`, `harga`, `jumlah`, `total_harga`) VALUES ($id_produk, '$tipe_item', '$nama_produk', $harga, $jumlah, $total_harga)");
    }

    if ($update) {
        $response = ['status' => 'success', 'message' => 'Item berhasil ditambahkan'];
    } else {
        $response = ['status' => 'error', 'message' => 'Gagal memperbarui database: ' . $conn->error];
    }
}

if ($action == 'fetch') {
    $result = $conn->query("SELECT * FROM `keranjang`");
    $items = [];
    $total_belanja = 0;

    while ($row = $result->fetch_assoc()) {
        $total_belanja += intval($row['total_harga']);
        $items[] = $row;
    }

    $response = [
        'status' => 'success',
        'items' => $items,
        'total' => $total_belanja
    ];
}

if ($action == 'checkout') {
    // Kosongkan keranjang saat bayar sukses
    $clear = $conn->query("TRUNCATE TABLE `keranjang`");
    if ($clear) {
        $response = ['status' => 'success', 'message' => 'Checkout berhasil'];
    } else {
        $response = ['status' => 'error', 'message' => 'Gagal mengosongkan keranjang'];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?>