<?php
session_start();
require_once __DIR__ . '/../admin/functions/db.php';

// show payment receipt (modern responsive UI)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$payment = null;
$allowed = true;
if ($id && $mysqli) {
  // helper to find first existing column name from candidates
  $find_col = function($table, $candidates) use ($mysqli) {
    foreach ($candidates as $c) {
      if (has_column($mysqli, $table, $c)) return $c;
    }
    return null;
  };

  // detect primary columns in payments table across different schemas
  $pay_id_col = $find_col('payments', ['id','paymentID','payment_id','paymentId']);
  $pay_client_col = $find_col('payments', ['client_id','clientID','tenantID','tenant_id','tenantId']);
  $pay_invoice_col = $find_col('payments', ['invoice_id','invoiceNumber','invoiceNumber','invoice']);

  // fallback to defaults
  if (!$pay_id_col) $pay_id_col = 'id';

  // build SQL only selecting from payments (avoid joining unknown client/invoice schemas)
  $sql = "SELECT p.* FROM payments p WHERE p.`" . $mysqli->real_escape_string($pay_id_col) . "` = ?";
  $types = 's';
  $params = [$id];
  if (!empty($_SESSION['client_id']) && $pay_client_col) {
    $sql .= " AND p.`" . $mysqli->real_escape_string($pay_client_col) . "` = ? LIMIT 1";
    $types = 'ss';
    $params[] = $_SESSION['client_id'];
  } else {
    $sql .= ' LIMIT 1';
  }

  $stmt = $mysqli->prepare($sql);
  if ($stmt) {
    // bind params dynamically
    $bind_names[] = $types;
    for ($i=0;$i<count($params);$i++) {
      $bind_name = 'bind' . $i;
      $$bind_name = $params[$i];
      $bind_names[] = &$$bind_name;
    }
    call_user_func_array([$stmt,'bind_param'], $bind_names);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    if (!$payment) $allowed = false;
    // normalize common fields for template
    if ($payment) {
      // detect amount column
      $amount_col = $find_col('payments', ['amount','amountPaid','amount_paid','expectedAmount','amountPaid']);
      $payment['amount_norm'] = $amount_col && isset($payment[$amount_col]) ? $payment[$amount_col] : ($payment['amount'] ?? 0);
      // detect reference
      $ref_col = $find_col('payments', ['reference','mpesaCode','mpesa_code','mpesaRef','mpesaCode']);
      $payment['reference_norm'] = $ref_col && isset($payment[$ref_col]) ? $payment[$ref_col] : ($payment['reference'] ?? '');
      // detect date
      $date_col = $find_col('payments', ['paid_at','paidAt','dateofPayment','dateofpayment','date_of_payment','date']);
      $payment['paid_at_norm'] = $date_col && isset($payment[$date_col]) ? $payment[$date_col] : ($payment['paid_at'] ?? ($payment['dateofPayment'] ?? ''));
      // detect invoice description later from invoices table if possible
      // try to resolve invoice description if payment references an invoice
      $payment['invoice_description'] = $payment['invoice_description'] ?? '';
      if (!empty($pay_invoice_col) && isset($payment[$pay_invoice_col])) {
        // determine invoices table id column and description column
        if (has_column($mysqli,'invoices','id')) {
          $inv_id_col = 'id';
        } elseif (has_column($mysqli,'invoices','invoiceNumber')) {
          $inv_id_col = 'invoiceNumber';
        } else {
          $inv_id_col = null;
        }
        $inv_desc_col = null;
        if (has_column($mysqli,'invoices','description')) $inv_desc_col = 'description';
        elseif (has_column($mysqli,'invoices','comment')) $inv_desc_col = 'comment';
        elseif (has_column($mysqli,'invoices','invoiceNumber')) $inv_desc_col = 'invoiceNumber';
        if ($inv_id_col && $inv_desc_col) {
          $v = $payment[$pay_invoice_col];
          $sqli = "SELECT `".$mysqli->real_escape_string($inv_desc_col)."` AS d FROM `invoices` WHERE `".$mysqli->real_escape_string($inv_id_col)."` = ? LIMIT 1";
          $sti = $mysqli->prepare($sqli);
          if ($sti) { $sti->bind_param('s', $v); $sti->execute(); $rowi = $sti->get_result()->fetch_assoc(); if ($rowi && !empty($rowi['d'])) $payment['invoice_description'] = $rowi['d']; }
        }
      }
      // attempt to resolve payer name from clients/tenants tables
      $payment['payer_name'] = '';
      $tableExists = function($t) use ($mysqli) { $r = $mysqli->query("SHOW TABLES LIKE '".$mysqli->real_escape_string($t)."'"); return $r && $r->num_rows>0; };
      $nameFound = false;
      if ($pay_client_col && isset($payment[$pay_client_col])) {
        $cid = $payment[$pay_client_col];
        // try tenants table first
        $fetchFromTable = function($table, $idCols, $nameCols, $cid) use ($mysqli) {
          foreach($idCols as $idc) {
            if (!has_column($mysqli, $table, $idc)) continue;
            foreach($nameCols as $nc) {
              if (!has_column($mysqli, $table, $nc)) continue;
              $sql = "SELECT `".$mysqli->real_escape_string($nc)."` AS nm FROM `".$mysqli->real_escape_string($table)."` WHERE `".$mysqli->real_escape_string($idc)."` = ? LIMIT 1";
              $st = $mysqli->prepare($sql);
              if ($st) { $st->bind_param('s', $cid); $st->execute(); $row = $st->get_result()->fetch_assoc(); if ($row && !empty($row['nm'])) return $row['nm']; }
            }
          }
          return null;
        };
        $idCandidates = ['id','tenantID','tenant_id','clientID','client_id'];
        $nameCandidates = ['full_name','tenant_name','name','fullname','first_name'];
        if ($tableExists('tenants')) {
          $n = $fetchFromTable('tenants', $idCandidates, $nameCandidates, $cid);
          if ($n) { $payment['payer_name'] = $n; $nameFound = true; }
        }
        if (!$nameFound && $tableExists('clients')) {
          $n = $fetchFromTable('clients', $idCandidates, $nameCandidates, $cid);
          if ($n) { $payment['payer_name'] = $n; $nameFound = true; }
        }
      }
      if (!$payment['payer_name']) {
        // fallbacks
        if (isset($payment['full_name'])) $payment['payer_name'] = $payment['full_name'];
        elseif (isset($payment['tenant_name'])) $payment['payer_name'] = $payment['tenant_name'];
        else $payment['payer_name'] = '';
      }
    }
  }
}

  // debug helper: append ?debug=1 to see DB status and params (temporary)
  if (!empty($_GET['debug'])) {
    echo "<pre style=\"background:#fff;padding:12px;border:1px solid #eee;max-width:900px;margin:16px auto;\">";
    if (function_exists('get_db_status')) {
      $status = get_db_status();
      echo "DB status:\n" . print_r($status, true) . "\n";
    }
    echo "Requested id: " . htmlspecialchars($id) . "\n";
    echo "Session client_id: " . htmlspecialchars($_SESSION['client_id'] ?? '');
    echo "\nPayment row:\n" . print_r($payment, true);
    echo "</pre>";
  }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Receipt - Rent-a-Kaya</title>
  <style>
    :root{--accent:#1f6feb;--muted:#6b7280;--bg:#f7fafc}
    body{font-family:Inter,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);margin:0;padding:24px;color:#111}
    .receipt-wrap{max-width:900px;margin:0 auto}
    .card{background:#fff;border-radius:10px;padding:28px;box-shadow:0 6px 20px rgba(17,24,39,0.06)}
    .header{display:flex;justify-content:space-between;align-items:flex-start;gap:16px}
    .brand{display:flex;align-items:center;gap:12px}
    .logo{width:56px;height:56px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#7dd3fc);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
    h1{font-size:18px;margin:0}
    .meta{text-align:right;color:var(--muted);font-size:13px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:18px}
    .box{background:#fbfdff;padding:14px;border-radius:8px;border:1px solid #eef2ff}
    table{width:100%;border-collapse:collapse;margin-top:18px}
    th,td{padding:10px;text-align:left;border-bottom:1px solid #f1f5f9}
    .total{font-weight:700;font-size:18px;color:#0f172a}
    .actions{margin-top:18px;display:flex;gap:8px}
    .btn{padding:10px 14px;border-radius:8px;border:0;cursor:pointer}
    .btn-primary{background:var(--accent);color:#fff}
    .btn-ghost{background:transparent;border:1px solid #e6eefc;color:var(--accent)}
    @media (max-width:700px){.grid{grid-template-columns:1fr}.meta{text-align:left}}
    /* print styles */
    @media print{
      body{background:#fff;padding:0}
      .actions, .logo{display:none}
      .card{box-shadow:none;border:0;padding:0}
      .receipt-wrap{margin:0}
    }
  </style>
</head>
<body>
  <div class="receipt-wrap">
    <div class="card">
      <div class="header">
        <div class="brand">
          <div class="logo"><img src="/rent-a-kaya/plugins/images/mainlogo.png" alt="Rent-a-Kaya" style="width:100%;height:100%;object-fit:cover;border-radius:6px"/></div>
          <div>
            <h1>Rent-a-Kaya</h1>
            <div style="color:var(--muted);font-size:13px">P.O. Box 12345 • Nairobi • Kenya</div>
          </div>
        </div>
        <div class="meta">
          <div id="ref">Reference: <?php echo $payment ? htmlspecialchars($payment['reference_norm'] ?? $payment['reference'] ?? 'DEMO-1001') : 'DEMO-1001'; ?></div>
          <div id="date"><?php echo $payment ? htmlspecialchars($payment['paid_at_norm'] ?? $payment['paid_at'] ?? $payment['dateofPayment'] ?? date('Y-m-d')) : date('Y-m-d'); ?></div>
        </div>
      </div>

      <?php if (!$payment || !$allowed): ?>
        <div style="margin-top:20px;color:var(--muted)">
          <h3 style="margin-top:0">Receipt not available</h3>
          <p>If this is a demo page or the payment cannot be found, try a different reference or return to your dashboard.</p>
        </div>
      <?php else: ?>
        <div class="grid" style="margin-top:18px">
          <div class="box">
            <div style="color:var(--muted);font-size:13px">Billed To</div>
            <div style="margin-top:6px;font-weight:600"><?php echo htmlspecialchars($payment['payer_name'] ?? ($payment['full_name'] ?? '')); ?></div>
            <div style="color:var(--muted);font-size:13px;margin-top:6px">Client ID: <?php echo htmlspecialchars(isset($pay_client_col) && isset($payment[$pay_client_col]) ? $payment[$pay_client_col] : ''); ?></div>
          </div>
          <div class="box">
            <div style="color:var(--muted);font-size:13px">Payment</div>
            <div style="margin-top:6px;font-weight:600">KES <?php echo number_format($payment['amount_norm'] ?? ($payment['amount'] ?? 0),2); ?></div>
            <div style="color:var(--muted);font-size:13px;margin-top:6px">Method: <?php echo htmlspecialchars(ucfirst($payment['method'] ?? ($payment['mpesaCode'] ?? ''))); ?></div>
            <?php if(!empty($pay_invoice_col) && !empty($payment[$pay_invoice_col])): ?>
              <div style="margin-top:8px"><span style="color:var(--accent);">Invoice:</span> <?php echo htmlspecialchars($payment[$pay_invoice_col]); ?></div>
            <?php endif; ?>
            <!-- QR code: encodes basic payment details -->
            <div id="qrcode" style="margin-top:12px"></div>
          </div>
        </div>

        <table aria-label="receipt-items">
          <thead>
            <tr><th style="width:70%">Description</th><th style="width:30%">Amount</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo htmlspecialchars($payment['invoice_description'] ?? ($payment['description'] ?? 'Payment received')); ?></td>
              <td>KES <?php echo number_format($payment['amount_norm'] ?? ($payment['amount'] ?? 0),2); ?></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align:right" class="total">Total</td>
              <td class="total">KES <?php echo number_format($payment['amount_norm'] ?? ($payment['amount'] ?? 0),2); ?></td>
            </tr>
          </tfoot>
        </table>

        <div style="margin-top:18px;color:var(--muted);font-size:13px">Paid By: <?php echo htmlspecialchars($payment['payer_name'] ?? ($payment['full_name'] ?? '')); ?> &middot; Reference: <?php echo htmlspecialchars($payment['reference_norm'] ?? ($payment['reference'] ?? '')); ?></div>
      <?php endif; ?>

      <div class="actions">
        <button class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
        <button class="btn btn-ghost" onclick="window.close()">Close</button>
      </div>
    </div>
  </div>
  <script>
    // friendly print focus
    (function(){
      // if ?print=1 in URL, auto-print
      try{
        var params = new URLSearchParams(location.search);
        if(params.get('print')==='1') setTimeout(function(){ window.print(); }, 300);
      }catch(e){}
    })();
  </script>
</body>
</html>

<!-- QR generation library and initialization (local) -->
<script src="/rent-a-kaya/plugins/js/qrcode.min.js"></script>
<script>
  (function(){
    <?php if($payment && $allowed): ?>
      var qrData = <?php echo json_encode([
        'reference' => $payment['reference_norm'] ?? ($payment['reference'] ?? 'DEMO-1001'),
        'amount' => number_format($payment['amount_norm'] ?? ($payment['amount'] ?? 0),2),
        'payer' => $payment['payer_name'] ?? ($payment['full_name'] ?? ''),
        'date' => $payment['paid_at_norm'] ?? ($payment['paid_at'] ?? $payment['created_at'] ?? date('Y-m-d')),
        'invoice_id' => (isset($pay_invoice_col) && isset($payment[$pay_invoice_col])) ? $payment[$pay_invoice_col] : null,
        'receipt_url' => '/rent-a-kaya/client/receipt.php?id=' . ($id ?? 0)
      ], JSON_UNESCAPED_SLASHES); ?>;
      try{
        var container = document.getElementById('qrcode');
        if(container){
          var qr = new QRCode(container, {text: JSON.stringify(qrData), width:140, height:140});
          // add download button
          var dl = document.createElement('button');
          dl.textContent = 'Download QR';
          dl.className = 'btn btn-ghost';
          dl.style.marginLeft='8px';
          dl.id='download-qr';
          container.parentNode.appendChild(dl);
          dl.addEventListener('click', function(){
            // find image inside QR container
            var img = container.querySelector('img');
            if(img && img.src){
              var a = document.createElement('a');
              a.href = img.src;
              a.download = 'receipt-qr-<?php echo htmlspecialchars($payment['reference'] ?? 'receipt'); ?>.png';
              document.body.appendChild(a);
              a.click();
              a.remove();
              showDownloadTooltip(dl,'Downloaded');
              return;
            }
            // fallback: try canvas
            var canvas = container.querySelector('canvas');
            if(canvas){
              var url = canvas.toDataURL('image/png');
              var a = document.createElement('a');
              a.href = url; a.download = 'receipt-qr-<?php echo htmlspecialchars($payment['reference'] ?? 'receipt'); ?>.png';
              document.body.appendChild(a); a.click(); a.remove();
              showDownloadTooltip(dl,'Downloaded');
            }
          });
        }
      }catch(e){ /* ignore QR errors */ }
    <?php endif; ?>
  })();
  // small helper to show transient tooltip near an element
  function showDownloadTooltip(target,msg){
    try{
      var tip = document.createElement('div');
      tip.textContent = msg || 'Saved';
      tip.style.position = 'absolute';
      tip.style.background = '#111';
      tip.style.color = '#fff';
      tip.style.padding = '6px 10px';
      tip.style.borderRadius = '6px';
      tip.style.fontSize = '13px';
      tip.style.zIndex = 3000;
      tip.style.opacity = '0';
      tip.style.transition = 'opacity 180ms ease, transform 180ms ease';
      document.body.appendChild(tip);
      var rect = target.getBoundingClientRect();
      tip.style.left = (rect.left + window.scrollX + rect.width/2 - tip.offsetWidth/2) + 'px';
      tip.style.top = (rect.top + window.scrollY - 40) + 'px';
      // reflow then show
      window.getComputedStyle(tip).opacity;
      tip.style.opacity = '1';
      tip.style.transform = 'translateY(-4px)';
      setTimeout(function(){ tip.style.opacity='0'; tip.style.transform='translateY(-8px)'; setTimeout(function(){ tip.remove(); },220); }, 1600);
    }catch(e){/* ignore */}
  }
</script>
