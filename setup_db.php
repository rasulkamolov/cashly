<?php
// setup_db.php

$db_file = __DIR__ . '/database/finance.db';
$schema_file = __DIR__ . '/database/schema.sql';

if (file_exists($db_file)) {
    echo "Database file already exists.\n";
    // For development, we might want to delete and recreate it
    // unlink($db_file);
}

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents($schema_file);

    // Split SQL by semicolons to execute multiple statements properly
    // PDO::exec can handle multiple statements in SQLite usually, but let's be safe
    // SQLite supports executing multiple statements in one go with exec()
    $pdo->exec($sql);

    echo "Database initialized successfully.\n";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}
?>
