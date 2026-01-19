<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: tmp_login_stub.php');
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Temporary Dashboard</title></head>
<body>
  <h1>Temporary Dashboard</h1>
  <p>Signed in as: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
  <p><a href="logout.php">Sign out</a> (this will not clear DB sessions)</p>
</body>
</html>
