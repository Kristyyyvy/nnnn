<?php
include 'function/auth.php';
checkRole(['admin']);
include 'function/connect.php';

$id = $_GET['id'] ?? 0;
$query = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE id_user = '$id'");
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
  <title>Edit User — KristyCrumbs</title>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --brown: #92400e;
      --brown-dark: #78350f;
      --brown-light: #fde8cc;
      --cream: #faf5ee;
      --cream-white: #fffcf7;
      --border: #f5dfc0;
      --tx: #1c1007;
      --txm: #6b4c2a;
      --txl: #b08a62;
    }

    * {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background: var(--cream);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .kc-card {
      background: var(--cream-white);
      border: 1.5px solid var(--border);
      border-radius: 14px;
      box-shadow: 0 4px 20px rgba(146, 64, 14, .08);
      max-width: 460px;
      width: 100%;
    }

    .kc-card-header {
      padding: 14px 20px;
      border-bottom: 1.5px solid var(--border);
      font-size: 14px;
      font-weight: 800;
      color: var(--tx);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .kc-card-header i {
      font-size: 18px;
      color: var(--brown);
    }

    .form-label {
      font-size: 10px;
      font-weight: 700;
      color: var(--txm);
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 13px;
      background: var(--cream-white);
      color: var(--tx);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--brown);
      box-shadow: 0 0 0 3px rgba(146, 64, 14, .12);
      background: var(--cream-white);
    }

    .btn-brown {
      background: var(--brown);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 700;
      padding: 9px 20px;
      transition: .15s;
    }

    .btn-brown:hover {
      background: var(--brown-dark);
      color: #fff;
    }

    .btn-back {
      background: var(--cream-white);
      color: var(--txm);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      padding: 8px 16px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: .15s;
    }

    .btn-back:hover {
      background: var(--brown-light);
      color: var(--brown-dark);
    }

    .hint {
      font-size: 10px;
      color: var(--txl);
      font-weight: 400;
      text-transform: none;
      letter-spacing: 0;
    }
  </style>
</head>

<body>
  <div class="kc-card">
    <div class="kc-card-header"><i class='bx bx-user-check'></i> Edit User</div>
    <div class="p-4">
      <a href="admin.php" class="btn-back mb-4"><i class='bx bx-arrow-back'></i> Kembali</a>
      <form action="function/function_user/update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id_user'] ?>">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password <span class="hint">(kosongkan jika tidak ubah)</span></label>
          <input type="password" name="password" class="form-control" placeholder="Password baru...">
        </div>
        <div class="mb-4">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <?php foreach (['admin' => 'Admin', 'kasir' => 'Kasir', 'pelayanan' => 'Pelayanan', 'dapur' => 'Dapur'] as $v => $l): ?>
              <option value="<?= $v ?>" <?= $data['role'] === $v ? 'selected' : '' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" name="update_user" class="btn-brown d-flex align-items-center gap-2">
          <i class='bx bx-save'></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</body>

</html>