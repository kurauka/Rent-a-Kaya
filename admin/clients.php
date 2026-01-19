<?php
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/admin_header0.php';

// simple list of clients (tenants) for admin
global $mysqli, $db;
$clients = [];
if ($mysqli) {
    $res = $mysqli->query('SELECT id, full_name, email, house_number, status, created_at FROM clients ORDER BY created_at DESC');
    if ($res) while($r = $res->fetch_assoc()) $clients[] = $r;
}
?>
<div class="page-wrapper" id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="kaya-card p-4">
          <h3>Manage Tenants</h3>
          <p><a class="btn btn-primary" href="new-client.php">Add Tenant</a></p>
          <table class="table table-striped">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>House</th><th>Status</th><th>Created</th></tr></thead>
            <tbody>
              <?php if(empty($clients)): ?>
                <tr><td colspan="6">No tenants yet.</td></tr>
              <?php else: foreach($clients as $c): ?>
                <tr>
                  <td><?php echo $c['id']; ?></td>
                  <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                  <td><?php echo htmlspecialchars($c['email']); ?></td>
                  <td><?php echo htmlspecialchars($c['house_number']); ?></td>
                  <td><?php echo htmlspecialchars($c['status']); ?></td>
                  <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
