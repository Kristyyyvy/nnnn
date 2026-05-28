<?php
$role  = $_SESSION['role'] ?? '';
$uname = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'KristyCrumbs' ?> - KristyCrumbs POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    * {
      font-family: sans-serif;
    }

    body {
      background: #faf5ee;
      margin: 0;
    }

    a {
      text-decoration: none;
    }

    .layout {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 190px;
      background: #fff;
      border-right: 1px solid #e8d5b8;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      height: 100vh;
    }

    .sb-brand {
      padding: 12px 14px;
      border-bottom: 1px solid #e8d5b8;
    }

    .sb-brand-name {
      font-size: 14px;
      font-weight: 700;
      color: #1c1007;
    }

    .sb-brand-name span {
      color: #92400e;
    }

    .sb-brand-sub {
      font-size: 10px;
      color: #a07850;
    }

    .sb-section {
      padding: 10px 14px 4px;
      font-size: 10px;
      color: #a07850;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .sb-nav {
      padding: 4px 8px;
    }

    .sb-link {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 10px;
      color: #5a3a1a;
      font-size: 12px;
      border-radius: 3px;
      margin-bottom: 1px;
    }

    .sb-link i {
      font-size: 14px;
    }

    .sb-link.active {
      background: #fde8cc;
      color: #7c3a0e;
      font-weight: 600;
    }

    .sb-foot {
      margin-top: auto;
      padding: 10px 14px;
      border-top: 1px solid #e8d5b8;
    }

    .sb-user-name {
      font-size: 12px;
      font-weight: 600;
      color: #1c1007;
    }

    .sb-user-role {
      font-size: 10px;
      color: #a07850;
      margin-bottom: 6px;
    }

    .btn-logout {
      display: inline-block;
      padding: 4px 10px;
      border: 1px solid #fca5a5;
      border-radius: 3px;
      color: #dc2626;
      font-size: 11px;
      font-weight: 600;
    }

    .main-wrap {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    .topbar {
      background: #fff;
      border-bottom: 1px solid #e8d5b8;
      padding: 8px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .topbar-title {
      font-size: 13px;
      font-weight: 700;
      color: #1c1007;
    }

    .topbar-sub {
      font-size: 10px;
      color: #a07850;
    }

    .page-content {
      padding: 16px;
    }

    .kc-card {
      background: #fff;
      border: 1px solid #e8d5b8;
      border-radius: 4px;
      margin-bottom: 14px;
    }

    .kc-card-header {
      padding: 8px 12px;
      border-bottom: 1px solid #e8d5b8;
      font-weight: 700;
      font-size: 12px;
      color: #1c1007;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .kc-card-header i {
      color: #92400e;
      font-size: 14px;
      margin-right: 4px;
    }

    .kc-card-body {
      padding: 12px;
    }

    .kc-table thead th {
      background: #fdf5ec;
      color: #7c3a0e;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .04em;
      padding: 6px 10px;
      border-bottom: 1px solid #e8d5b8;
    }

    .kc-table tbody td {
      padding: 7px 10px;
      border-bottom: 1px solid #fdf5ec;
      font-size: 12px;
      vertical-align: middle;
      color: #1c1007;
    }

    .kc-table tbody tr:last-child td {
      border-bottom: none;
    }

    .kc-badge {
      border-radius: 3px;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: 700;
      display: inline-block;
    }

    .kc-badge-brown {
      background: #fde8cc;
      color: #7c3a0e;
    }

    .kc-badge-yellow {
      background: #fef9c3;
      color: #854d0e;
    }

    .kc-badge-green {
      background: #dcfce7;
      color: #15803d;
    }

    .kc-badge-blue {
      background: #dbeafe;
      color: #1e40af;
    }

    .kc-badge-red {
      background: #fee2e2;
      color: #b91c1c;
    }

    .kc-badge-gray {
      background: #f1f5f9;
      color: #475569;
    }

    .btn-kc {
      background: #92400e;
      border: 1px solid #92400e;
      color: #fff;
      border-radius: 3px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-kc-sm {
      padding: 3px 8px;
      font-size: 11px;
    }

    .btn-kc-outline {
      background: #fff;
      border: 1px solid #e8d5b8;
      color: #5a3a1a;
      border-radius: 3px;
      padding: 3px 8px;
      font-size: 11px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-kc-danger {
      background: #fff;
      border: 1px solid #fca5a5;
      color: #dc2626;
      border-radius: 3px;
      padding: 3px 8px;
      font-size: 11px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .stat-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 14px;
    }

    .stat-box {
      background: #fff;
      border: 1px solid #e8d5b8;
      border-radius: 4px;
      padding: 10px 12px;
    }

    .stat-label {
      font-size: 10px;
      color: #a07850;
      text-transform: uppercase;
      letter-spacing: .04em;
      font-weight: 600;
      margin-bottom: 3px;
    }

    .stat-value {
      font-size: 20px;
      font-weight: 700;
      color: #92400e;
    }

    .stat-value.dark {
      color: #1c1007;
    }

    .stat-value.blue {
      color: #1d4ed8;
    }

    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: #5a3a1a;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #92400e;
      box-shadow: 0 0 0 .15rem rgba(146, 64, 14, .15);
    }
  </style>
</head>

<body>
  <div class="layout">

    <div class="sidebar">
      <div class="sb-brand">
        <div class="sb-brand-name">Kristy<span>Crumbs</span></div>
        <div class="sb-brand-sub">POS System</div>
      </div>

      <?php if ($role === 'admin' || $role === 'owner'): ?>
        <div class="sb-section">Admin</div>
        <div class="sb-nav">
          <a href="admin.php" class="sb-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"><i class='bx bx-home-alt'></i> Dashboard</a>
          <a href="admin.php?tab=menu" class="sb-link <?= ($active ?? '') === 'menu' ? 'active' : '' ?>"><i class='bx bx-dish'></i> Kelola Menu</a>
          <a href="admin.php?tab=user" class="sb-link <?= ($active ?? '') === 'user' ? 'active' : '' ?>"><i class='bx bx-group'></i> Kelola User</a>
          <a href="admin.php?tab=pesanan" class="sb-link <?= ($active ?? '') === 'pesanan' ? 'active' : '' ?>"><i class='bx bx-receipt'></i> Pesanan</a>
          <a href="admin.php?tab=meja" class="sb-link <?= ($active ?? '') === 'meja' ? 'active' : '' ?>"><i class='bx bx-table'></i> Kelola Meja</a>
          <a href="admin.php?tab=laporan" class="sb-link <?= ($active ?? '') === 'laporan' ? 'active' : '' ?>"><i class='bx bx-bar-chart-alt-2'></i> Laporan</a>
        </div>
      <?php endif; ?>

      <?php if ($role === 'kasir' || $role === 'owner'): ?>
        <div class="sb-section">Kasir</div>
        <div class="sb-nav">
          <a href="kasir.php" class="sb-link <?= ($active ?? '') === 'kasir' ? 'active' : '' ?>"><i class='bx bx-calculator'></i> Transaksi</a>
        </div>
      <?php endif; ?>

      <?php if ($role === 'dapur' || $role === 'owner'): ?>
        <div class="sb-section">Dapur</div>
        <div class="sb-nav">
          <a href="dapur.php" class="sb-link <?= ($active ?? '') === 'dapur' ? 'active' : '' ?>"><i class='bx bx-bowl-hot'></i> Antrian Masak</a>
        </div>
      <?php endif; ?>

      <?php if ($role === 'owner'): ?>
        <div class="sb-section">Owner</div>
        <div class="sb-nav">
          <a href="owner.php" class="sb-link <?= ($active ?? '') === 'owner' ? 'active' : '' ?>"><i class='bx bx-bar-chart-alt-2'></i> Laporan & Omzet</a>
        </div>
      <?php endif; ?>

      <div class="sb-foot">
        <div class="sb-user-name"><?= htmlspecialchars($uname) ?></div>
        <div class="sb-user-role"><?= ucfirst($role) ?></div>
        <a href="function/logout.php" class="btn-logout"><i class='bx bx-log-out'></i> Keluar</a>
      </div>
    </div>

    <div class="main-wrap">
      <div class="topbar">
        <div class="topbar-title"><?= $page_title ?? '' ?></div>
        <div class="topbar-sub"><?= date('l, d F Y') ?></div>
      </div>
      <div class="page-content">