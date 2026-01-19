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
$pgnm = 'Invoices - Rent-a-Kaya';
$username = $_SESSION['client_name'] ?? 'Client';
if (!$is_ajax) {
  require_once __DIR__ . '/client_header.php';
  require_once __DIR__ . '/client_left_panel.php';
}

// demo invoice data (fallback when DB unavailable)
$invoices = [];
if ($mysqli && has_column($mysqli,'invoices','id')) {
  try {
    $res = $mysqli->query("SELECT id, description, amount_due, due_date, status FROM invoices WHERE client_id = " . (int)$_SESSION['client_id'] . " ORDER BY due_date DESC LIMIT 50");
    if ($res) while($r = $res->fetch_assoc()) $invoices[] = $r;
  } catch (Exception $e) { $invoices = []; }
}
if (empty($invoices)) {
  $invoices = [
    ['id'=>101,'description'=>'Monthly rent (Dec 2025)','amount_due'=>7000,'due_date'=>'2025-12-10','status'=>'unpaid'],
    ['id'=>102,'description'=>'Maintenance fee','amount_due'=>2000,'due_date'=>'2025-12-20','status'=>'unpaid'],
    ['id'=>99,'description'=>'Security deposit (adjustment)','amount_due'=>0,'due_date'=>'2025-06-01','status'=>'paid']
  ];
}

// build map of latest payment id per invoice for receipt links
$payments_by_invoice = [];
if ($mysqli && !empty($invoices)) {
  $ids = array_column($invoices, 'id');
  $ids = array_map('intval', $ids);
  $ids_list = implode(',', $ids);
  if ($ids_list) {
    $sql = "SELECT id, invoice_id FROM payments WHERE invoice_id IN ($ids_list) AND client_id = " . (int)$_SESSION['client_id'] . " ORDER BY paid_at DESC";
    try {
      $res = $mysqli->query($sql);
      if ($res) {
        while ($p = $res->fetch_assoc()) {
          if (!isset($payments_by_invoice[$p['invoice_id']])) $payments_by_invoice[$p['invoice_id']] = $p['id'];
        }
      }
    } catch (Exception $e) { /* ignore, leave map empty */ }
  }
}
?>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="kaya-card p-3">
          <div class="d-flex justify-content-between align-items-center">
            <h3 style="margin:0">Your Invoices</h3>
            <div class="kaya-muted"><?php echo htmlspecialchars($username); ?></div>
          </div>
          <p class="kaya-muted" style="margin-top:8px">Summary of recent invoices and outstanding amounts.</p>
          <div style="margin-top:12px;overflow:auto">
            <table class="table table-striped">
              <thead><tr><th>Invoice</th><th>Description</th><th>Due</th><th>Amount</th><th>Status</th><th></th></tr></thead>
              <tbody>
                <?php foreach($invoices as $inv): ?>
                  <tr>
                    <td>#<?php echo htmlspecialchars($inv['id']); ?></td>
                    <td><?php echo htmlspecialchars($inv['description']); ?></td>
                    <td><?php echo htmlspecialchars($inv['due_date']); ?></td>
                    <td>KES <?php echo number_format($inv['amount_due'],2); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($inv['status'])); ?></td>
                    <td>
                      <a class="btn btn-outline-primary btn-sm" href="payment.php?invoice=<?php echo urlencode($inv['id']); ?>">Pay</a>
                      <?php if(!empty($payments_by_invoice[$inv['id']])): ?>
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="receipt.php?id=<?php echo urlencode($payments_by_invoice[$inv['id']]); ?>" target="_blank">Receipt</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>
