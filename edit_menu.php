<?php
include 'function/auth.php';
checkRole(['admin']);
include 'function/connect.php';

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM tb_menu WHERE id_menu = $id");
$data = mysqli_fetch_assoc($query);
if (!$data) {
  header("Location: admin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Menu — KristyCrumbs</title>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background: #fdf9f0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .kc-card {
      background: #fff;
      border: 1px solid #ece8df;
      border-radius: 24px;
      max-width: 460px;
      width: 100%;
    }

    .kc-card-header {
      padding: 14px 20px;
      border-bottom: 1px solid #ece8df;
      font-size: 14px;
      font-weight: 800;
      color: #1c1c17;
      display: flex;
      align-items: center;
      gap: 8px;
      border-radius: 24px 24px 0 0;
    }

    .kc-card-header i {
      font-size: 18px;
      color: #964261;
    }

    .form-label {
      font-size: 10px;
      font-weight: 700;
      color: #534247;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .form-control,
    .form-select {
      border: 1.5px solid #ece8df;
      border-radius: 999px;
      font-size: 13px;
      background: #f1eee5;
      color: #1c1c17;
      transition: border-color 0.15s, background 0.15s;
    }

    .form-control:hover,
    .form-select:hover {
      border-color: #f48fb1;
      background: #fdf0f4;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #964261;
      box-shadow: 0 0 0 3px rgba(150, 66, 97, .12);
      background: #fff;
    }

    .form-select {
      border-radius: 999px;
      padding-left: 18px;
    }

    .btn-brown {
      background: #964261;
      color: #fff;
      border: 1.5px solid #964261;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
      padding: 10px 22px;
      transition: background 0.15s, color 0.15s, border-color 0.15s;
    }

    .btn-brown:hover {
      background: #fdf0f4;
      color: #964261;
      border-color: #f48fb1;
    }

    .btn-back {
      background: #fff;
      color: #534247;
      border: 1px solid #ece8df;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      padding: 7px 16px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: border-color 0.15s, background 0.15s;
    }

    .btn-back:hover {
      border-color: #f48fb1;
      background: #fdf0f4;
      color: #964261;
    }
  </style>
</head>

<body>
  <div class="kc-card">
    <div class="kc-card-header"><i class='bx bx-edit'></i> Edit Menu</div>
    <div class="p-4">
      <a href="admin.php" class="btn-back mb-4"><i class='bx bx-arrow-back'></i> Kembali</a>
      <form action="function/function_menu/update_menu.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id_menu'] ?>">
        <div class="mb-3">
          <label class="form-label">Nama Menu</label>
          <input type="text" name="nama_menu" class="form-control" value="<?= htmlspecialchars($data['nama_menu']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <select name="kategori" class="form-select" required>
            <option value="makanan" <?= $data['kategori'] == 'makanan' ? 'selected' : '' ?>>Makanan</option>
            <option value="minuman" <?= $data['kategori'] == 'minuman' ? 'selected' : '' ?>>Minuman</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Stok</label>
          <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" required>
        </div>
        <button type="submit" name="update_menu" class="btn-brown d-flex align-items-center gap-2">
          <i class='bx bx-save'></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</body>

</html>