<?php
// Simple callback endpoint for M-PESA STK Push responses.
// Safaricom will POST JSON here. We log the payload and, if desired, update DB records.

$raw = file_get_contents('php://input');
@file_put_contents(__DIR__ . '/mpesa_callback.log', date('[Y-m-d H:i:s] ') . $raw . "\n", FILE_APPEND);

// Attempt to parse STK callback and record successful payments to DB if possible
header('Content-Type: application/json');
try{
	$data = json_decode($raw, true);
	// navigate to stkCallback if present
	$callback = $data['Body']['stkCallback'] ?? null;
	if($callback && isset($callback['ResultCode'])){
		$result = (int)$callback['ResultCode'];
		$checkoutRequestID = $callback['CheckoutRequestID'] ?? null;
		$merchantRequestID = $callback['MerchantRequestID'] ?? null;
		$items = $callback['CallbackMetadata']['Item'] ?? [];
		$meta = [];
		foreach($items as $it){ if(isset($it['Name'])) $meta[$it['Name']] = $it['Value'] ?? null; }

		$amount = isset($meta['Amount']) ? floatval($meta['Amount']) : null;
		$mpesaReceipt = $meta['MpesaReceiptNumber'] ?? null;
		$phone = $meta['PhoneNumber'] ?? null;
		$accountRef = $meta['AccountReference'] ?? null;

		if($result === 0 && $amount){
			// record payment if DB available
			$dbPath = __DIR__ . '/../../admin/functions/db.php';
			if(file_exists($dbPath)){
				include_once $dbPath; // provides $mysqli and helpers

				if(isset($mysqli) && $mysqli){
					// normalize phone for lookup (expect 2547...)
					$normPhone = preg_replace('/[^0-9]+/','', (string)$phone);
					if(strlen($normPhone)==9 && strpos($normPhone,'7')===0) $normPhone = '254'.$normPhone;
					if(strlen($normPhone)==10 && strpos($normPhone,'07')===0) $normPhone = '254'.substr($normPhone,1);

					$tenantID = null;
					// try tenants table (common schema with phone_number)
					$q = $mysqli->prepare('SELECT tenantID FROM tenants WHERE phone_number = ? LIMIT 1');
					if($q){ $q->bind_param('s', $normPhone); $q->execute(); $r = $q->get_result(); if($r && $row = $r->fetch_assoc()) $tenantID = $row['tenantID']; @$q->close(); }

					// decide how to insert depending on payments schema
					$hasTenantID = false; $hasClientID = false;
					$res = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'tenantID'"); if($res && $res->num_rows>0) $hasTenantID = true;
					$res = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'client_id'"); if($res && $res->num_rows>0) $hasClientID = true;

					$now = date('Y-m-d H:i:s');
					if($hasTenantID){
						// insert into payments (tenantID, invoiceNumber, expectedAmount, amountPaid, balance, mpesaCode, dateofPayment, comment)
						$stmt = $mysqli->prepare('INSERT INTO payments (tenantID, invoiceNumber, expectedAmount, amountPaid, balance, mpesaCode, dateofPayment, comment) VALUES (?,?,?,?,?,?,?,?)');
						$invoice = $accountRef ?: '';
						$expected = 0;
						$balance = 0;
						$mpesaCode = $mpesaReceipt ?: '';
						$comment = 'Lipa na M-PESA STK push';
						$tid = $tenantID ?: 0;
						$stmt->bind_param('isddisss', $tid, $invoice, $expected, $amount, $balance, $mpesaCode, $now, $comment);
						@$stmt->execute();
					} else if($hasClientID){
						// fallback to client_id schema used by client area
						$stmt = $mysqli->prepare('INSERT INTO payments (client_id, invoice_id, house_number, amount, method, reference) VALUES (?,?,?,?,?,?)');
						$nullInv = null; $nullHouse = null;
						// attempt to map tenant -> client by email/other not available; leave client_id NULL if not found
						$clientId = null;
						$method = 'mpesa';
						$ref = $mpesaReceipt ?: ($accountRef ?: $checkoutRequestID ?: $merchantRequestID ?: 'mpesa_'.$checkoutRequestID);
						$stmt->bind_param('iisdss', $clientId, $nullInv, $nullHouse, $amount, $method, $ref);
						@$stmt->execute();
					} else {
						// Unknown payments schema: append a log entry
						@file_put_contents(__DIR__ . '/mpesa_unhandled_schema.log', date('[Y-m-d H:i:s] ') . json_encode(['amount'=>$amount,'phone'=>$normPhone,'receipt'=>$mpesaReceipt]) . "\n", FILE_APPEND);
					}
				}
			}
		}
	}
} catch(Exception $e){
	@file_put_contents(__DIR__ . '/mpesa_callback_error.log', date('[Y-m-d H:i:s] ') . $e->getMessage() . "\n", FILE_APPEND);
}

echo json_encode(['received'=>true]);
?>