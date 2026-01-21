<?php
// CLI-only script to reset admin password.
// Usage: php reset_admin_password.php email@example.com newpassword
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

if ($argc < 3) {
    echo "Usage: php reset_admin_password.php email@example.com newpassword\n";
    echo "Example: php reset_admin_password.php obed@example.com mimi\n";
    exit(1);
}

$email = $argv[1];
$password = $argv[2];

require_once __DIR__ . '/../functions/db.php';

if (empty($connection) && empty($mysqli) && empty($db)) {
    echo "No database connection available. Ensure .env is configured and DB is reachable.\n";
    exit(1);
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$sql = "UPDATE admin SET password = ? WHERE email = ? LIMIT 1";
if ($mysqli) {
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: " . $mysqli->error . "\n";
        exit(1);
    }
    $stmt->bind_param('ss', $hash, $email);
    if ($stmt->execute()) {
        echo "Password updated for: $email\n";
        echo "New password hash: $hash\n";
    } else {
        echo "Update failed: " . $mysqli->error . "\n";
        exit(1);
    }
    exit;
}

try {
    $st = $db->prepare($sql);
    $st->execute([$hash, $email]);
    echo "Password updated for: $email\n";
    echo "New password hash: $hash\n";
} catch (Exception $e) {
    echo "Failed to update password: " . $e->getMessage() . "\n";
    exit(1);
}

?>
