<?php
session_start();
require_once __DIR__ . '/../admin/functions/db.php';

if (!isset($_SESSION['client_id'])) {
  header('Location: /rent-a-kaya/login.php');
  exit;
}

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($is_ajax) {
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}

$client_id = (int) $_SESSION['client_id'];
$errors = [];
$success = '';

// demo tenant (id 0) cannot edit
if ($client_id === 0) {
  $errors[] = 'Demo account cannot edit profile.';
}

// Handle POST (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $client_id !== 0) {
  $full_name = isset($_POST['full_name']) ? is_username($_POST['full_name']) : '';
  $email = isset($_POST['email']) ? is_email($_POST['email']) : '';
  $phone = isset($_POST['phone']) ? uncrack($_POST['phone']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

  if (!$full_name) $errors[] = 'Full name is required.';
  if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
  if ($password) {
    if ($password !== $password_confirm) $errors[] = 'Passwords do not match.';
    elseif (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
  }

  if (empty($errors) && $mysqli) {
    try {
      // update columns conditionally
      if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('UPDATE clients SET full_name = ?, email = ?, phone = ?, password_hash = ? WHERE id = ? LIMIT 1');
        $stmt->bind_param('ssssi', $full_name, $email, $phone, $hash, $client_id);
      } else {
        $stmt = $mysqli->prepare('UPDATE clients SET full_name = ?, email = ?, phone = ? WHERE id = ? LIMIT 1');
        $stmt->bind_param('sssi', $full_name, $email, $phone, $client_id);
      }
      $ok = $stmt->execute();
      if ($ok) {
        $success = 'Profile updated successfully.';
        $_SESSION['client_name'] = $full_name;
      } else {
        $errors[] = 'Failed to update profile.';
      }
    } catch (Exception $e) { $errors[] = 'Update error.'; }
  }
}

// Fetch current client data
$client = null;
if ($mysqli && $client_id !== 0) {
  try {
    $stmt = $mysqli->prepare('SELECT id, full_name, email, phone, house_number, created_at FROM clients WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $client_id);
    $stmt->execute();
    $client = $stmt->get_result()->fetch_assoc();
  } catch (Exception $e) { $client = null; }
}

// use fallback/demo values
if (!$client) {
  $client = [
    'full_name' => $_SESSION['client_name'] ?? 'Client',
    'email' => '',
    'phone' => '',
    'house_number' => $_SESSION['house_number'] ?? ''
  ];
}

$pgnm = 'Account Settings - Rent-a-Kaya';
if (!$is_ajax) {
  require_once __DIR__ . '/client_header.php';
  require_once __DIR__ . '/client_left_panel.php';
}
?>

<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <main class="col-md-12">
        <h3 class="m-0 mb-3">Account settings</h3>

        <?php if(!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul>
              <?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if($success): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="kaya-card p-3">
          <form method="post" action="settings.php">
            <div class="form-group mb-3">
              <label>Full name</label>
              <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($client['full_name'] ?? ''); ?>" required>
            </div>

            <div class="form-group mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>">
            </div>

            <div class="form-group mb-3">
              <label>Phone</label>
              <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($client['phone'] ?? ''); ?>">
            </div>

            <hr>
            <h5>Change password (optional)</h5>
            <div class="form-group mb-3">
              <label>New password</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group mb-3">
              <label>Confirm password</label>
              <input type="password" name="password_confirm" class="form-control">
            </div>

            <div class="d-flex gap-2">
              <button class="btn btn-primary" type="submit">Save changes</button>
              <a class="btn btn-outline-secondary" href="dashboard.php">Cancel</a>
            </div>
          </form>
        </div>

      </main>
    </div>
  </div>
</div>

<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>

<?php
// mark todo complete
?>
