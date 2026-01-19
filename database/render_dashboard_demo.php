<?php
// Render the dashboard in CLI for demo tenant by setting session variables then including dashboard.php
chdir(__DIR__ . '/..');
// start session in CLI - use a temp session id
if (session_status() == PHP_SESSION_NONE) session_start();
$_SESSION['client_id'] = 0;
$_SESSION['client_name'] = 'Demo Tenant';
$_SESSION['house_number'] = 'A1';

ob_start();
include __DIR__ . '/../client/dashboard.php';
$out = ob_get_clean();
// Print first 400 chars to confirm
echo "--- Dashboard snapshot ---\n";
echo substr(strip_tags($out),0,400) . "\n";
echo "--- end ---\n";
