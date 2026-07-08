<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$query_komponen = "SELECT * FROM `data produk komponen pc`";
$stmt = $conn->prepare($query_komponen);
$stmt->execute();
$result_komponen = $stmt->get_result();

// --- FUNGSI PARSING GAMBAR ADAPTIF KOMPONEN ---
$query_komponen = "SELECT * FROM `data produk komponen pc`";
$stmt = $conn->prepare($query_komponen);
$stmt->execute();
$result_komponen = $stmt->get_result();

// --- FUNGSI PENCARIAN GAMBAR ADAPTIF ---
function findImage($filename) {
    $base_path = 'images komponen pc/';
    $default = 'images/placeholder.jpg'; 
    
    if (empty($filename)) return $default;
    
    if (strpos(strtolower($filename), 'images/') === 0) {
        $filename = substr($filename, 7);
    } elseif (strpos(strtolower($filename), 'images\\') === 0) {
        $filename = substr($filename, 7);
    }
    if (strpos(strtolower($filename), 'images komponen pc/') === 0) {
        $filename = substr($filename, 19);
    }

    $full_path = $base_path . $filename;
    if (file_exists($full_path) && !is_dir($full_path)) {
        return $full_path;
    }
    
    if (is_dir($base_path)) {
        $files = scandir($base_path);
        $clean_target = strtolower(trim($filename));
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            if (strtolower(trim($file)) == $clean_target) {
                return $base_path . $file;
            }
        }
    }
    return $full_path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS GAMING STORE - PC Components</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .nav-links-group { display: flex; align-items: center; gap: 20px; }
        .cart-icon-wrapper { position: relative; font-size: 20px; color: #fff; text-decoration: none; cursor: pointer; }
        .cart-icon-wrapper:hover { color: #ff003c; }
        .cart-badge {
            position: absolute; top: -10px; right: -12px; background: #ff003c; color: #fff;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 50%;
            min-width: 18px; text-align: center;
        }
        .buttons-group { display: flex; gap: 8px; width: 100%; }
        
        /* Modifikasi tombol agar pas berdampingan */
        .btn-cart {
            background: transparent; color: #ff003c; border: 1px solid #ff003c;
            padding: 10px; font-size: 13px; font-weight: 700; text-transform: uppercase;
            cursor: pointer; border-radius: 4px; transition: all 0.3s; width: 50%;
            display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-cart:hover { background: #ff003c; color: #fff; box-shadow: 0 0 10px rgba(255,0,60,0.5); }
        
        /* Style tombol BUY bawaan style.css */
        .btn-buy {
            background-color: #ffffff; color: #000000; border: none;
            padding: 10px; font-size: 13px; font-weight: 700; text-transform: uppercase;
            cursor: pointer; border-radius: 4px; transition: all 0.3s; width: 50%;
            display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-buy:hover { background-color: #ff003c; color: #ffffff; box-shadow: 0 0 10px rgba(255,0,60,0.5); }

        .image-container { background: #fff; padding: 10px; height: 220px; display: flex; justify-content: center; align-items: center; position: relative; }
        .image-container img { max-height: 100%; max-width: 100%; object-fit: contain; display: block; }
        .badge-ribbon { position: absolute; top: 10px; right: -5px; background: #ff003c; color: #fff; font-size: 10px; font-weight: 700; padding: 4px 15px; text-transform: uppercase; transform: skewX(-15deg); }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; }
        .product-card { background: #0f0f0f; border: 1px solid #1a1a1a; border-radius: 4px; overflow: hidden; transition: all 0.3s; display: flex; flex-direction: column; }
        .product-card:hover { border-color: #ff003c; box-shadow: 0 0 15px rgba(255,0,60,0.2); transform: translateY(-5px); }
        .product-info { padding: 25px; flex: 1; display: flex; flex-direction: column; }
        .prod-category { color: #ff003c; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .product-info h3 { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: #fff; min-height: 50px; }
        .specs { color: #888; font-size: 12px; margin-bottom: 15px; height: 55px; }
        .stock-status { color: #00ff66; font-size: 12px; margin-bottom: 15px; }
        .qty-row { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 12px; color: #aaa; }
        .qty-input { background: #1a1a1a; border: 1px solid #333; color: #fff; width: 50px; text-align: center; padding: 3px; font-weight: 700; }
        .footer-action { display: flex; flex-direction: column; gap: 15px; border-top: 1px solid #1a1a1a; padding-top: 15px; }
        .price-row { display: flex; justify-content: space-between; align-items: center; }
        .price { color: #ff003c; font-size: 20px; font-weight: 700; }
        
        .hero { background: linear-gradient(180deg, rgba(14,2,6,0.85) 0%, rgba(0,0,0,0.98) 100%); height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .hero h1 { font-family: 'Orbitron', sans-serif; font-size: 36px; font-weight: 900; }
        .hero h1 span { color: #ff003c; }
        main { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .section-title { text-align: center; font-family: 'Orbitron', sans-serif; font-size: 28px; margin-bottom: 50px; }

        /* Memaksa keranjang samping tetap tersembunyi secara default */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px; 
            width: 350px;
            height: 100vh;
            background: #0f0f0f;
            border-left: 1px solid #1a1a1a;
            z-index: 2000;
            transition: right 0.3s ease;
            padding: 20px;
        }
        .cart-sidebar.open {
            right: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">NEXUS GAMING</div>
        <div class="nav-links-group">
            <nav style="display: flex; align-items: center; gap: 20px;">
                <a href="index.php">Home</a>
                <a href="index.php#products">Products</a>
                <a href="components.php" class="active">Components</a>
                <a href="index.php#about">About</a>

                <?php if(isset($_SESSION['username'])): ?>
                    <span style="color:#00f0ff; font-weight:bold; font-size:14px; display: inline-flex; align-items: center; gap: 5px; border-left: 1px solid #333; padding-left: 15px;">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars(strtoupper($_SESSION['username'])); ?>
                    </span>
                    <a href="logout.php" style="color:#ff003c; display: inline-flex; align-items: center; gap: 5px;"><i class="fas fa-sign-out-alt"></i> Out</a>
                <?php else: ?>
                    <a href="login.php" style="color:#ff003c; display: inline-flex; align-items: center; gap: 5px; border-left: 1px solid #333; padding-left: 15px;"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </nav>
            <div class="cart-icon-wrapper" onclick="toggleCart()">
                <i class="fas fa-shopping-cart"></i>
                <?php
                $cart_count = $conn->query("SELECT SUM(jumlah) as total FROM `keranjang`");
                $total_item = $cart_count ? ($cart_count->fetch_assoc()['total'] ?? 0) : 0;
                ?>
                <span class="cart-badge" id="cartBadge"><?php echo $total_item; ?></span>
            </div>
        </div>
    </header>

    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-sidebar-header">
            <h3>SHOPPING CART</h3>
            <button class="close-cart-btn" onclick="toggleCart()">×</button>
        </div>
        <div id="cartItems" class="cart-items-container"></div>
        <div class="cart-sidebar-footer">
            <div class="cart-total-row">
                <span>TOTAL:</span>
                <span id="cartTotal" class="cart-total-price">Rp 0</span>
            </div>
            <button class="checkout-btn" onclick="window.location.href='cart.php'">PROCEED TO PAY</button>
        </div>
    </div>

    <section class="hero">
        <h1>PC <span>COMPONENTS</span></h1>
    </section>

    <main>
        <h2 class="section-title">HARDWARE & PARTS</h2>
        <div class="products-grid">
            <?php if ($result_komponen && $result_komponen->num_rows > 0): ?>
                <?php while($row = $result_komponen->fetch_assoc()): 
                    $id = $row['no_produk'] ?? 0;
                    $nama = $row['nama produk'] ?? 'Unknown Component';
                    $harga = $row['harga'] ?? 0;
                    $stok = $row['stok'] ?? 0;
                    $gambar_found = findImage($row['gambar'] ?? '');
                ?>
                <div class="product-card">
                    <div class="image-container">
                        <span class="badge-ribbon">HARDWARE</span>
                        <img src="<?php echo htmlspecialchars($gambar_found); ?>" onerror="this.src='images/placeholder.jpg';">
                    </div>
                    <div class="product-info">
                        <div class="prod-category">COMPONENTS</div>
                        <h3><?php echo htmlspecialchars($nama); ?></h3>
                        <p class="specs">Premium Components for Maximum Performance Setup.</p>
                        <div class="stock-status">✅ Stok: <?php echo $stok; ?> unit</div>
                        <div class="qty-row">
                            <span>Jumlah:</span>
                            <input type="number" id="qty_comp_<?php echo $id; ?>" class="qty-input" value="1" min="1" max="<?php echo $stok; ?>">
                        </div>
                        <div class="footer-action">
                            <div class="price-row">
                                <span class="price">Rp <?php echo number_format($harga, 0, ',', '.'); ?></span>
                            </div>
                            <div class="buttons-group">
                                <button class="btn-cart" onclick="cartAction(<?php echo $id; ?>, '<?php echo addslashes($nama); ?>', <?php echo $harga; ?>, 'cart')">
                                    <i class="fas fa-shopping-cart"></i> CART
                                </button>
                                <button class="btn-buy" onclick="cartAction(<?php echo $id; ?>, '<?php echo addslashes($nama); ?>', <?php echo $harga; ?>, 'buy')">
                                    <i class="fas fa-bolt"></i> BUY
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="script.js"></script>
    <script>
    function cartAction(id_produk, nama_produk, harga, type) {
        const qtyInput = document.getElementById('qty_comp_' + id_produk);
        const jumlah = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

        fetch('cart_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'add',
                id_produk: id_produk,
                tipe_item: 'komponen',
                nama_produk: nama_produk,
                harga: harga,
                jumlah: jumlah
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (type === 'buy') {
                    // Jika tipe 'buy', langsung alihkan ke halaman cart pembayaran
                    window.location.href = 'cart.php';
                } else {
                    // Jika tipe 'cart', perbarui UI dan tampilkan sidebar keranjang samping
                    if (typeof updateCartUI === "function") {
                        updateCartUI();
                    }
                    const sidebar = document.getElementById('cartSidebar');
                    if (sidebar) {
                        sidebar.classList.add('open');
                    }
                }
            } else {
                alert('❌ Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan sistem.');
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof updateCartUI === "function") updateCartUI();
    });
    </script>
</body>
</html>