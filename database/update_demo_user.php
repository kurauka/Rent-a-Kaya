<?php
require_once __DIR__ . '/../admin/functions/db.php';

$old = 'tenant@example.com';
$new = 'tenant@kaya.com';
$password = 'tenant1';
$hash = password_hash($password, PASSWORD_DEFAULT);

if (isset($db) && $db instanceof PDO) {
    $stmt = $db->prepare('UPDATE clients SET email = :new, password_hash = :hash WHERE email = :old');
    $stmt->execute([':new'=>$new,':hash'=>$hash,':old'=>$old]);
    echo "Updated via PDO, rows: " . $stmt->rowCount() . "\n";
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
    $safeOld = $mysqli->real_escape_string($old);
    $safeNew = $mysqli->real_escape_string($new);
    $safeHash = $mysqli->real_escape_string($hash);
    $res = $mysqli->query("UPDATE clients SET email = '$safeNew', password_hash = '$safeHash' WHERE email = '$safeOld'");
    if ($res) echo "Updated via mysqli.\n"; else echo "mysqli update error: " . $mysqli->error . "\n";
} else {
    echo "No DB connection available.\n";
}
