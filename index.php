<?php
session_start();
if (isset($_SESSION['role'])) {
  $allowed = ['admin', 'kasir', 'dapur', 'owner'];
  $role = $_SESSION['role'];
  if (in_array($role, $allowed)) {
    header("Location: " . $role . ".php");
    exit;
  }
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
      cursor: pointer;
      transition: all 0.15s ease-in-out;
      position: relative;
    }

    .demo-chip:hover {
      border-color: #92400e;
      background: #fde8cc;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(146, 64, 14, 0.08);
    }

    .demo-chip.copied {
      border-color: #16a34a;
      background: #dcfce7;
    }

    .demo-chip-role {
      font-weight: 700;
      margin-bottom: 2px;
    }

    .demo-chip-user {
      font-family: monospace;
      font-size: 10px;
      color: #374151;
    }

    .demo-chip-pass {
      font-size: 10px;
      color: #6b7280;
      margin-top: 2px;
    }

    .demo-chip-hint {
      font-size: 9px;
      color: #9ca3af;
      margin-top: 4px;
    }

    .copy-toast {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: #1f2937;
      color: #fff;
      padding: 8px 18px;
      border-radius: 20px;
      font-size: 12px;
      opacity: 0;
      transition: all 0.25s ease;
      pointer-events: none;
      z-index: 9999;
    }

    .copy-toast.show {
      opacity: 1;
      transform: translateX(-50%) translateY(0);
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
        <div
          style="font-size:11px;font-weight:600;color:#a07850;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px;text-align:center">
          Demo Akun <span style="font-weight:400;color:#9ca3af">(klik untuk isi form)</span></div>
        <div class="row g-2 justify-content-center">
          <div class="col-6">
            <div class="demo-chip" onclick="fillLogin('Owner','user_owner')">
              <div class="demo-chip-role" style="color:#a16207">Owner</div>
              <div class="demo-chip-user">Owner</div>
              <div class="demo-chip-pass">pass: user_owner</div>
            </div>
          </div>
          <div class="col-6">
            <div class="demo-chip" onclick="fillLogin('Admin','user_admin')">
              <div class="demo-chip-role" style="color:#92400e">Admin</div>
              <div class="demo-chip-user">Admin</div>
              <div class="demo-chip-pass">pass: user_admin</div>
            </div>
          </div>
          <div class="col-6">
            <div class="demo-chip" onclick="fillLogin('Kasir','user_kasir')">
              <div class="demo-chip-role" style="color:#1d4ed8">Kasir</div>
              <div class="demo-chip-user">Kasir</div>
              <div class="demo-chip-pass">pass: user_kasir</div>
            </div>
          </div>
          <div class="col-6">
            <div class="demo-chip" onclick="fillLogin('Dapur','user_dapur')">
              <div class="demo-chip-role" style="color:#059669">Dapur</div>
              <div class="demo-chip-user">Dapur</div>
              <div class="demo-chip-pass">pass: user_dapur</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Toast notification -->
      <div class="copy-toast" id="copyToast">✓ Akun diisi ke form</div>
    </div>
  </div>

  <script>
    function fillLogin(username, password) {
      const usernameInput = document.querySelector('input[name="username"]');
      const passwordInput = document.querySelector('input[name="password"]');

      if (usernameInput) usernameInput.value = username;
      if (passwordInput) passwordInput.value = password;

      // Highlight the clicked chip
      document.querySelectorAll('.demo-chip').forEach(el => el.classList.remove('copied'));
      event.currentTarget.classList.add('copied');
      setTimeout(() => event.currentTarget.classList.remove('copied'), 1200);

      // Show toast
      const toast = document.getElementById('copyToast');
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 1800);

      // Focus on submit button
      usernameInput.focus();
    }
  </script>
</body>

</html>