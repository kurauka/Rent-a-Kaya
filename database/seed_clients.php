<?php
// Seeder script: runs database/client_schema.sql and inserts a demo client
require_once __DIR__ . '/../admin/functions/db.php';

echo "Starting client DB seeder...\n";

$sqlFile = __DIR__ . '/client_schema.sql';
if (!file_exists($sqlFile)) {
    echo "ERROR: SQL file not found: $sqlFile\n";
    exit(1);
}

$sql = file_get_contents($sqlFile);
if (!$sql) {
    echo "ERROR: Could not read SQL file.\n";
    exit(1);
}

// Prefer PDO if available
if (isset($db) && $db instanceof PDO) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Split on semicolon followed by newline for safety
        $stmts = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));
        foreach ($stmts as $s) {
            if ($s) $db->exec($s);
        }
        echo "SQL migration executed via PDO.\n";
    } catch (Exception $e) {
        echo "PDO error executing migration: " . $e->getMessage() . "\n";
        // continue to attempt seeding if possible
    }
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
    // Use multi_query for mysqli
    if ($mysqli->multi_query($sql)) {
        do { /* flush */ } while ($mysqli->more_results() && $mysqli->next_result());
        echo "SQL migration executed via mysqli.\n";
    } else {
        echo "mysqli error executing migration: " . $mysqli->error . "\n";
    }
} else {
    echo "No database connection available (PDO or mysqli). Start DB and retry.\n";
    exit(1);
}

// Insert demo client if not exists
$demoEmail = 'tenant@example.com';
$demoName = 'Demo Tenant';
$demoHouse = 'A1';
$demoPassword = 'tenant1';
$hash = password_hash($demoPassword, PASSWORD_DEFAULT);

try {
    if (isset($db) && $db instanceof PDO) {
        $stmt = $db->prepare('SELECT id FROM clients WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $demoEmail]);
        if ($stmt->fetch()) {
            echo "Demo client already exists (email: $demoEmail).\n";
        } else {
            $ins = $db->prepare('INSERT INTO clients (full_name,email,password_hash,house_number) VALUES (:name,:email,:hash,:house)');
            $ins->execute([':name'=>$demoName,':email'=>$demoEmail,':hash'=>$hash,':house'=>$demoHouse]);
            echo "Inserted demo client: $demoEmail / $demoPassword\n";
        }
    } elseif (isset($mysqli) && $mysqli instanceof mysqli) {
        $safeEmail = $mysqli->real_escape_string($demoEmail);
        $res = $mysqli->query("SELECT id FROM clients WHERE email = '$safeEmail' LIMIT 1");
        if ($res && $res->num_rows) {
            echo "Demo client already exists (email: $demoEmail).\n";
        } else {
            $safeName = $mysqli->real_escape_string($demoName);
            $safeHash = $mysqli->real_escape_string($hash);
            $safeHouse = $mysqli->real_escape_string($demoHouse);
            $insQ = "INSERT INTO clients (full_name,email,password_hash,house_number) VALUES ('$safeName','$safeEmail','$safeHash','$safeHouse')";
            if ($mysqli->query($insQ)) echo "Inserted demo client: $demoEmail / $demoPassword\n";
            else echo "Failed to insert demo client: " . $mysqli->error . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error seeding demo client: " . $e->getMessage() . "\n";
}

echo "Seeder finished.\n";
