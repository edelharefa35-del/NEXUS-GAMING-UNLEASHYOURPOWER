<?php
include 'config.php';
$result = $conn->query("SELECT * FROM `keranjang`");
$total_bayar = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS GAMING - Pembayaran</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        .cart-page { max-width: 800px; margin: 60px auto; padding: 20px; background: #0f0f0f; border: 1px solid #1a1a1a; }
        .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .cart-table th, .cart-table td { padding: 15px; border-bottom: 1px solid #1a1a1a; text-align: left; }
        .cart-table th { color: #ff003c; font-family: 'Orbitron'; font-size: 14px; }
        .pay-btn { width: 100%; background: #ff003c; color: white; border: none; padding: 15px; font-family: 'Orbitron'; font-weight: 700; cursor: pointer; text-transform: uppercase; transition: 0.3s; }
        .pay-btn:hover { background: #cc0030; box-shadow: 0 0 15px rgba(255,0,60,0.6); }
    </style>
</head>
<body>
    <header>
        <div class="logo" onclick="window.location.href='index.php'" style="cursor:pointer;">NEXUS GAMING</div>
    </header>

    <main class="cart-page">
        <h2 style="font-family:'Orbitron'; color:#fff; margin-bottom:20px; text-align:center;">RINGKASAN ORDER</h2>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): 
                        $total_bayar += intval($row['total_harga']);
                    ?>
                    <tr>
                        <td style="color:#fff;"><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td style="color:#00f0ff; font-weight:bold;"><?php echo $row['jumlah']; ?></td>
                        <td style="color:#ff003c; font-weight:bold;">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; color:#555;">Keranjang Anda Kosong.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="display:flex; justify-content:space-between; font-size:20px; font-family:'Orbitron'; margin-bottom:30px;">
            <span>TOTAL BAYAR:</span>
            <span style="color:#00ff66; font-weight:900;">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
        </div>

        <?php if($total_bayar > 0): ?>
            <button class="pay-btn" onclick="checkout()">BAYAR SEKARANG VIA QRIS / SALDO</button>
        <?php endif; ?>
    </main>

    <script src="script.js"></script>
</body>
</html>