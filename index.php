<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// --- AUTO-CREATE FOLDER IMAGES & PLACEHOLDER (TANPA GD) ---
if (!is_dir('images')) {
    mkdir('images', 0777, true);
}
if (!file_exists('images/placeholder.jpg')) {
    // Buat file placeholder.jpg dengan data base64 (gambar SVG kecil)
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200">
        <rect width="300" height="200" fill="#2a2a2a"/>
        <text x="150" y="100" font-family="Arial" font-size="18" fill="#666" text-anchor="middle">No Image</text>
        <text x="150" y="130" font-family="Arial" font-size="12" fill="#444" text-anchor="middle">Placeholder</text>
    </svg>';
    file_put_contents('images/placeholder.jpg', $svg); // Simpan sebagai .jpg
}

// Ambil parameter filter
$kategori_filter = isset($_GET['category']) ? $_GET['category'] : 'ALL PRODUCTS';

// Query dengan prepared statement
if ($kategori_filter == 'ALL PRODUCTS') {
    $query_produk = "SELECT * FROM `data produk`";
    $stmt = $conn->prepare($query_produk);
} else {
    $query_produk = "SELECT * FROM `data produk` WHERE `kategori` = ?";
    $stmt = $conn->prepare($query_produk);
    $stmt->bind_param("s", $kategori_filter);
}
$stmt->execute();
$result_produk = $stmt->get_result();

// --- FUNGSI PENCARIAN GAMBAR CERDAS ---
function findImage($filename) {
    $base_path = 'images/';
    $default = $base_path . 'placeholder.jpg';
    
    if (empty($filename)) return $default;
    if (file_exists($filename)) return $filename;
    $full_path = $base_path . $filename;
    if (file_exists($full_path)) return $full_path;
    
    $info = pathinfo($filename);
    $name = $info['filename'];
    $ext = isset($info['extension']) ? $info['extension'] : '';
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'JPG', 'JPEG', 'PNG', 'GIF', 'WEBP'];
    
    foreach ($extensions as $e) {
        $test = $base_path . $name . '.' . $e;
        if (file_exists($test)) return $test;
    }
    if ($ext) {
        if (file_exists($name . '.' . $ext)) return $name . '.' . $ext;
        if (file_exists($base_path . $name . '.' . $ext)) return $base_path . $name . '.' . $ext;
    }
    // Case-insensitive scan
    if (is_dir($base_path)) {
        $files = scandir($base_path);
        $name_lower = strtolower($name);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $file_info = pathinfo($file);
            $file_name = $file_info['filename'];
            if (strtolower($file_name) == $name_lower) {
                return $base_path . $file;
            }
        }
    }
    return $default;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS GAMING STORE - Laptops</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .nav-links-group { display: flex; align-items: center; gap: 20px; }
        .cart-icon-wrapper { position: relative; font-size: 20px; color: #fff; text-decoration: none; transition: color 0.3s; }
        .cart-icon-wrapper:hover { color: #ff003c; }
        .cart-badge {
            position: absolute; top: -10px; right: -12px; background: #ff003c; color: #fff;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 50%;
            min-width: 18px; text-align: center;
        }
        
        .buttons-group { display: flex; gap: 8px; width: 100%; }
        
        .btn-cart {
            background: transparent; color: #ff003c; border: 1px solid #ff003c;
            padding: 10px; font-size: 13px; font-weight: 700; text-transform: uppercase;
            cursor: pointer; border-radius: 4px; transition: all 0.3s; width: 50%;
            display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-cart:hover { background: #ff003c; color: #fff; box-shadow: 0 0 10px rgba(255,0,60,0.5); transform: none; }
        
        .btn-buy { 
            background-color: #ffffff; color: #000000; border: none; 
            padding: 10px; font-size: 13px; font-weight: 700; text-transform: uppercase; 
            cursor: pointer; border-radius: 4px; transition: all 0.3s; width: 50%;
            display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-buy:hover { background-color: #ff003c; color: #ffffff; box-shadow: 0 0 10px rgba(255,0,60,0.5); }
        
        .empty-state { grid-column: 1/-1; text-align: center; padding: 60px 20px; background: #0f0f0f; border: 1px solid #1a1a1a; }
        .empty-state i { font-size: 50px; color: #333; margin-bottom: 20px; }
        .empty-state h3 { color: #888; margin-bottom: 10px; }
        .empty-state p { color: #555; }
        .image-container { background: #fff; padding: 10px; height: 220px; display: flex; justify-content: center; align-items: center; position: relative; }
        .image-container img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .badge-ribbon { position: absolute; top: 10px; right: -5px; background: #ff003c; color: #fff; font-size: 10px; font-weight: 700; padding: 4px 15px; text-transform: uppercase; transform: skewX(-15deg); box-shadow: -2px 2px 5px rgba(0,0,0,0.3); }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; }
        .product-card { background: #0f0f0f; border: 1px solid #1a1a1a; border-radius: 4px; overflow: hidden; transition: all 0.3s; display: flex; flex-direction: column; }
        .product-card:hover { border-color: #ff003c; box-shadow: 0 0 15px rgba(255,0,60,0.2); transform: translateY(-5px); }
        .product-info { padding: 25px; flex: 1; display: flex; flex-direction: column; }
        .prod-category { color: #ff003c; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .product-info h3 { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: #fff; }
        .specs { color: #888; font-size: 12px; line-height: 1.6; margin-bottom: 15px; height: 55px; overflow: hidden; }
        .stock-status { color: #00ff66; font-size: 12px; font-weight: 500; margin-bottom: 15px; }
        .qty-row { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 12px; color: #aaa; }
        .qty-input { background: #1a1a1a; border: 1px solid #333; color: #fff; width: 50px; text-align: center; padding: 3px; font-weight: 700; }
        
        .footer-action { display: flex; flex-direction: column; gap: 15px; border-top: 1px solid #1a1a1a; padding-top: 15px; margin-top: auto; }
        .price-row { display: flex; justify-content: space-between; align-items: center; }
        .price { color: #ff003c; font-size: 20px; font-weight: 700; }
        
        /* --- UPDATE: VIDEO BACKGROUND LAYOUT (TEXT DI TENGAH & TIMPA VIDEO) --- */
        .hero { 
            position: relative;
            height: 550px; 
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #1a1a1a;
        }
        
        /* Mengatur video agar pas memenuhi background penuh tanpa rusak rasionya */
        .hero-video-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        
        /* Gradasi gelap transparan agar tulisan di atas video lebih kontras dan terbaca */
        .hero::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.8) 100%);
            z-index: 1.5;
        }
        
        /* Konten Teks di Atas Video */
        .hero-content {
            position: relative;
            z-index: 2; /* Di atas lapisan video & overlay */
            max-width: 800px;
            width: 100%;
            text-align: center;
            padding: 0 20px;
        }
        .hero-content h1 { 
            font-family: 'Orbitron', sans-serif; 
            font-size: 52px; 
            font-weight: 900; 
            letter-spacing: 3px; 
            margin-bottom: 15px; 
            text-shadow: 0 0 25px rgba(255,0,60,0.7); 
        }
        .hero-content h1 span { color: #ff003c; }
        .hero-content p { font-size: 18px; color: #ccc; margin-bottom: 30px; font-weight: 400; }
        
        .btn-explore { background: linear-gradient(45deg, #ff003c, #b3002a); color: #fff; border: none; padding: 12px 35px; font-size: 14px; font-weight: 700; text-transform: uppercase; cursor: pointer; transform: skewX(-15deg); transition: all 0.3s; box-shadow: 0 4px 15px rgba(255,0,60,0.4); text-decoration: none; display: inline-block; }
        .btn-explore span { display: inline-block; transform: skewX(15deg); }
        .btn-explore:hover { background: linear-gradient(45deg, #ff3366, #ff003c); box-shadow: 0 4px 25px rgba(255,0,60,0.8); }
        .filter-container { background: #111; padding: 15px 0; display: flex; justify-content: center; gap: 15px; border-bottom: 1px solid #222; flex-wrap: wrap; }
        .filter-btn { background: transparent; color: #aaa; border: 1px solid #333; padding: 8px 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; cursor: pointer; transform: skewX(-15deg); transition: all 0.3s; }
        .filter-btn span { display: inline-block; transform: skewX(15deg); }
        .filter-btn:hover, .filter-btn.active { background: #ff003c; color: #fff; border-color: #ff003c; }
        main { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .section-title { text-align: center; font-family: 'Orbitron', sans-serif; font-size: 28px; letter-spacing: 2px; margin-bottom: 50px; text-transform: uppercase; position: relative; }
        .section-title::after { content: ''; display: block; width: 60px; height: 3px; background: #ff003c; margin: 10px auto 0; }
        .features-bar { background: #0a0a0a; max-width: 1200px; margin: 60px auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .feature-box { background: #0f0f0f; border: 1px solid #1a1a1a; padding: 25px; text-align: center; transition: border-color 0.3s; }
        .feature-box:hover { border-color: #ff003c; }
        .feature-box h4 { font-size: 15px; font-weight: 700; margin-bottom: 8px; color: #ffffff; }
        .feature-box p { font-size: 12px; color: #777; }
        footer { background: #000; border-top: 1px solid #1a1a1a; padding: 30px 20px; text-align: center; font-size: 11px; color: #555; }
        .social-container { margin-bottom: 20px; }
        .social-title { font-size: 13px; letter-spacing: 2px; color: #888; margin-bottom: 15px; }
        .social-icons { display: flex; justify-content: center; gap: 20px; }
        .social-icon { display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #0f0f0f; border: 1px solid #1a1a1a; color: #fff; font-size: 20px; text-decoration: none; border-radius: 4px; transform: skewX(-10deg); transition: all 0.3s; }
        .social-icon i { transform: skewX(10deg); }
        .social-icon:hover { transform: translateY(-5px) skewX(-10deg) scale(1.1); border-color: transparent; }
        .social-icon.facebook:hover { background: #1877f2; }
        .social-icon.instagram:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366); }
        .social-icon.youtube:hover { background: #ff0000; }
        .social-icon.discord:hover { background: #5865f2; }
        .footer-bottom { border-top: 1px solid #1a1a1a; padding-top: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="logo">NEXUS GAMING</div>
        <div class="nav-links-group">
            <nav>
                <a href="index.php" class="active">Home</a>
                <a href="index.php#products">Products</a>
                <a href="components.php">Components</a>
                <a href="#about">About</a>
                
                <?php if(isset($_SESSION['username'])): ?>
                    <span style="color:#00f0ff; font-weight:bold; font-size:14px; margin-left:15px;">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars(strtoupper($_SESSION['username'])); ?>
                    </span>
                    <a href="logout.php" style="color:#ff003c; margin-left:10px;"><i class="fas fa-sign-out-alt"></i> Out</a>
                <?php else: ?>
                    <a href="login.php" style="color:#ff003c; margin-left:15px;"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </nav>
            <a href="cart.php" class="cart-icon-wrapper">
                <i class="fas fa-shopping-cart"></i>
                <?php
                $cart_count = $conn->query("SELECT SUM(jumlah) as total FROM `keranjang`");
                $total_item = $cart_count ? ($cart_count->fetch_assoc()['total'] ?? 0) : 0;
                ?>
                <span class="cart-badge"><?php echo $total_item; ?></span>
            </a>
        </div>
    </header>

    <section class="hero">
        <video autoplay loop muted playsinline class="hero-video-bg">
            <source src="Outlast the Competition - ASUS TUF Gaming A15 ASUS.mp4" type="video/mp4">
            Browser Anda tidak mendukung elemen video HTML5.
        </video>
        
        <div class="hero-content">
            <h1>UNLEASH <span>YOUR POWER</span></h1>
            <p>Premium Gaming Laptops & PC Components</p>
            <a href="#products" class="btn-explore"><span>EXPLORE NOW</span></a>
        </div>
    </section>

    <div class="filter-container" id="products">
        <?php 
        $categories = ['ALL PRODUCTS', 'ENTRY LEVEL', 'MID RANGE', 'HIGH PERFORMANCE', 'PREMIUM', 'FLAGSHIP'];
        foreach ($categories as $cat) {
            $active = ($kategori_filter == $cat) ? 'active' : '';
            echo "<button class='filter-btn $active' onclick=\"location.href='index.php?category=".urlencode($cat)."#products'\"><span>$cat</span></button>";
        }
        ?>
    </div>

    <main>
        <h2 class="section-title">GAMING LAPTOPS</h2>
        <div class="products-grid">
            <?php if ($result_produk && $result_produk->num_rows > 0): ?>
                <?php while($row = $result_produk->fetch_assoc()): 
                    $id = $row['id_produk'] ?? 0;
                    $nama = $row['nama'] ?? 'Unknown';
                    $spesifikasi = $row['spesifikasi'] ?? 'No specs';
                    $kategori = $row['kategori'] ?? 'Uncategorized';
                    $harga = $row['harga'] ?? 0;
                    $stok = $row['stok'] ?? 0;
                    $gambar_db = $row['gambar'] ?? '';
                    $gambar_found = findImage($gambar_db);
                ?>
                <div class="product-card">
                    <div class="image-container">
                        <span class="badge-ribbon"><?php echo htmlspecialchars($kategori); ?></span>
                        <img src="<?php echo htmlspecialchars($gambar_found); ?>" 
                             alt="<?php echo htmlspecialchars($nama); ?>" 
                             loading="lazy"
                             onerror="this.src='images/placeholder.jpg'">
                    </div>
                    <div class="product-info">
                        <div class="prod-category"><?php echo htmlspecialchars($kategori); ?></div>
                        <h3><?php echo htmlspecialchars($nama); ?></h3>
                        <p class="specs"><?php echo htmlspecialchars($spesifikasi); ?></p>
                        <div class="stock-status">✅ Stok: <?php echo $stok; ?> unit</div>
                        <div class="qty-row">
                            <span>Jumlah:</span>
                            <input type="number" id="qty_<?php echo $id; ?>" class="qty-input" value="1" min="1" max="<?php echo $stok; ?>">
                        </div>
                        <div class="footer-action">
                            <div class="price-row">
                                <span class="price">Rp <?php echo number_format($harga, 0, ',', '.'); ?></span>
                            </div>
                            <div class="buttons-group">
                                <button class="btn-cart" onclick="cartAction(<?php echo $id; ?>, '<?php echo addslashes($nama); ?>', <?php echo $harga; ?>, 'add')">
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
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-laptop"></i>
                    <h3>Belum Ada Data Laptop</h3>
                    <p>Silakan tambahkan produk laptop melalui phpMyAdmin</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <section class="features-bar">
        <div class="feature-box"><h4>Free Shipping</h4><p>Gratis ongkir seluruh Indonesia</p></div>
        <div class="feature-box"><h4>Official Warranty</h4><p>Garansi resmi distributor</p></div>
        <div class="feature-box"><h4>7 Days Return</h4><p>Pengembalian dalam 7 hari</p></div>
        <div class="feature-box"><h4>24/7 Support</h4><p>Layanan customer service 24 jam</p></div>
    </section>

    <footer>
        <div class="social-container">
            <p class="social-title">CONNECT WITH THE NEXUS</p>
            <div class="social-icons">
                <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
                <a href="#" class="social-icon discord"><i class="fab fa-discord"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 NEXUS GAMING STORE. All rights reserved.</p>
            <p style="color: #333; font-size: 10px;">For The Gamers, By The Gamers</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
    function cartAction(id_produk, nama_produk, harga, type) {
        const qtyInput = document.getElementById('qty_' + id_produk);
        const jumlah = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

        fetch('cart_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'add',
                id_produk: id_produk,
                tipe_item: 'laptop',
                nama_produk: nama_produk,
                harga: harga,
                jumlah: jumlah
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (type === 'buy') {
                    window.location.href = 'cart.php';
                } else {
                    const badge = document.querySelector('.cart-badge');
                    if (badge) {
                        fetch('cart_handler.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'fetch' })
                        })
                        .then(res => res.json())
                        .then(cartData => {
                            let total = 0;
                            cartData.items.forEach(item => total += parseInt(item.jumlah));
                            badge.textContent = total;
                        });
                    }
                    alert('✅ ' + nama_produk + ' (x' + jumlah + ') ditambahkan ke keranjang!');
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
    </script>
</body>
</html>