<?php

// Script untuk membuat database testing terpisah

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $pdo = DB::connection()->getPdo();

    // Buat database testing
    $pdo->exec('CREATE DATABASE IF NOT EXISTS pendidikan_ansuh_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    echo "✓ Database 'pendidikan_ansuh_test' berhasil dibuat.\n";

    // Verifikasi database yang ada
    $databases = $pdo->query("SHOW DATABASES LIKE 'pendidikan_ansuh%'")->fetchAll(PDO::FETCH_COLUMN);

    echo "\nDatabase yang tersedia:\n";
    foreach ($databases as $db) {
        echo "  • {$db}\n";
    }

} catch (Exception $e) {
    echo '✗ Error: '.$e->getMessage()."\n";
    exit(1);
}
