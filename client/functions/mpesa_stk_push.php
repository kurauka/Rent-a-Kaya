<?php
// Simple STK Push initiator for Safaricom Daraja (sandbox)
// IMPORTANT: Replace the placeholders with your Daraja credentials and callback URL.
header('Content-Type: application/json');

$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$reference = isset($_POST['reference']) ? trim($_POST['reference']) : ('REF'.time());

if(!$phone || $amount <= 0){ echo json_encode(['success'=>false,'message'=>'Invalid phone or amount']); exit; }

// Normalize phone to international format (expecting 2547XXXXXXXX or starting with 07)
$phone = preg_replace('/[^0-9]+/','',$phone);
if(strlen($phone) == 9 && strpos($phone, '7') === 0) { $phone = '254' . $phone; }
if(strlen($phone)==10 && strpos($phone,'07')===0){ $phone = '254' . substr($phone,1); }

// Load configuration
$configPath = __DIR__ . '/mpesa_config.php';
$cfg = file_exists($configPath) ? include $configPath : [];
$consumerKey = $cfg['consumer_key'] ?? '';
$consumerSecret = $cfg['consumer_secret'] ?? '';
$shortcode = $cfg['shortcode'] ?? '';
$passkey = $cfg['passkey'] ?? '';

// Build callback URL: prefer config, otherwise derive from request
if (!empty($cfg['callback_url'])) {
  $callbackUrl = $cfg['callback_url'];
} else {
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']==='https') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $base = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
  $callbackUrl = $scheme . '://' . $host . $base . '/mpesa_callback.php';
}

// OAuth token (sandbox)
$tokenUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
$res = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$tokenLog = date('[Y-m-d H:i:s] ') . "TOKEN HTTP={$http} RESPONSE=" . ($res ?? 'NULL') . "\n";
@file_put_contents(__DIR__ . '/mpesa_stk_push.log', $tokenLog, FILE_APPEND);
if(!$res || $http !== 200){ echo json_encode(['success'=>false,'message'=>'Failed to obtain access token','debug'=>$res]); exit; }
$body = json_decode($res, true);
if(empty($body['access_token'])){ @file_put_contents(__DIR__ . '/mpesa_stk_push.log', date('[Y-m-d H:i:s] ') . "NO_ACCESS_TOKEN: " . $res . "\n", FILE_APPEND); echo json_encode(['success'=>false,'message'=>'No access token in response','debug'=>$res]); exit; }
$accessToken = $body['access_token'];

$timestamp = date('YmdHis');
$password = base64_encode($shortcode . $passkey . $timestamp);

$stkUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$payload = [
  'BusinessShortCode' => $shortcode,
  'Password' => $password,
  'Timestamp' => $timestamp,
  'TransactionType' => 'CustomerPayBillOnline',
  'Amount' => $amount,
  'PartyA' => $phone,
  'PartyB' => $shortcode,
  'PhoneNumber' => $phone,
  'CallBackURL' => $callbackUrl,
  'AccountReference' => $reference,
  'TransactionDesc' => 'Rent payment'
];

$ch = curl_init($stkUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $accessToken
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// log raw provider response for debugging
@file_put_contents(__DIR__ . '/mpesa_stk_push.log', date('[Y-m-d H:i:s] ') . "STK HTTP={$http} CALLBACK={$callbackUrl} REQUEST=" . json_encode($payload) . " RESPONSE=" . ($res ?? 'NULL') . "\n", FILE_APPEND);
if(!$res){ echo json_encode(['success'=>false,'message'=>'No response from STK endpoint']); exit; }
$resp = json_decode($res, true);
if($http >=200 && $http <300 && isset($resp['ResponseCode']) && $resp['ResponseCode']=='0'){
  // STK Push accepted by provider
  echo json_encode(['success'=>true,'message'=>'STK Push initiated','data'=>$resp]);
  exit;
}

// build an informative error message if available
$errMsg = 'Provider error';
if(is_array($resp)){
  if(!empty($resp['errorMessage'])) $errMsg = $resp['errorMessage'];
  elseif(!empty($resp['ResponseDescription'])) $errMsg = $resp['ResponseDescription'];
  elseif(!empty($resp['message'])) $errMsg = $resp['message'];
  else $errMsg = json_encode($resp);
} else {
  $errMsg = (string)$res;
}

// Warn if callback URL looks non-public/HTTP
if(strpos($callbackUrl, 'http://localhost') === 0 || strpos($callbackUrl, 'http://127.0.0.1') === 0 || strpos($callbackUrl, 'http://192.') === 0){
  $errMsg .= ' (Callback URL may be non-public or non-HTTPS: ' . $callbackUrl . ')';
}

echo json_encode(['success'=>false,'message'=>$errMsg,'data'=>$resp]);

?>