<?php
// Request account page — modern UI. Submits to self and appends requests to tmp/account_requests.jsonl
$submitted = false;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? 'Tenant');
    $org = trim($_POST['organization'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') $errors[] = 'Please provide your full name.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please provide a valid email address.';

    if (empty($errors)) {
        $record = [
            'timestamp' => date('c'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'name' => htmlspecialchars($name, ENT_QUOTES|ENT_SUBSTITUTE),
            'email' => htmlspecialchars($email, ENT_QUOTES|ENT_SUBSTITUTE),
            'phone' => htmlspecialchars($phone, ENT_QUOTES|ENT_SUBSTITUTE),
            'role' => htmlspecialchars($role, ENT_QUOTES|ENT_SUBSTITUTE),
            'organization' => htmlspecialchars($org, ENT_QUOTES|ENT_SUBSTITUTE),
            'preferred_username' => htmlspecialchars($username, ENT_QUOTES|ENT_SUBSTITUTE),
            'message' => htmlspecialchars($message, ENT_QUOTES|ENT_SUBSTITUTE)
        ];
        @mkdir(__DIR__ . '/tmp', 0755, true);
        $path = __DIR__ . '/tmp/account_requests.jsonl';
        file_put_contents($path, json_encode($record, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);
        $submitted = true;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Request an Account — Rent-a-Kaya</title>
  <link href="css/brand.css" rel="stylesheet">
  <link href="css/kayarent-theme.css" rel="stylesheet">
  <link href="css/auth.css" rel="stylesheet">
</head>
<body>
  <main class="signin-shell" style="align-items:start;padding-top:3rem;padding-bottom:3rem">
    <section class="auth-card brand-panel">
      <div>
        <div class="brand-pill">KayaRent</div>
        <h1>Request an account</h1>
        <p class="small-note">Fill this short form to request an account. An administrator will review and create your account shortly.</p>
        <ul style="margin-top:1rem">
          <li style="margin:0.35rem 0;color:var(--kaya-muted)">Fast review turnaround</li>
          <li style="margin:0.35rem 0;color:var(--kaya-muted)">Secure verification</li>
          <li style="margin:0.35rem 0;color:var(--kaya-muted)">You will receive an email when your account is ready</li>
        </ul>
      </div>
      <div style="margin-top:1rem">
        <h4 style="margin:0">Need help?</h4>
        <p class="small-note">Contact support at <a href="mailto:info@rent-a-kaya.com" style="color:var(--kaya-accent)">info@rent-a-kaya.com</a></p>
      </div>
    </section>

    <section class="auth-card form-panel">
      <?php if ($submitted): ?>
        <div style="padding:1rem;border-radius:10px;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.12);">
          <strong style="color:var(--kaya-primary)">Request submitted</strong>
          <div class="small-note">Thanks — your request has been recorded. An administrator will get back to you by email.</div>
          <div style="margin-top:0.6rem"><a href="signin.php" class="link-muted">Return to sign-in</a></div>
        </div>
      <?php else: ?>
        <?php if (!empty($errors)): ?>
          <div style="padding:0.8rem;border-radius:8px;background:#fff4f4;border:1px solid rgba(233,30,99,0.08);margin-bottom:0.8rem">
            <strong style="color:#a61d2b">Please fix the following</strong>
            <ul style="margin:0.5rem 0 0 1.2rem;color:#6b1b2a">
              <?php foreach($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?>
            </ul>
          </div>
        <?php endif; ?>

        <h2>Request an account</h2>
        <form method="post" action="" style="margin-top:1rem" autocomplete="on">
          <div class="form-group">
            <label for="name">Full name</label>
            <input id="name" name="name" class="form-input" type="text" placeholder="Jane Doe" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" class="form-input" type="email" placeholder="you@example.com" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone (optional)</label>
            <input id="phone" name="phone" class="form-input" type="tel" placeholder="+254 7xx xxx xxx">
          </div>
          <div class="form-group">
            <label for="role">I am a</label>
            <select id="role" name="role" class="form-input">
              <option>Tenant</option>
              <option>Landlord / Owner</option>
              <option>Agent</option>
              <option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="organization">Organization (optional)</label>
            <input id="organization" name="organization" class="form-input" type="text" placeholder="Company or agency name">
          </div>
          <div class="form-group">
            <label for="username">Preferred username</label>
            <input id="username" name="username" class="form-input" type="text" placeholder="optional">
          </div>
          <div class="form-group">
            <label for="message">Message (why you need access)</label>
            <textarea id="message" name="message" class="form-input" rows="4" placeholder="A short description of why you need an account"></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-accent">Send request</button>
            <a href="signin.php" class="link-muted">Back to sign-in</a>
          </div>
          <div class="small-note">We respect your privacy. Your details are stored securely for account verification.</div>
        </form>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
