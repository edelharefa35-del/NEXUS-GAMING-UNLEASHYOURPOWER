// Membuka / Menutup Sidebar Keranjang
function toggleCart() {
    document.getElementById('cartSidebar').classList.toggle('open');
    updateCartUI();
}

// Mengirim data ke backend menggunakan Fetch API (JSON)
function addToCart(id, name, price, type = 'produk') {
    fetch('cart_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'add',
            id_produk: id,
            tipe_item: type,
            nama_produk: name,
            harga: price
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            updateCartUI();
            
            // Animasi pop badge jika element tersedia di halaman
            const badge = document.getElementById('cartBadge');
            if (badge) {
                badge.style.animation = 'none';
                setTimeout(() => {
                    badge.style.animation = 'badgePop 0.5s ease-out';
                }, 10);
            }
            
            alert('🚀 ' + name + ' sukses masuk keranjang!');
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan sistem saat menghubungi server.');
    });
}

// Memperbarui UI Keranjang Sidebar tanpa reload
function updateCartUI() {
    fetch('cart_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'fetch' })
    })
    .then(res => res.json())
    .then(data => {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cart-count');
        const cartTotal = document.getElementById('cartTotal');
        const cartBadge = document.getElementById('cartBadge');
        
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = '';
            let totalQty = 0;

            data.items.forEach(item => {
                totalQty += parseInt(item.jumlah);
                cartItemsContainer.innerHTML += `
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <h4>${item.nama_produk}</h4>
                            <p>Qty: ${item.jumlah} × Rp ${parseInt(item.harga).toLocaleString('id-ID')}</p>
                        </div>
                        <div>
                            <span style="font-weight:bold; color:#00f0ff">Rp ${parseInt(item.total_harga).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;
            });
            
            if(cartCount) cartCount.innerText = data.items.length;
            if(cartBadge) cartBadge.innerText = totalQty;
            if(cartTotal) cartTotal.innerText = `Rp ${data.total.toLocaleString('id-ID')}`;
        }
    });
}

// Fungsi checkout transaksional
function checkout() {
    fetch('cart_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'checkout' })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert('SYSTEM NOTIFICATION: Checkout Sukses! Database telah disinkronisasi.');
            location.reload();
        }
    });
}