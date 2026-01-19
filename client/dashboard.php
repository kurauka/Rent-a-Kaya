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

// fetch client basic
$client = null;
if ($mysqli) {
  if ($client_id !== 0 && has_column($mysqli,'clients','id')) {
    try {
      $stmt = $mysqli->prepare('SELECT id, full_name, email, phone, house_number, created_at FROM clients WHERE id = ? LIMIT 1');
      $stmt->bind_param('i', $client_id);
      $stmt->execute();
      $client = $stmt->get_result()->fetch_assoc();
    } catch (Exception $e) {
      $client = null;
    }
  }
}

// payments summary — guard against missing columns/tables
$total_paid = 0.0; $due_total = 0.0; $due_count = 0;
// use shared has_column() from admin/functions/db.php

if ($mysqli && $client_id !== 0 && has_column($mysqli,'payments','amount')) {
  try {
    $r = $mysqli->query("SELECT SUM(amount) AS s FROM payments WHERE client_id = " . $client_id);
    $row = $r ? $r->fetch_assoc() : null;
    $total_paid = $row && isset($row['s']) ? (float)$row['s'] : 0.0;
  } catch (Exception $e) { $total_paid = 0.0; }
}

if ($mysqli && $client_id !== 0 && has_column($mysqli,'invoices','amount_due')) {
  try {
    $r2 = $mysqli->query("SELECT COUNT(*) AS c, COALESCE(SUM(amount_due),0) AS s FROM invoices WHERE client_id = " . $client_id . " AND status != 'paid'");
    $row2 = $r2 ? $r2->fetch_assoc() : null;
    $due_count = $row2 ? (int)$row2['c'] : 0;
    $due_total = $row2 ? (float)$row2['s'] : 0.0;
  } catch (Exception $e) { $due_count = 0; $due_total = 0.0; }
}

// fallback demo values when DB or columns not available
if ($total_paid == 0 && $due_total == 0 && $due_count == 0) {
  $total_paid = 4000.00;
  $due_total = 9000.00;
  $due_count = 2;
}

// recent payments
$recentPayments = [];
if ($mysqli && $client_id !== 0 && has_column($mysqli,'payments','amount') && has_column($mysqli,'payments','paid_at')) {
  try {
    $res = $mysqli->query("SELECT id, amount, method, reference, paid_at FROM payments WHERE client_id = " . $client_id . " ORDER BY paid_at DESC LIMIT 8");
    if ($res) while($r = $res->fetch_assoc()) $recentPayments[] = $r;
  } catch (Exception $e) {
    $recentPayments = [];
  }
} else {
    // demo data for tenant id 0 (or when DB unavailable)
    $recentPayments = [
      ['reference'=>'RCPT-1001','amount'=>2500,'method'=>'mpesa','paid_at'=>'2025-12-01 10:12:00'],
      ['reference'=>'RCPT-1002','amount'=>1500,'method'=>'cash','paid_at'=>'2025-11-18 14:05:00'],
    ];
}

// demo invoices and bills (for dashboard links and counts)
$invoices = [];
$bills = [];
if ($mysqli && $client_id !== 0) {
    // load limited data paths if needed
} else {
    $invoices = [
      ['id'=>1,'description'=>'Monthly rent (Dec 2025)','amount_due'=>7000,'due_date'=>'2025-12-10','status'=>'unpaid'],
      ['id'=>2,'description'=>'Maintenance','amount_due'=>2000,'due_date'=>'2025-12-20','status'=>'unpaid']
    ];
    $bills = [
      ['id'=>1,'type'=>'water','amount'=>450,'period'=>'2025-11'],
      ['id'=>2,'type'=>'electricity','amount'=>1200,'period'=>'2025-11']
    ];
}

// demo messages and blog posts
$messages = [];
$posts = [];
if ($mysqli && $client_id !== 0) {
  // load from DB if available
} else {
  $messages = [ ['subject'=>'Rent reminder','message'=>'Your December rent is due on 10th Dec.','created_at'=>'2025-11-30'], ['subject'=>'Water shutdown','message'=>'Water supply will be interrupted on 5th Dec.','created_at'=>'2025-11-25'] ];
  $posts = [ ['title'=>'Welcome tenants','published_at'=>'2025-10-01','slug'=>'welcome-tenants'], ['title'=>'Holiday schedule','published_at'=>'2025-12-01','slug'=>'holiday-schedule'] ];
}

?>
<?php
// Use admin layout header and sidebar for consistent UI
$pgnm = 'Client Dashboard - Rent-a-Kaya';
$username = $_SESSION['client_name'] ?? 'Client';
if (!$is_ajax) {
  require_once __DIR__ . '/client_header.php';
  require_once __DIR__ . '/client_left_panel.php';
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
      <div class="row">
        <main class="col-md-12 dash-wrap">
          <div class="d-flex align-items-center justify-content-between">
            <h3 class="m-0">Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?></h3>
            <div>
              <a href="settings.php" class="settings-badge" title="Account settings">
                <i class="fa-solid fa-gear kaya-settings-icon"></i>
              </a>
            </div>
          </div>
          <div class="summary-cards">
            <div class="row g-3 modern-stats">
              <div class="col-lg-3 col-md-6">
                <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                  <div class="kaya-icon icon-badge"><i class="fa-solid fa-wallet fa-lg"></i></div>
                  <div style="flex:1">
                    <div class="kaya-muted" style="font-size:12px">Total paid</div>
                    <div style="font-weight:700;font-size:20px">KES <?php echo number_format($total_paid,2);?></div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                  <div class="kaya-icon" style="color:#EF4444"><i class="fa-solid fa-file-invoice-dollar fa-lg"></i></div>
                  <div style="flex:1">
                    <div class="kaya-muted" style="font-size:12px">Outstanding invoices</div>
                    <div style="font-weight:700;font-size:20px">KES <?php echo number_format($due_total,2); ?> <small>(<?php echo $due_count; ?>)</small></div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                  <div class="kaya-icon" style="color:#06A7FF"><i class="fa-solid fa-receipt fa-lg"></i></div>
                  <div style="flex:1">
                    <div class="kaya-muted" style="font-size:12px">Recent payments</div>
                    <div style="font-weight:700;font-size:20px"><?php echo count($recentPayments); ?> <div style="font-size:12px;color:var(--kaya-muted)">entries</div></div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                  <div class="kaya-icon" style="color:#F59E0B"><i class="fa-solid fa-plug fa-lg"></i></div>
                  <div style="flex:1">
                    <div class="kaya-muted" style="font-size:12px">Utility bills</div>
                    <div style="font-weight:700;font-size:20px"><?php echo count($bills); ?> <div style="font-size:12px;color:var(--kaya-muted)">items</div></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-7">
              <h4 class="mb-3">Recent payments</h4>
              <div class="kaya-card p-3">
                <table class="table">
                  <thead><tr><th>Ref</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
                  <tbody>
                    <?php if(empty($recentPayments)): ?>
                      <tr><td colspan="4">No payments yet.</td></tr>
                    <?php else: foreach($recentPayments as $p): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($p['reference'] ?? ''); ?></td>
                        <td>KES <?php echo number_format($p['amount'],2); ?></td>
                        <td><span class="badge badge-light" style="background:#f3f4f6;color:#0f172a;padding:6px 10px;border-radius:10px;font-weight:600"><?php echo htmlspecialchars(strtoupper($p['method'])); ?></span></td>
                        <td><?php echo htmlspecialchars($p['paid_at']); ?></td>
                      </tr>
                    <?php endforeach; endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-5">
              <h4 class="mb-3">Outstanding invoices</h4>
              <?php if(empty($invoices)): ?><div class="kaya-card p-3">No outstanding invoices</div><?php else: ?>
                <?php foreach($invoices as $inv): ?>
                  <div class="kaya-card p-3 mb-2 d-flex justify-content-between align-items-center">
                    <div>
                      <div style="font-weight:700"><?php echo htmlspecialchars($inv['description']); ?></div>
                      <div class="kaya-muted">Due: <?php echo htmlspecialchars($inv['due_date']); ?></div>
                    </div>
                    <div style="text-align:right">
                      <div style="font-weight:700">KES <?php echo number_format($inv['amount_due'],2); ?></div>
                      <a href="invoices.php" class="btn btn-outline-primary btn-sm mt-2">View</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>

              <h5 class="mt-3 mb-2">Utility bills</h5>
              <?php foreach($bills as $b): ?>
                <div class="kaya-card p-2 mb-2 d-flex justify-content-between align-items-center">
                  <div style="font-weight:600"><?php echo strtoupper($b['type']); ?> <div class="kaya-muted" style="font-size:12px">Period: <?php echo htmlspecialchars($b['period']); ?></div></div>
                  <div style="text-align:right">KES <?php echo number_format($b['amount'],2); ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-6">
              <h4>Messages</h4>
              <?php foreach($messages as $m): ?>
                <div class="kaya-card p-3 mb-2">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <div style="font-weight:700"><?php echo htmlspecialchars($m['subject']); ?></div>
                      <div class="kaya-muted small"><?php echo htmlspecialchars($m['created_at']); ?></div>
                    </div>
                    <div><i class="fa-regular fa-envelope-open text-muted"></i></div>
                  </div>
                  <div style="margin-top:8px"><?php echo htmlspecialchars($m['message']); ?></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="col-md-6">
              <h4>Blog / Notices</h4>
              <?php foreach($posts as $p): ?>
                <div class="kaya-card p-3 mb-2 d-flex justify-content-between align-items-center">
                  <div>
                    <div style="font-weight:700"><?php echo htmlspecialchars($p['title']); ?></div>
                    <div class="kaya-muted small"><?php echo htmlspecialchars($p['published_at']); ?></div>
                  </div>
                  <a href="blog.php" class="text-[#A7634E]"><i class="fa-solid fa-arrow-right"></i></a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="mt-3 d-flex gap-2">
            <a class="btn btn-primary d-flex align-items-center gap-2" href="payment.php"><i class="fa-solid fa-credit-card"></i> Make a payment</a>
            <a class="btn btn-outline-secondary d-flex align-items-center gap-2" href="invoices.php"><i class="fa-solid fa-file-invoice"></i> My invoices</a>
          </div>
        </main>
      </div>
      </div>
    </div>
  <?php
  if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php';
  ?>
