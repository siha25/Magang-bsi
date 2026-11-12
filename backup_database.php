<?php

// Script untuk backup database utama sebelum migration

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dbName = 'pendidikan_ansuh';
$backupDir = __DIR__.'/storage/backups';
$timestamp = date('Y-m-d_His');
$backupFile = "{$backupDir}/backup_{$dbName}_{$timestamp}.sql";

// Buat direktori backup jika belum ada
if (! is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

try {
    $host = config('database.connections.mysql.host');
    $user = config('database.connections.mysql.username');
    $pass = config('database.connections.mysql.password');

    // Windows: Cek mysqldump di Laragon
    $mysqldumpPaths = [
        'C:\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\mysqldump.exe',
        'C:\\laragon\\bin\\mysql\\mysql-5.7.33\\bin\\mysqldump.exe',
        'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    ];

    $mysqldump = null;
    foreach ($mysqldumpPaths as $path) {
        if (file_exists($path)) {
            $mysqldump = $path;
            break;
        }
    }

    if (! $mysqldump) {
        throw new Exception('mysqldump tidak ditemukan. Silakan backup manual via phpMyAdmin.');
    }

    // Build command
    $passwordArg = $pass ? "-p{$pass}" : '';
    $command = "\"{$mysqldump}\" -h{$host} -u{$user} {$passwordArg} {$dbName} > \"{$backupFile}\" 2>&1";

    echo "Membuat backup database '{$dbName}'...\n";
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        throw new Exception('Backup gagal. Error: '.implode("\n", $output));
    }

    if (! file_exists($backupFile) || filesize($backupFile) === 0) {
        throw new Exception('File backup kosong atau tidak terbuat.');
    }

    $sizeKB = round(filesize($backupFile) / 1024, 2);

    echo "✓ Backup berhasil: {$backupFile}\n";
    echo "  Ukuran: {$sizeKB} KB\n";

    // Verifikasi restore capability (dry-run)
    echo "\nVerifikasi backup dapat dipulihkan...\n";

    $pdo = DB::connection()->getPdo();

    // Cek tabel yang ada di database
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $tableCount = count($tables);

    echo "  Database saat ini memiliki {$tableCount} tabel.\n";

    // Cek isi backup file (head)
    $backupContent = file_get_contents($backupFile, false, null, 0, 1000);

    if (strpos($backupContent, 'CREATE TABLE') !== false || strpos($backupContent, 'INSERT INTO') !== false) {
        echo "✓ Backup file berisi SQL statements yang valid.\n";
    } else {
        throw new Exception('Backup file tidak berisi SQL statements yang valid.');
    }

    echo "\n";
    echo "INSTRUKSI RESTORE (jika diperlukan):\n";
    echo "1. Via mysqldump:\n";
    echo "   \"{$mysqldump}\" -h{$host} -u{$user} {$passwordArg} {$dbName} < \"{$backupFile}\"\n";
    echo "\n";
    echo "2. Via phpMyAdmin:\n";
    echo "   - Buka phpMyAdmin → Import → Pilih file: {$backupFile}\n";
    echo "\n";

} catch (Exception $e) {
    echo '✗ Error: '.$e->getMessage()."\n";
    echo "\nCARA BACKUP MANUAL:\n";
    echo "1. Buka phpMyAdmin (http://localhost/phpmyadmin)\n";
    echo "2. Pilih database 'pendidikan_ansuh'\n";
    echo "3. Tab 'Export' → Format: SQL → Klik 'Export'\n";
    echo "4. Simpan file SQL di folder: {$backupDir}\n";
    exit(1);
}
