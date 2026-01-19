<?php
// CLI-only script to add an admin user to the database.
// Usage: php create_admin.php email password "Full Name"
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

if ($argc < 3) {
    echo "Usage: php create_admin.php email password [Full Name]\n";
    exit(1);
}

$email = $argv[1];
$password = $argv[2];
$name = $argv[3] ?? 'Main Admin';

require_once __DIR__ . '/../functions/db.php';

if (empty($connection) && empty($mysqli) && empty($db)) {
    echo "No database connection available. Ensure .env is configured and DB is reachable.\n";
    exit(1);
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO admin (`name`, `role`, `email`, `password`, `date`) VALUES (?, 'level-0', ?, ?, NOW())";
if ($mysqli) {
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sss', $name, $email, $hash);
    if ($stmt->execute()) echo "Admin created: $email\n"; else echo "Failed to create admin: " . $mysqli->error . "\n";
    exit;
}

try {
    $st = $db->prepare($sql);
    $st->execute([$name, $email, $hash]);
    echo "Admin created: $email\n";
} catch (Exception $e) {
    echo "Failed to create admin: " . $e->getMessage() . "\n";
}

?>
