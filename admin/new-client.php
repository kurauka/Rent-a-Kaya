<?php
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/admin_header0.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['full_name']) ? uncrack($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? is_email($_POST['email']) : '';
    $house = isset($_POST['house_number']) ? uncrack($_POST['house_number']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : random_password();

    if ($mysqli) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO clients (full_name,email,password_hash,house_number) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $name, $email, $hash, $house);
        if ($stmt->execute()) {
            $msg = 'Tenant created. Password: ' . htmlspecialchars($password);
        } else {
            $msg = 'Error: ' . $mysqli->error;
        }
    } else {
        $msg = 'DB connection not available.';
    }
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-8">
        <div class="kaya-card p-4">
          <h3>Create Tenant</h3>
          <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
          <form method="post">
            <div class="mb-3"><label>Full name</label><input class="form-control" name="full_name" required></div>
            <div class="mb-3"><label>Email</label><input class="form-control" name="email" type="email" required></div>
            <div class="mb-3"><label>House number</label><input class="form-control" name="house_number"></div>
            <div class="mb-3"><label>Password (leave blank to auto-generate)</label><input class="form-control" name="password" type="text"></div>
            <button class="btn btn-primary">Create tenant</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
