<?php
// Temporary login stub for testing when DB is unavailable.
// Do NOT leave this file enabled in production. Remove it after testing.
session_start();

$test_email = 'obed@example.com';
$test_pass = 'mimi';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

  if ($email === $test_email && $password === $test_pass) {
    // login success: set session and redirect to temporary dashboard
    $_SESSION['email'] = $email;
    header('Location: tmp_dashboard.php');
    exit;
  } else {
        $error = 'Invalid test credentials.';
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test Login Stub</title>
</head>
<body>
  <h2>Temporary Login Stub</h2>
  <?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
  <form method="post">
    <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars(isset($email)?$email:''); ?>"></label><br>
    <label>Password: <input type="password" name="password"></label><br>
    <button type="submit">Sign In</button>
  </form>
  <p>Use test credentials: <strong>obed@example.com</strong> / <strong>mimi</strong></p>
</body>
</html>