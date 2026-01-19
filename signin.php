<?php
// Simple combined sign-in page: Admin (link) or Tenant (embedded form)
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sign In - Rent-a-Kaya</title>
  <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/brand.css" rel="stylesheet">
  <link href="css/kayarent-theme.css" rel="stylesheet">
  <link href="css/auth.css" rel="stylesheet">
</head>
<body>
  <main class="signin-shell">
    <section class="auth-card brand-panel">
      <div>
        <div class="brand-pill">KayaRent</div>
        <h1>Welcome to Rent-a-Kaya</h1>
        <p class="small-note">Manage and book rental homes with confidence. Secure, simple and tailored for landlords and tenants.</p>
      </div>
      <div>
        <h4 style="margin-top:1rem">Administrator</h4>
        <p class="small-note">Admins access the management console to add properties, tenants and invoices.</p><br>
        <p style="margin-top:0.6rem"><a href="admin/login.php" class="btn-accent ">Go to Admin Login</a></p>
      </div>
    </section>

    <section class="auth-card form-panel">
      <h2>Tenant Sign In</h2>
      <p class="small-note">Tenants can sign in to view invoices, receipts and messages.</p>
      <form method="post" action="client/auth.php" style="margin-top:1rem" autocomplete="on">
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" class="form-input" type="email" placeholder="tenant@kaya.com" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" name="password" class="form-input" type="password" placeholder="Your password" required>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <label style="display:flex;align-items:center;gap:0.5rem"><input type="checkbox" name="remember"> Remember me</label>
          <a class="link-muted" href="contact.php">Need help?</a>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-accent">Sign in</button>
          <a href="signup.php" class="link-muted">Request account</a>
        </div>
        <div class="small-note">By signing in you agree to our <a href="#">terms</a> and <a href="#">privacy</a>.</div>
      </form>
    </section>
  </main>
</body>
</html>
