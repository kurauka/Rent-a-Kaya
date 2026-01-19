<?php
require_once __DIR__ . '/../admin/functions/db.php';
$email = 'tenant@kaya.com';
$password = 'tenant1';
if (isset($mysqli) && $mysqli instanceof mysqli) {
    $stmt = $mysqli->prepare('SELECT id, full_name, password_hash FROM clients WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        echo "Found user: " . $row['full_name'] . " (id=" . $row['id'] . ")\n";
        if (password_verify($password, $row['password_hash'])) echo "Password verified OK\n"; else echo "Password mismatch\n";
    } else echo "User not found\n";
} elseif (isset($db) && $db instanceof PDO) {
    $stmt = $db->prepare('SELECT id, full_name, password_hash FROM clients WHERE email = :email LIMIT 1');
    $stmt->execute([':email'=>$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "Found user: " . $row['full_name'] . " (id=" . $row['id'] . ")\n";
        if (password_verify($password, $row['password_hash'])) echo "Password verified OK\n"; else echo "Password mismatch\n";
    } else echo "User not found\n";
} else {
    echo "No DB connection available.\n";
}
