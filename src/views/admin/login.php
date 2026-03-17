<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — Faradj MMC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-login-wrap">
  <div class="admin-login-box">
    <div class="login-logo">
      <h1>⚙️ Admin Panel</h1>
      <p>Faradj MMC İdarəetmə</p>
    </div>
    <?php if (!empty($error)): ?>
      <div class="flash error">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="/admin/login">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
               placeholder="admin@faradj.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Şifrə</label>
        <input type="password" name="password"
               placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Daxil ol
      </button>
    </form>
  </div>
</body>
</html>
