<?php
// Modern combined login page (Admin link + Tenant form)
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tenant Login — Rent-a-Kaya</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="admin/css/animate.css" rel="stylesheet">
    <link href="admin/css/style.css" rel="stylesheet">
    <link href="admin/css/login-custom.css" rel="stylesheet">
    <style>
        /* Force exact parity with admin login card */
        .login-card{max-width:420px;padding:28px;border-radius:12px}
        .login-brand .login-title{font-size:20px;font-weight:600;margin-bottom:8px}
        .form-group.input-group-icon{position:relative}
        .form-group.input-group-icon .fa{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9aa4b2;width:36px;text-align:center;z-index:2}
        .form-group.input-group-icon input{display:block;width:100%;box-sizing:border-box;padding-left:44px;height:48px;border-radius:8px;border:1px solid rgba(18,34,63,0.08)}
        .form-group.input-group-icon .form-control{height:48px}
        .btn-primary.btn-block{display:block;width:100%;height:48px;border-radius:8px;font-weight:600}
        .alert-error{background:#fff6f6;border:1px solid #ffd6d6;color:#9a2b2b;padding:10px 12px;border-radius:8px;margin-bottom:12px}
    </style>
</head>
<body>
    <section class="login-wrapper">
        <div class="login-card animated fadeInDown" role="main">
            <div class="login-brand">
                <h2 class="login-title">Rent-A-Kaya</h2>
                <div class="login-subtitle">Sign in to view invoices, payments and receipts</div>
            </div>

            <?php if (!empty($_GET['error'])): ?>
                <div class="alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form id="loginform" action="client/auth.php" method="post" novalidate>
                <div class="form-group input-group-icon">
                    <i class="fa fa-envelope"></i>
                    <input class="form-control" type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
                </div>

                <div class="form-group input-group-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control" type="password" name="password" required placeholder="Password">
                </div>

                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Sign In</button>
                </div>

                <div class="footer-note" style="margin-top:8px">Demo account: <strong>tenant@kaya.com</strong> / <strong>tenant1</strong></div>
            </form>

            <div style="text-align:center;margin-top:8px"><a href="admin/login.php">Administrator login</a> · <a href="contact.php">Need help?</a></div>
            <div class="footer-note">© <?php echo date('Y'); ?> Rent-A-Kaya</div>
        </div>
    </section>

    <script src="admin/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="admin/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
</body>
</html>