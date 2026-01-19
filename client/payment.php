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
$client_id = (int) $_SESSION['client_id'];

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $method = isset($_POST['method']) ? uncrack($_POST['method']) : 'cash';
    $reference = isset($_POST['reference']) ? uncrack($_POST['reference']) : 'REF' . time();

    // MPESA placeholder: if method == mpesa, simulate an async callback (we just insert)
    if ($mysqli) {
        $house = isset($_SESSION['house_number']) ? $_SESSION['house_number'] : null;
        $stmt = $mysqli->prepare('INSERT INTO payments (client_id, invoice_id, house_number, amount, method, reference) VALUES (?,?,?,?,?,?)');
        $nullInv = null;
        $stmt->bind_param('iisdss', $client_id, $nullInv, $house, $amount, $method, $reference);
        if ($stmt->execute()) {
            $pid = $mysqli->insert_id;
            $msg = 'Payment recorded. Receipt: ' . $pid;
            header('Location: receipt.php?id=' . $pid);
            exit;
        } else {
            $msg = 'DB error: ' . $mysqli->error;
        }
    } else {
        $msg = 'DB not available; in demo mode pretend success.';
    }
}
?>
<?php if (!$is_ajax) { require_once __DIR__ . '/client_header.php'; require_once __DIR__ . '/client_left_panel.php'; }
?>
<div id="page-wrapper">
  <div class="container" style="max-width:1000px;margin:0 auto;padding-top:24px">
    <div class="row g-3">
      <div class="col-md-7">
        <div class="kaya-card p-3">
          <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin:0">Recent payments</h4>
            <div class="kaya-muted">Summary of your payment history</div>
          </div>
          <div style="margin-top:12px;overflow:auto">
            <table class="table table-striped">
              <thead><tr><th>Ref</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
              <tbody>
                <?php if(empty($recentPayments)): ?>
                  <tr><td colspan="4">No payments yet.</td></tr>
                <?php else: foreach($recentPayments as $p): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($p['reference'] ?? ''); ?></td>
                    <td>KES <?php echo number_format($p['amount'],2); ?></td>
                    <td><?php echo htmlspecialchars($p['method']); ?></td>
                    <td><?php echo htmlspecialchars($p['paid_at']); ?></td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="kaya-card p-3">
          <h4 style="margin:0">Make a payment</h4>
          <?php if($msg): ?><div class="alert alert-info mt-2"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
          <form id="payment-form" method="post" class="mt-3">
            <div class="mb-3">
              <label class="form-label">Amount (KES)</label>
              <div class="input-group">
                <span class="input-group-text">KES</span>
                <input name="amount" type="number" step="0.01" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Payment method</label>
              <select name="method" class="form-control">
                <option value="cash">Cash</option>
                <option value="mpesa">MPESA (placeholder)</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Reference</label>
              <input name="reference" class="form-control" placeholder="Transaction reference">
            </div>
            <div class="d-grid"><button id="pay-now-btn" class="btn btn-primary btn-lg">Pay now</button></div>
          </form>
          <div class="mt-3 kaya-muted" style="font-size:13px">You can also copy the MPESA paybill and account number from your invoice to complete payment.</div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!$is_ajax) require_once __DIR__ . '/../admin/admin_footer.php'; ?>

<!-- MPESA STK Push modal -->
<div id="mpesa-modal" class="modal" tabindex="-1" role="dialog" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;align-items:center;justify-content:center;z-index:2000">
  <div style="background:rgba(0,0,0,0.4);position:absolute;inset:0"></div>
  <div role="document" style="background:#fff;border-radius:10px;padding:20px;max-width:420px;margin:auto;position:relative;z-index:2001">
    <h5 style="margin-top:0">Lipa na M-PESA</h5>
    <p class="kaya-muted">Enter the mobile number to receive the M-PESA prompt.</p>
    <div class="mb-3">
      <label class="form-label">Phone (use 2547XXXXXXXX)</label>
      <input id="mpesa-phone" type="text" class="form-control" placeholder="2547..." required>
    </div>
    <div class="mb-3">
      <label class="form-label">Amount (KES)</label>
      <input id="mpesa-amount" type="number" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Reference</label>
      <input id="mpesa-reference" type="text" class="form-control" placeholder="Invoice or account reference">
    </div>
    <div class="d-flex gap-2 justify-content-end">
      <button id="mpesa-cancel" class="btn btn-light">Cancel</button>
      <button id="mpesa-send" class="btn btn-primary">Send M-PESA prompt</button>
    </div>
    <div id="mpesa-status" style="margin-top:12px;display:none"></div>
  </div>
</div>

<script>
(function(){
  var form = document.getElementById('payment-form');
  var payBtn = document.getElementById('pay-now-btn');
  var modal = document.getElementById('mpesa-modal');
  var cancel = document.getElementById('mpesa-cancel');
  var send = document.getElementById('mpesa-send');
  var status = document.getElementById('mpesa-status');
  var phoneInput = document.getElementById('mpesa-phone');
  var amountInput = document.getElementById('mpesa-amount');
  var refInput = document.getElementById('mpesa-reference');

  function showModal(){ modal.style.display = 'flex'; }
  function hideModal(){ modal.style.display = 'none'; status.style.display='none'; }

  // Populate modal when payment button clicked for MPESA method
  form.addEventListener('submit', function(e){
    var method = (form.querySelector('select[name=method]')||{}).value;
    if(method === 'mpesa'){
      e.preventDefault();
      // fill modal fields from form
      var amt = form.querySelector('input[name=amount]').value || '';
      var ref = form.querySelector('input[name=reference]').value || '';
      amountInput.value = amt;
      refInput.value = ref;
      showModal();
    }
  });

  cancel.addEventListener('click', function(e){ e.preventDefault(); hideModal(); });

  send.addEventListener('click', function(e){
    e.preventDefault();
    var phone = phoneInput.value.trim();
    var amount = amountInput.value.trim();
    var reference = refInput.value.trim() || ('REF' + Math.floor(Date.now()/1000));
    if(!phone || !amount){ status.style.display='block'; status.innerHTML = '<div class="alert alert-danger">Please provide phone and amount.</div>'; return; }
    status.style.display='block'; status.innerHTML = '<div class="alert alert-info">Sending prompt…</div>';
    send.disabled = true;
    fetch('functions/mpesa_stk_push.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
      body: 'phone=' + encodeURIComponent(phone) + '&amount=' + encodeURIComponent(amount) + '&reference=' + encodeURIComponent(reference)
    }).then(function(res){ return res.json(); }).then(function(json){
      if(json.success){ status.innerHTML = '<div class="alert alert-success">STK Push sent. You should receive a prompt on your phone.</div>'; }
      else { status.innerHTML = '<div class="alert alert-danger">Error: ' + (json.message||'Unknown') + '</div>'; }
    }).catch(function(err){ status.innerHTML = '<div class="alert alert-danger">Network error</div>'; }).finally(function(){ send.disabled=false; });
  });
})();
</script>
