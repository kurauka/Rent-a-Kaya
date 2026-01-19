<?php
session_start();
require_once __DIR__ . '/../admin/functions/db.php';
if (!isset($_SESSION['client_id'])) { header('Location: /rent-a-kaya/login.php'); exit; }
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($is_ajax) {
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}
$pgnm = 'Notices - Rent-a-Kaya';
$username = $_SESSION['client_name'] ?? 'Client';
if (!$is_ajax) {
  require_once __DIR__ . '/client_header.php';
  require_once __DIR__ . '/client_left_panel.php';
}

$posts = [];
if ($mysqli && has_column($mysqli,'posts','id')) {
  try {
    $res = $mysqli->query("SELECT id, title, date AS published_at, excerpt FROM posts WHERE status='published' ORDER BY date DESC LIMIT 20");
    if ($res) while($r = $res->fetch_assoc()) $posts[] = $r;
  } catch (Exception $e) { $posts = []; }
}
if (empty($posts)) {
  $posts = [
    ['id'=>1,'title'=>'Welcome tenants','published_at'=>'2025-10-01','excerpt'=>'Welcome to Rent-a-Kaya. We aim to provide timely services...'],
    ['id'=>2,'title'=>'Holiday schedule','published_at'=>'2025-12-01','excerpt'=>'Office will be closed from 24th Dec to 2nd Jan. For emergencies contact...']
  ];
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="kaya-card p-3">
          <h3 style="margin:0">Notices & Announcements</h3>
          <p class="kaya-muted" style="margin-top:8px">Latest notices from management.</p>
          <div style="margin-top:12px">
            <?php foreach($posts as $post): ?>
              <div class="kaya-card p-3 mb-2">
                <div class="d-flex justify-content-between"><strong><?php echo htmlspecialchars($post['title']); ?></strong><small class="kaya-muted"><?php echo htmlspecialchars($post['published_at']); ?></small></div>
                <div style="margin-top:8px"><?php echo htmlspecialchars($post['excerpt']); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>
