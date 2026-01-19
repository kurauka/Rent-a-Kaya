<?php
session_start();
require_once __DIR__ . '/../admin/functions/db.php';
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!isset($_SESSION['client_id'])) { header('Location: /rent-a-kaya/login.php'); exit; }
if ($is_ajax) {
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}
$pgnm = 'Messages - Rent-a-Kaya';
$username = $_SESSION['client_name'] ?? 'Client';
if (!$is_ajax) {
  require_once __DIR__ . '/client_header.php';
  require_once __DIR__ . '/client_left_panel.php';
}

$messages = [];
if ($mysqli && has_column($mysqli,'messages','id')) {
  try {
    $res = $mysqli->query("SELECT id, subject, message, created_at FROM messages WHERE client_id = " . (int)$_SESSION['client_id'] . " ORDER BY created_at DESC LIMIT 30");
    if ($res) while($r = $res->fetch_assoc()) $messages[] = $r;
  } catch (Exception $e) { $messages = []; }
}
if (empty($messages)) {
  $messages = [
    ['id'=>1,'subject'=>'Rent reminder','message'=>'Your December rent is due on 10th Dec. Please pay promptly.','created_at'=>'2025-11-30'],
    ['id'=>2,'subject'=>'Water outage','message'=>'Planned water shutdown on 5th Dec between 08:00-12:00.','created_at'=>'2025-11-25']
  ];
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="kaya-card p-3">
          <h3 style="margin:0">Messages</h3>
          <p class="kaya-muted" style="margin-top:8px">Important messages from property managers.</p>
          <div style="margin-top:12px">
            <?php foreach($messages as $m): ?>
              <div class="kaya-card p-3 mb-2">
                <div class="d-flex justify-content-between"><strong><?php echo htmlspecialchars($m['subject']); ?></strong><small class="kaya-muted"><?php echo htmlspecialchars($m['created_at']); ?></small></div>
                <div style="margin-top:8px"><?php echo htmlspecialchars($m['message']); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>
