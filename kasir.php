<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['kasir']);
include 'function/connect.php';

$page_title = 'Transaksi Kasir';
$active = 'kasir';
include '_layout.php';

$menus = mysqli_query($koneksi, "SELECT * FROM tb_menu WHERE stok > 0 ORDER BY kategori, nama_menu");
?>

<style>
  .kasir-wrap {
    display: grid;
    grid-template-columns: 1fr 220px;
    gap: 12px;
    margin-bottom: 14px;
  }

  .menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 8px;
  }

  .menu-item {
    background: #fff;
    border: 1px solid #e8d5b8;
    border-radius: 4px;
    padding: 8px;
    cursor: pointer;
  }

  .menu-item.dipilih {
    border-color: #92400e;
    background: #fdf5ec;
  }

  .menu-kat {
    font-size: 10px;
    color: #a07850;
    margin-bottom: 2px;
  }

  .menu-nm {
    font-size: 12px;
    font-weight: 600;
    color: #1c1007;
    margin-bottom: 2px;
  }

  .menu-harga {
    font-size: 12px;
    color: #92400e;
    font-weight: 700;
    margin-bottom: 6px;
  }

  .qty-wrap {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .qty-btn {
    width: 20px;
    height: 20px;
    border: 1px solid #e8d5b8;
    border-radius: 3px;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .qty-num {
    font-size: 12px;
    font-weight: 700;
    min-width: 14px;
    text-align: center;
  }

  .cart-row {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    padding: 4px 0;
    border-bottom: 1px solid #f5ede3;
  }

  .cart-row:last-child {
    border-bottom: none;
  }
</style>

<div class="kasir-wrap">

  <!-- Kiri: info pelanggan + menu -->
  <div>
    <div class="kc-card mb-3">
      <div class="kc-card-header"><i class='bx bx-user'></i> Info Pelanggan</div>
      <div class="kc-card-body">
        <div class="row g-2">
          <div class="col-8">
            <label class="form-label">Nama pelanggan</label>
            <input type="text" id="nama_pelanggan" class="form-control form-control-sm" placeholder="Nama pelanggan">
          </div>
          <div class="col-4">
            <label class="form-label">No. meja</label>
            <input type="number" id="no_meja" class="form-control form-control-sm" placeholder="0" min="1">
          </div>
        </div>
      </div>
    </div>

    <div class="kc-card">
      <div class="kc-card-header"><i class='bx bx-dish'></i> Pilih Menu</div>
      <div class="kc-card-body">
        <div class="menu-grid">
          <?php while ($m = mysqli_fetch_assoc($menus)): ?>
            <div class="menu-item"
              id="mc-<?= $m['id_menu'] ?>"
              data-id="<?= $m['id_menu'] ?>"
              data-nama="<?= htmlspecialchars($m['nama_menu']) ?>"
              data-harga="<?= $m['harga'] ?>"
              data-stok="<?= $m['stok'] ?>">
              <div class="menu-kat"><?= ucfirst($m['kategori']) ?></div>
              <div class="menu-nm"><?= htmlspecialchars($m['nama_menu']) ?></div>
              <div class="menu-harga">Rp <?= number_format($m['harga'], 0, ',', '.') ?></div>
              <div class="qty-wrap">
                <button class="qty-btn" onclick="ubahQty(<?= $m['id_menu'] ?>,-1,event)">−</button>
                <span class="qty-num" id="qty-<?= $m['id_menu'] ?>">0</span>
                <button class="qty-btn" style="background:#92400e;color:#fff;border-color:#92400e" onclick="ubahQty(<?= $m['id_menu'] ?>,1,event)">+</button>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Kanan: keranjang + bayar -->
  <div>
    <div class="kc-card" style="position:sticky;top:50px">
      <div class="kc-card-header"><i class='bx bx-cart'></i> Keranjang</div>
      <div class="kc-card-body">

        <div id="cart-list" style="min-height:40px;margin-bottom:8px">
          <div style="font-size:11px;color:#a07850;text-align:center;padding:10px 0">Belum ada item</div>
        </div>

        <div style="display:flex;justify-content:space-between;font-size:12px;font-weight:700;padding:6px 0;border-top:1px solid #e8d5b8;margin-bottom:8px">
          <span>Total</span>
          <span id="total-display" style="color:#92400e">Rp 0</span>
        </div>

        <label class="form-label">Uang bayar</label>
        <input type="number" id="uang-bayar" class="form-control form-control-sm mb-2" placeholder="0" oninput="hitungKembalian()">

        <div class="row g-1 mb-2">
          <div class="col-4"><button class="btn-kc-outline w-100" style="font-size:10px;justify-content:center" onclick="setNom(20000)">20rb</button></div>
          <div class="col-4"><button class="btn-kc-outline w-100" style="font-size:10px;justify-content:center" onclick="setNom(50000)">50rb</button></div>
          <div class="col-4"><button class="btn-kc-outline w-100" style="font-size:10px;justify-content:center" onclick="setNom(100000)">100rb</button></div>
        </div>

        <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:10px">
          <span style="color:#a07850">Kembalian</span>
          <span id="kembalian-display" style="font-weight:700;color:#1d4ed8">Rp 0</span>
        </div>

        <button class="btn-kc w-100 justify-content-center mb-1" onclick="prosesCheckout()">
          <i class='bx bx-check-circle'></i> Proses & Bayar
        </button>
        <button class="btn-kc-outline w-100 justify-content-center" onclick="clearCart()">
          <i class='bx bx-trash'></i> Bersihkan
        </button>

      </div>
    </div>
  </div>

</div>

<!-- Riwayat -->
<div class="kc-card">
  <div class="kc-card-header"><i class='bx bx-history'></i> Riwayat Pembayaran</div>
  <table class="kc-table w-100">
    <thead>
      <tr>
        <th>#</th>
        <th>Waktu</th>
        <th>Pelanggan</th>
        <th>Total</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $result = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE status_bayar='lunas' ORDER BY id_pesanan DESC LIMIT 15");
      if ($result) while ($r = mysqli_fetch_assoc($result)):
      ?>
        <tr>
          <td style="color:#a07850"><?= $no++ ?></td>
          <td style="font-size:11px;color:#a07850"><?= $r['tgl_pesanan'] ?></td>
          <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
          <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
          <td><span class="kc-badge kc-badge-green">Lunas</span></td>
          <td><a href="print_invoice.php?id=<?= $r['id_pesanan'] ?>" target="_blank" class="btn-kc-outline"><i class='bx bx-printer'></i> Struk</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
  let cart = {};

  function rp(n) {
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
  }

  function ubahQty(id, delta, e) {
    e.stopPropagation();
    const card = document.getElementById('mc-' + id);
    const stok = parseInt(card.dataset.stok);
    const nama = card.dataset.nama;
    const harga = parseInt(card.dataset.harga);
    if (!cart[id]) cart[id] = {
      id,
      nama,
      harga,
      qty: 0
    };
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
      delete cart[id];
    } else if (cart[id].qty > stok) {
      cart[id].qty = stok;
      alert('Stok tidak cukup!');
    }
    document.getElementById('qty-' + id).textContent = cart[id]?.qty || 0;
    card.classList.toggle('dipilih', !!(cart[id]?.qty > 0));
    renderCart();
  }

  function renderCart() {
    const list = document.getElementById('cart-list');
    const items = Object.values(cart);
    if (items.length === 0) {
      list.innerHTML = '<div style="font-size:11px;color:#a07850;text-align:center;padding:10px 0">Belum ada item</div>';
      document.getElementById('total-display').textContent = rp(0);
      hitungKembalian();
      return;
    }
    let total = 0,
      html = '';
    items.forEach(i => {
      const sub = i.harga * i.qty;
      total += sub;
      html += `<div class="cart-row"><span>${i.nama} ×${i.qty}</span><span style="color:#92400e;font-weight:600">${rp(sub)}</span></div>`;
    });
    list.innerHTML = html;
    document.getElementById('total-display').textContent = rp(total);
    hitungKembalian();
  }

  function hitungKembalian() {
    const total = Object.values(cart).reduce((s, i) => s + i.harga * i.qty, 0);
    const bayar = parseInt(document.getElementById('uang-bayar').value) || 0;
    const kem = bayar - total;
    const el = document.getElementById('kembalian-display');
    el.textContent = rp(Math.max(0, kem));
    el.style.color = kem < 0 ? '#dc2626' : '#1d4ed8';
  }

  function setNom(n) {
    document.getElementById('uang-bayar').value = n;
    hitungKembalian();
  }

  function clearCart() {
    cart = {};
    document.querySelectorAll('.menu-item').forEach(c => c.classList.remove('dipilih'));
    document.querySelectorAll('.qty-num').forEach(n => n.textContent = '0');
    document.getElementById('uang-bayar').value = '';
    renderCart();
  }

  async function prosesCheckout() {
    const nama = document.getElementById('nama_pelanggan').value.trim();
    const meja = parseInt(document.getElementById('no_meja').value) || 0;
    const bayar = parseInt(document.getElementById('uang-bayar').value) || 0;
    const items = Object.values(cart);
    const total = items.reduce((s, i) => s + i.harga * i.qty, 0);

    if (!nama) {
      alert('Nama pelanggan wajib diisi');
      return;
    }
    if (meja <= 0) {
      alert('Nomor meja tidak valid');
      return;
    }
    if (items.length === 0) {
      alert('Keranjang masih kosong');
      return;
    }
    if (bayar < total) {
      alert('Uang bayar kurang');
      return;
    }

    const fd = new FormData();
    fd.append('nama_pelanggan', nama);
    fd.append('no_meja', meja);
    fd.append('bayar', bayar);
    fd.append('cart', JSON.stringify(items));

    const res = await fetch('function/function_pesanan/checkout_kasir.php', {
      method: 'POST',
      body: fd
    });
    const data = await res.json();

    if (!data.ok) {
      alert('Gagal: ' + data.msg);
      return;
    }

    // Langsung buka struk 
    alert(`Berhasil!\nTotal: ${rp(data.total)}\nKembalian: ${rp(data.kembalian)}`);
    window.open('print_invoice.php?id=' + data.id_pesanan, '_blank');

    clearCart();
    document.getElementById('nama_pelanggan').value = '';
    document.getElementById('no_meja').value = '';
    location.reload();
  }
</script>

<?php include '_layout_end.php'; ?>