<?php
session_start();
if (isset($_SESSION['role'])) {
  header("Location: " . $_SESSION['role'] . ".php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KristyCrumbs POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    body {
      background: #faf5ee;
      font-family: sans-serif;
    }

    .card {
      border: 1px solid #e8d5b8;
      border-radius: 4px;
      background: #fff;
    }

    .btn-primary {
      background: #92400e;
      border-color: #92400e;
    }

    .btn-primary:hover,
    .btn-primary:focus {
      background: #78350f;
      border-color: #78350f;
    }

    .form-control:focus {
      border-color: #92400e;
      box-shadow: 0 0 0 .15rem rgba(146, 64, 14, .15);
    }

    .brand {
      color: #92400e;
    }

    .demo-chip {
      background: #fdf5ec;
      border: 1px solid #e8d5b8;
      border-radius: 4px;
      padding: 8px;
      text-align: center;
      font-size: 11px;
    }

    .demo-chip-role {
      font-weight: 700;
      margin-bottom: 2px;
    }
  </style>
</head>

<body>
  <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div style="width:100%;max-width:360px;padding:16px">
      <div class="text-center mb-4">
        <h4 class="fw-bold mb-0">Kristy<span class="brand">Crumbs</span></h4>
        <small class="text-muted">Point of Sale System</small>
      </div>
      <div class="card p-4 mb-3">
        <h6 class="fw-bold mb-3">Masuk ke Akun</h6>
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:13px">
            <i class='bx bx-error-circle'></i> Username atau password salah.
          </div>
        <?php endif; ?>
        <form method="POST" action="function/login.php">
          <div class="mb-2">
            <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Username</label>
            <input type="text" name="username" class="form-control form-control-sm" placeholder="Username" required>
          </div>
          <div class="mb-3">
            <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Password</label>
            <input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
          </div>
          <button type="submit" name="login" class="btn btn-primary btn-sm w-100">
            <i class='bx bx-log-in'></i> Masuk
          </button>
        </form>
      </div>

      <div class="card p-3">
        <div style="font-size:11px;font-weight:600;color:#a07850;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px;text-align:center">Demo Akun</div>
        <div class="row g-2 justify-content-center">
          <div class="col-4">
            <div class="demo-chip">
              <div class="demo-chip-role" style="color:#92400e">Admin</div>
              <div>user_admin</div>
            </div>
          </div>
          <div class="col-4">
            <div class="demo-chip">
              <div class="demo-chip-role" style="color:#1d4ed8">Kasir</div>
              <div>user_kasir</div>
            </div>
          </div>
          <div class="col-4">
            <div class="demo-chip">
              <div class="demo-chip-role" style="color:#a16207">Dapur</div>
              <div>user_dapur</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>