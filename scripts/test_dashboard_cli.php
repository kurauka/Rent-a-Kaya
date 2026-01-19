<?php
// CLI test to render client/dashboard.php with a demo session
ini_set('display_errors', 1);
error_reporting(E_ALL);
chdir(__DIR__ . '/..'); // ensure working directory is project root
// start session if needed and set demo values
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$_SESSION['client_id'] = 0;
$_SESSION['client_name'] = 'Demo Tenant';
// run dashboard and capture output
ob_start();
include __DIR__ . '/../client/dashboard.php';
$html = ob_get_clean();
file_put_contents(__DIR__ . '/test_dashboard_output.html', $html);
echo "WROTE: scripts/test_dashboard_output.html\n";
?>