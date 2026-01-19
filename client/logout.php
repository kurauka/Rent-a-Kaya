<?php
session_start();
// Clear client session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

// ensure browsers don't cache tenant pages after logout
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'suspended' => true, 'redirect' => '/rent-a-kaya/login.php']);
    exit;
}

// fallback for normal requests: redirect to login page
header('Location: /rent-a-kaya/login.php');
exit;
