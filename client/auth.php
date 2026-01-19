<?php
session_start();
require_once __DIR__ . '/../admin/functions/db.php';

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!$email || !$password) {
        $error = 'Please provide email and password.';
    } else {
        $table_missing = true;
        if (!empty($mysqli)) {
            try {
                $res = $mysqli->query("SHOW TABLES LIKE 'clients'");
                if ($res && $res->num_rows > 0) $table_missing = false;
            } catch (Exception $e) { $table_missing = true; }
        }

        // Demo fallback
        if ($table_missing) {
            if (($email === 'tenant' || $email === 'tenant@example.com' || $email === 'tenant@kaya.com') && $password === 'tenant1') {
                $_SESSION['client_id'] = 0;
                $_SESSION['client_name'] = 'Demo Tenant';
                $_SESSION['house_number'] = 'A1';
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Demo mode: use tenant / tenant1 or contact admin.';
            }
        } else {
            try {
                $stmt = $mysqli->prepare('SELECT id, full_name, password_hash, house_number FROM clients WHERE email = ? AND status = "active" LIMIT 1');
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    if (password_verify($password, $row['password_hash'])) {
                        $_SESSION['client_id'] = $row['id'];
                        $_SESSION['client_name'] = $row['full_name'];
                        $_SESSION['house_number'] = $row['house_number'];
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $error = 'Invalid credentials.';
                    }
                } else {
                    $error = 'No active account found for that email.';
                }
            } catch (Exception $e) { $error = 'Authentication error.'; }
        }
    }
}

// Redirect back to the unified login page with a short error and preserve email
$dest = '/rent-a-kaya/login.php';
$params = [];
if (!empty($error)) $params['error'] = $error;
if (!empty($email)) $params['email'] = $email;
$qs = http_build_query($params);
header('Location: ' . $dest . ($qs ? ('?' . $qs) : ''));
exit;

// end of script - redirection has already been performed above
