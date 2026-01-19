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
$pgnm = 'Inbox - Rent-a-Kaya';
$username = $_SESSION['client_name'] ?? 'Client';
if (!$is_ajax) { require_once __DIR__ . '/client_header.php'; require_once __DIR__ . '/client_left_panel.php'; }

$messages = [];
if ($mysqli && has_column($mysqli,'messages','id')) {
  try {
    $res = $mysqli->query("SELECT id, subject, message, created_at FROM messages WHERE client_id = " . (int)$_SESSION['client_id'] . " ORDER BY created_at DESC LIMIT 50");
    if ($res) while($r = $res->fetch_assoc()) $messages[] = $r;
  } catch (Exception $e) { $messages = []; }
}
if (empty($messages)) {
  $messages = [
    ['id'=>1,'subject'=>'Rent reminder','message'=>'Your December rent is due on 10th Dec.','created_at'=>'2025-11-30'],
    ['id'=>2,'subject'=>'Water shutdown','message'=>'Water supply will be interrupted on 5th Dec.','created_at'=>'2025-11-25']
  ];
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="kaya-card p-3">
          <h5 style="margin:0">Inbox</h5>
          <div style="margin-top:12px">
            <ul class="list-group">
              <?php foreach($messages as $m): ?>
                <li class="list-group-item list-group-item-action" data-id="<?php echo $m['id']; ?>">
                  <div style="display:flex;justify-content:space-between"><strong><?php echo htmlspecialchars($m['subject']); ?></strong><small class="kaya-muted"><?php echo htmlspecialchars($m['created_at']); ?></small></div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="kaya-card p-3" id="inbox-view">
          <div class="kaya-muted">Select a message to read</div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>

<script>
window.initClientPage = function(){
  var list = document.querySelectorAll('#page-wrapper .list-group-item');
  var view = document.getElementById('inbox-view');
  list.forEach(function(li){ li.addEventListener('click', function(){ var id = this.getAttribute('data-id'); var subject = this.querySelector('strong').innerText; var date = this.querySelector('.kaya-muted').innerText; var body = '';
      // try to find message body from server-rendered data (not secure, demo)
      body = 'Message content is not loaded from server in demo mode.';
      view.innerHTML = '<h5>'+subject+'</h5><div class="kaya-muted" style="margin-bottom:8px">'+date+'</div><div>'+body+'</div>';
  }); });
};
if(typeof window.initClientPage === 'function') window.initClientPage();
</script>
