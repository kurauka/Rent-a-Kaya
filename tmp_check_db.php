<?php
// Tiny test harness that includes the project's db.php and reports connection state
require __DIR__ . '/admin/functions/db.php';

$status = get_db_status();
echo "DB status:\n";
foreach ($status as $k => $v) {
    if ($k === 'errors') continue;
    printf("  %-12s: %s\n", $k, $v ? 'OK' : 'NO');
}
if (!empty($status['errors'])) {
    echo "Errors:\n";
    foreach ($status['errors'] as $err) echo "  - " . $err . "\n";
}

?>