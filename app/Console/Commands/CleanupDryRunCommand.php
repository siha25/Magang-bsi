<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;
use Throwable;

#[Signature('simpena:cleanup-dry-run')]
#[Description('Simulasikan penghapusan data percobaan dan selalu rollback')]
class CleanupDryRunCommand extends Command
{
    private const USER_EXPECTATIONS = [
        57 => ['Pengguna Uji', 'user-6ad82b346e93b@example.com', 'admin_pusat', 0, null],
        58 => ['Pengguna Uji', 'user-6ad82b34ca839@example.com', 'admin_pusat', 1, null],
        59 => ['Pengguna Uji', 'user-6ad82b351b545@example.com', 'admin_cabang', 1, 2],
        60 => ['Pengguna Uji', 'user-6ad82b3542f1d@example.com', 'admin_cabang', 1, 4],
        61 => ['Pengguna Uji', 'user-6ad82b35693af@example.com', 'pimpinan_pusat', 1, null],
        62 => ['Pengguna Uji', 'user-6ad82b467e6ab@example.com', 'admin_pusat', 0, null],
        63 => ['Pengguna Uji', 'user-6ad82b46abf21@example.com', 'admin_pusat', 1, null],
        64 => ['Pengguna Uji', 'user-6ad82b470688e@example.com', 'pimpinan_pusat', 1, null],
        69 => ['Pengguna Uji', 'user-6ad82b5f9c991@example.com', 'admin_pusat', 0, null],
        70 => ['Pengguna Uji', 'user-6ad82b5fd0960@example.com', 'admin_pusat', 1, null],
        71 => ['Pengguna Uji', 'user-6ad82b600c06d@example.com', 'admin_cabang', 1, 8],
        72 => ['Pengguna Uji', 'user-6ad82b6024971@example.com', 'admin_cabang', 1, 10],
        73 => ['Pengguna Uji', 'user-6ad82b603b43f@example.com', 'pimpinan_pusat', 1, null],
        78 => ['Pengguna Uji', 'user-6ad82d946d8e5@example.com', 'admin_pusat', 0, null],
        79 => ['Pengguna Uji', 'user-6ad82d9710da9@example.com', 'admin_pusat', 1, null],
        80 => ['Pengguna Uji', 'user-6ad82d99a0c4c@example.com', 'admin_cabang', 1, 12],
        81 => ['Pengguna Uji', 'user-6ad82d9a41b99@example.com', 'admin_cabang', 1, 14],
        82 => ['Pengguna Uji', 'user-6ad82d9aacd43@example.com', 'pimpinan_pusat', 1, null],
    ];

    private const ASRAMA_EXPECTATIONS = [
        1 => ['Asrama percobaan siha - update', 'RH-001'],
        2 => ['Asrama A', 'A01'],
        3 => ['Asrama B', 'B01'],
        4 => ['Asrama D', 'D01'],
        5 => ['Asrama E', 'E01'],
        8 => ['Asrama A', 'A01_6ad82b6008e53'],
        9 => ['Asrama B', 'B01_6ad82b600b1dd'],
        10 => ['Asrama D', 'D01_6ad82b6021bfc'],
        11 => ['Asrama E', 'E01_6ad82b6023b49'],
        12 => ['Asrama 01', 'A01_6ad82d99808dc'],
        13 => ['Asrama B', 'B01_6ad82d999dfb7'],
        14 => ['Asrama 02', 'D01_6ad82d9a15420'],
        15 => ['Asrama 03', 'E01_6ad82d9a3c568'],
    ];

    private const TABLES = [
        'users',
        'asrama',
        'anak_asuh',
        'kegiatan_harian',
        'evaluasi_diniyah',
        'asrama_code_counters',
        'sessions',
        'password_reset_tokens',
    ];

    private const EXPECTED_FOREIGN_KEYS = [
        'anak_asuh|asrama_id|asrama|id|RESTRICT|NO ACTION',
        'evaluasi_diniyah|anak_asuh_id|anak_asuh|id|CASCADE|NO ACTION',
        'evaluasi_diniyah|asrama_id|asrama|id|CASCADE|NO ACTION',
        'evaluasi_diniyah|diajukan_oleh|users|id|CASCADE|NO ACTION',
        'evaluasi_diniyah|dinilai_oleh|users|id|SET NULL|NO ACTION',
        'kegiatan_harian|asrama_id|asrama|id|CASCADE|NO ACTION',
        'kegiatan_harian|penginput_id|users|id|CASCADE|NO ACTION',
        'users|asrama_id|asrama|id|SET NULL|NO ACTION',
    ];

    /**
     * Execute a rollback-only cleanup simulation.
     */
    public function handle(): int
    {
        $connection = DB::connection('mysql');

        if ($connection->getDriverName() !== 'mysql' || $connection->getDatabaseName() !== 'laravel') {
            $this->error('Simulasi hanya diizinkan pada koneksi MySQL ke database [laravel].');

            return self::FAILURE;
        }

        $currentDatabase = $connection->selectOne('SELECT DATABASE() AS database_name');
        if (($currentDatabase->database_name ?? null) !== 'laravel') {
            $this->error('Database aktif pada koneksi bukan [laravel].');

            return self::FAILURE;
        }

        if ($connection->transactionLevel() !== 0) {
            $this->error('Simulasi menolak koneksi yang sudah berada di dalam transaksi.');

            return self::FAILURE;
        }

        try {
            $this->assert($this->transactionState($connection) === false, 'Status transaksi awal tidak dapat dipastikan.');
            $baselineMetadata = $this->captureMetadata($connection);
            $this->reportAutoIncrementMetadata($baselineMetadata['auto_increment'], 'baseline');
            $this->validateTargets($connection, lockRows: false);
            $baselineSnapshot = $this->captureDataSnapshot($connection, lockRows: false);
            $pdoBeforeTransaction = $connection->getPdo();
        } catch (Throwable $exception) {
            $this->error('Pemeriksaan awal gagal; tidak ada transaksi simulasi yang dimulai. Periksa konfigurasi dan database secara read-only.');

            return self::FAILURE;
        }

        $simulationException = null;
        $rollbackException = null;
        $transactionStarted = false;
        $transactionAttempted = false;
        $rollbackAttempted = false;
        $transactionStateUncertain = false;

        try {
            $transactionAttempted = true;
            $connection->beginTransaction();
            $transactionStarted = true;

            $this->assert(
                $this->transactionState($connection) === true,
                'Status transaksi setelah beginTransaction tidak dapat dipastikan.'
            );
            $this->assertMetadataUnchanged($connection, $baselineMetadata);
            $this->validateTargets($connection, lockRows: true);
            $transactionSnapshot = $this->captureDataSnapshot($connection, lockRows: true);
            $this->assert(
                $transactionSnapshot === $baselineSnapshot,
                'Data target atau relasi berubah antara snapshot awal dan transaksi.'
            );
            $this->simulateDeletes($connection);

            $this->info('Semua penghapusan simulasi lolos validasi di dalam transaksi.');
        } catch (Throwable $exception) {
            $simulationException = $exception;
        } finally {
            $connectionMatches = $this->isSameConnection($connection, $pdoBeforeTransaction);
            $stateBeforeRollback = $this->transactionState($connection);

            if (
                $stateBeforeRollback === null
                || ($transactionStarted && $stateBeforeRollback !== true)
            ) {
                $transactionStateUncertain = true;
            }

            if (! $connectionMatches) {
                $transactionStateUncertain = true;
            } elseif ($transactionStarted || $stateBeforeRollback === true) {
                $rollbackAttempted = true;
                try {
                    $connection->rollBack();
                } catch (Throwable $exception) {
                    $rollbackException = $exception;
                }
            } elseif ($transactionAttempted && $stateBeforeRollback === null) {
                $transactionStateUncertain = true;
            }
        }

        $pdoAfterRollback = null;
        $transactionLevelAfterRollback = null;
        $pdoInTransactionAfterRollback = null;
        $sameConnectionAfterRollback = false;
        try {
            $pdoAfterRollback = $connection->getPdo();
            $transactionLevelAfterRollback = $connection->transactionLevel();
            $pdoInTransactionAfterRollback = $pdoAfterRollback->inTransaction();
            $sameConnectionAfterRollback = $pdoAfterRollback === $pdoBeforeTransaction;
        } catch (Throwable) {
            $transactionStateUncertain = true;
        }

        if (
            $rollbackException !== null
            || $transactionStateUncertain
            || ! $pdoAfterRollback instanceof PDO
            || $transactionLevelAfterRollback !== 0
            || $pdoInTransactionAfterRollback !== false
            || ! $sameConnectionAfterRollback
        ) {
            $this->reportUncertainTransactionState();

            return self::FAILURE;
        }

        $postRollbackException = null;
        try {
            $this->assert(
                $connection->getDatabaseName() === 'laravel'
                && ($connection->selectOne('SELECT DATABASE() AS database_name')->database_name ?? null) === 'laravel',
                'Koneksi pasca-rollback tidak lagi menunjuk database [laravel].'
            );
            $this->assertMetadataUnchanged($connection, $baselineMetadata);
            $this->validateTargets($connection, lockRows: false);
            $afterRollback = $this->captureMetadata($connection);
            $afterRollbackSnapshot = $this->captureDataSnapshot($connection, lockRows: false);
            $this->assert(
                $this->isSameConnection($connection, $pdoBeforeTransaction),
                'Koneksi database berubah selama pemeriksaan pasca-rollback.'
            );
            $this->assert(
                $afterRollbackSnapshot === $baselineSnapshot,
                'Snapshot data pasca-rollback berbeda dari snapshot sebelum transaksi.'
            );
            $this->assertStableMetadata($baselineMetadata, $afterRollback);
            $this->reportAutoIncrementMetadata($afterRollback['auto_increment'], 'setelah rollback');
            $this->reportAutoIncrementChanges($baselineMetadata['auto_increment'], $afterRollback['auto_increment']);
        } catch (Throwable $exception) {
            $postRollbackException = $exception;
        }

        if ($simulationException !== null) {
            $message = $rollbackAttempted
                ? 'Simulasi gagal; rollback dan status transaksi sudah diverifikasi. Detail target tidak dicetak.'
                : 'Simulasi gagal sebelum transaksi aktif; status transaksi sudah diverifikasi tidak aktif. Detail target tidak dicetak.';
            $this->error($message);
        }

        if ($postRollbackException !== null) {
            $this->error('Pemeriksaan pasca-rollback gagal. Periksa target dan metadata database secara read-only.');
        }

        if ($simulationException !== null || $postRollbackException !== null) {
            return self::FAILURE;
        }

        $this->info('Rollback eksplisit berhasil; seluruh target, akun utama, counter, dan struktur tetap terverifikasi.');

        return self::SUCCESS;
    }

    private function transactionState(Connection $connection): ?bool
    {
        try {
            $pdoInTransaction = $connection->getPdo()->inTransaction();
            $transactionLevel = $connection->transactionLevel();

            if ($transactionLevel === 0 && ! $pdoInTransaction) {
                return false;
            }

            if ($transactionLevel === 1 && $pdoInTransaction) {
                return true;
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    private function isSameConnection(Connection $connection, PDO $expectedPdo): bool
    {
        try {
            return $connection->getPdo() === $expectedPdo;
        } catch (Throwable) {
            return false;
        }
    }

    private function reportUncertainTransactionState(): void
    {
        $this->error('Status transaksi atau rollback tidak dapat dipastikan. Command berhenti tanpa pemulihan otomatis.');
        $this->line('Jangan jalankan ulang simulasi atau pembersihan. Periksa database secara read-only dari koneksi independen dan minta DBA menilai status sesi/transaksi.');
    }

    /**
     * @return array{
     *     auto_increment: array<string, array<string, int|null>>,
     *     auto_increment_columns: list<string>,
     *     create_fingerprints: array<string, string>,
     *     engines: array<string, string>,
     *     foreign_keys: list<string>,
     *     migrations: list<string>,
     *     primary_keys: list<string>,
     *     triggers: list<string>
     * }
     */
    private function captureMetadata(Connection $connection): array
    {
        $tableList = "'".implode("','", self::TABLES)."'";

        $informationSchemaAutoIncrement = [];
        foreach ($connection->select(
            'SELECT TABLE_NAME, AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ('.$tableList.')'
        ) as $row) {
            $informationSchemaAutoIncrement[$row->TABLE_NAME] = $row->AUTO_INCREMENT === null
                ? null
                : (int) $row->AUTO_INCREMENT;
        }

        $showStatusAutoIncrement = [];
        foreach ($connection->select('SHOW TABLE STATUS WHERE Name IN ('.$tableList.')') as $row) {
            $showStatusAutoIncrement[$row->Name] = $row->Auto_increment === null
                ? null
                : (int) $row->Auto_increment;
        }

        $createTableAutoIncrement = [];
        $createFingerprints = [];
        foreach (self::TABLES as $table) {
            $row = $connection->selectOne('SHOW CREATE TABLE `'.$table.'`');
            $createSql = (string) ($row->{'Create Table'} ?? '');

            preg_match('/\bAUTO_INCREMENT=(\d+)/i', $createSql, $matches);
            if (in_array($table, ['users', 'asrama', 'anak_asuh', 'kegiatan_harian'], true)) {
                $createTableAutoIncrement[$table] = isset($matches[1]) ? (int) $matches[1] : null;
            }
            $normalisedCreateSql = preg_replace('/\bAUTO_INCREMENT=\d+/i', 'AUTO_INCREMENT=<value>', $createSql);
            $createFingerprints[$table] = hash('sha256', (string) $normalisedCreateSql);
        }

        $autoIncrement = [];
        foreach (['users', 'asrama', 'anak_asuh', 'kegiatan_harian'] as $table) {
            $autoIncrement[$table] = [
                'information_schema' => $informationSchemaAutoIncrement[$table] ?? null,
                'show_table_status' => $showStatusAutoIncrement[$table] ?? null,
                'show_create_table' => $createTableAutoIncrement[$table] ?? null,
            ];
        }

        $engines = [];
        foreach ($connection->select(
            'SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ('.$tableList.')'
        ) as $row) {
            $engines[$row->TABLE_NAME] = (string) $row->ENGINE;
        }
        ksort($engines);

        $autoIncrementColumns = [];
        foreach ($connection->select(
            'SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, EXTRA
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME IN (\'users\', \'asrama\', \'anak_asuh\', \'kegiatan_harian\')
               AND EXTRA LIKE \'%auto_increment%\''
        ) as $row) {
            $autoIncrementColumns[] = implode('|', [
                $row->TABLE_NAME,
                $row->COLUMN_NAME,
                $row->COLUMN_TYPE,
                $row->EXTRA,
            ]);
        }
        sort($autoIncrementColumns);

        $foreignKeys = [];
        foreach ($connection->select(
            'SELECT k.TABLE_NAME, k.COLUMN_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, r.DELETE_RULE, r.UPDATE_RULE
             FROM information_schema.KEY_COLUMN_USAGE k
             JOIN information_schema.REFERENTIAL_CONSTRAINTS r
               ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA
              AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME
              AND r.TABLE_NAME = k.TABLE_NAME
             WHERE k.CONSTRAINT_SCHEMA = DATABASE()
               AND k.REFERENCED_TABLE_NAME IN (\'users\', \'asrama\', \'anak_asuh\')'
        ) as $row) {
            $foreignKeys[] = implode('|', [
                $row->TABLE_NAME,
                $row->COLUMN_NAME,
                $row->REFERENCED_TABLE_NAME,
                $row->REFERENCED_COLUMN_NAME,
                $row->DELETE_RULE,
                $row->UPDATE_RULE,
            ]);
        }
        sort($foreignKeys);

        $triggers = [];
        foreach ($connection->select(
            'SELECT TRIGGER_NAME, EVENT_OBJECT_TABLE, EVENT_MANIPULATION, ACTION_TIMING
             FROM information_schema.TRIGGERS
             WHERE TRIGGER_SCHEMA = DATABASE()
               AND EVENT_OBJECT_TABLE IN ('.$tableList.')'
        ) as $row) {
            $triggers[] = implode('|', [
                $row->TRIGGER_NAME,
                $row->EVENT_OBJECT_TABLE,
                $row->EVENT_MANIPULATION,
                $row->ACTION_TIMING,
            ]);
        }
        sort($triggers);

        $primaryKeys = [];
        foreach ($connection->select(
            'SELECT TABLE_NAME, SEQ_IN_INDEX, COLUMN_NAME, NON_UNIQUE
             FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME IN (\'users\', \'asrama\', \'anak_asuh\', \'kegiatan_harian\')
               AND INDEX_NAME = \'PRIMARY\'
             ORDER BY TABLE_NAME, SEQ_IN_INDEX'
        ) as $row) {
            $primaryKeys[] = implode('|', [
                $row->TABLE_NAME,
                (string) $row->SEQ_IN_INDEX,
                $row->COLUMN_NAME,
                (string) $row->NON_UNIQUE,
            ]);
        }

        $migrationRows = $connection->table('migrations')
            ->where('migration', '2026_10_21_071850_add_primary_admin_marker_to_users_table')
            ->orderBy('batch')
            ->get(['migration', 'batch']);
        $migrations = $migrationRows
            ->map(static fn (object $row): string => $row->migration.'|'.(string) $row->batch)
            ->all();

        return [
            'auto_increment' => $autoIncrement,
            'auto_increment_columns' => $autoIncrementColumns,
            'create_fingerprints' => $createFingerprints,
            'engines' => $engines,
            'foreign_keys' => $foreignKeys,
            'migrations' => $migrations,
            'primary_keys' => $primaryKeys,
            'triggers' => $triggers,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function captureDataSnapshot(Connection $connection, bool $lockRows): array
    {
        $userIds = array_keys(self::USER_EXPECTATIONS);
        $asramaIds = array_keys(self::ASRAMA_EXPECTATIONS);
        $targetEmails = array_column(self::USER_EXPECTATIONS, 1);

        $usersQuery = $connection->table('users')
            ->where('id', 13)
            ->orWhereIn('id', $userIds)
            ->select(['id', 'name', 'email', 'role', 'status_aktif', 'is_primary_admin', 'asrama_id'])
            ->orderBy('id');
        $asramasQuery = $connection->table('asrama')
            ->whereIn('id', $asramaIds)
            ->select(['id', 'nama_asrama', 'kode_asrama', 'status_aktif'])
            ->orderBy('id');
        $childrenQuery = $connection->table('anak_asuh')
            ->where('id', 1)
            ->orWhereIn('asrama_id', $asramaIds)
            ->select([
                'id', 'asrama_id', 'nomor_induk', 'nama_lengkap', 'jenis_kelamin',
                'jenjang_pendidikan', 'status_aktif',
            ])
            ->orderBy('id');
        $activitiesQuery = $connection->table('kegiatan_harian')
            ->where('id', 1)
            ->orWhereIn('asrama_id', $asramaIds)
            ->orWhereIn('penginput_id', $userIds)
            ->select([
                'id', 'asrama_id', 'penginput_id', 'tanggal_kegiatan',
                'kategori', 'nama_kegiatan', 'jam_mulai', 'jam_selesai', 'jumlah_peserta',
            ])
            ->orderBy('id');
        $evaluationsQuery = $connection->table('evaluasi_diniyah')
            ->whereIn('asrama_id', $asramaIds)
            ->orWhere('anak_asuh_id', 1)
            ->orWhereIn('diajukan_oleh', $userIds)
            ->orWhereIn('dinilai_oleh', $userIds)
            ->select(['id', 'anak_asuh_id', 'asrama_id', 'diajukan_oleh', 'dinilai_oleh', 'status'])
            ->orderBy('id');
        $assignedUsersQuery = $connection->table('users')
            ->whereIn('asrama_id', $asramaIds)
            ->select(['id', 'asrama_id'])
            ->orderBy('id');
        $sessionsQuery = $connection->table('sessions')
            ->whereIn('user_id', $userIds)
            ->orderBy('user_id')
            ->select('user_id');
        $resetTokensQuery = $connection->table('password_reset_tokens')
            ->whereIn('email', $targetEmails)
            ->orderBy('email')
            ->select('email');
        $counterQuery = $connection->table('asrama_code_counters')
            ->orderBy('id');

        foreach ([
            $usersQuery,
            $asramasQuery,
            $childrenQuery,
            $activitiesQuery,
            $evaluationsQuery,
            $assignedUsersQuery,
            $sessionsQuery,
            $resetTokensQuery,
            $counterQuery,
        ] as $query) {
            if ($lockRows) {
                $query->lockForUpdate();
            }
        }

        return [
            'users' => $this->snapshotRows($usersQuery),
            'asramas' => $this->snapshotRows($asramasQuery),
            'children' => $this->snapshotRows($childrenQuery),
            'activities' => $this->snapshotRows($activitiesQuery),
            'evaluations' => $this->snapshotRows($evaluationsQuery->select([
                'id', 'anak_asuh_id', 'asrama_id', 'diajukan_oleh', 'dinilai_oleh', 'status',
            ])),
            'assigned_users' => $this->snapshotRows($assignedUsersQuery->select(['id', 'asrama_id'])),
            'sessions' => $this->snapshotRows($sessionsQuery),
            'password_reset_tokens' => $this->snapshotRows($resetTokensQuery),
            'counter' => $this->snapshotRows($counterQuery),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function snapshotRows(Builder $query): array
    {
        return $query->get()
            ->map(static fn (object $row): array => get_object_vars($row))
            ->all();
    }

    private function assertMetadataUnchanged(Connection $connection, array $baseline): void
    {
        $current = $this->captureMetadata($connection);
        $expectedEngines = array_fill_keys(self::TABLES, 'InnoDB');
        ksort($expectedEngines);

        $this->assert(
            $current['engines'] === $expectedEngines,
            'Tabel yang diperiksa tidak seluruhnya menggunakan InnoDB.'
        );
        $expectedForeignKeys = self::EXPECTED_FOREIGN_KEYS;
        sort($expectedForeignKeys);
        $this->assert(
            $current['foreign_keys'] === $expectedForeignKeys,
            'Foreign key atau aturan ON DELETE/ON UPDATE berubah dari hasil audit.'
        );
        $this->assert($current['triggers'] === [], 'Trigger ditemukan pada tabel yang akan diperiksa.');
        $this->assert(
            $current['primary_keys'] === $this->expectedPrimaryKeys(),
            'Struktur primary key tidak sesuai.'
        );
        $this->assert(
            $current['auto_increment_columns'] === [
                'anak_asuh|id|bigint unsigned|auto_increment',
                'asrama|id|bigint unsigned|auto_increment',
                'kegiatan_harian|id|bigint unsigned|auto_increment',
                'users|id|bigint unsigned|auto_increment',
            ],
            'Definisi kolom AUTO_INCREMENT tidak sesuai.'
        );
        $this->assert(
            $current['migrations'] === ['2026_10_21_071850_add_primary_admin_marker_to_users_table|11'],
            'Status migration perlindungan akun utama berubah dari hasil audit.'
        );
        $this->assert(
            $current['create_fingerprints'] === $baseline['create_fingerprints'],
            'Struktur tabel berubah sejak pencatatan baseline.'
        );
    }

    /**
     * @return list<string>
     */
    private function expectedPrimaryKeys(): array
    {
        return [
            'anak_asuh|1|id|0',
            'asrama|1|id|0',
            'kegiatan_harian|1|id|0',
            'users|1|id|0',
        ];
    }

    private function validateTargets(Connection $connection, bool $lockRows): void
    {
        $userIds = array_keys(self::USER_EXPECTATIONS);
        $asramaIds = array_keys(self::ASRAMA_EXPECTATIONS);

        $adminQuery = $connection->table('users')->where('id', 13);
        if ($lockRows) {
            $adminQuery->lockForUpdate();
        }
        $admin = $adminQuery->first(['id', 'name', 'email', 'role', 'status_aktif', 'is_primary_admin', 'asrama_id']);

        $this->assert(
            $admin !== null
            && $admin->name === 'siti hasanah'
            && $admin->email === 'Adminpusat1@gmail.com'
            && $admin->role === 'admin_pusat'
            && (int) $admin->status_aktif === 1
            && (int) $admin->is_primary_admin === 1
            && $admin->asrama_id === null,
            'Akun utama ID 13 tidak cocok; simulasi dihentikan.'
        );

        $primaryAdminsQuery = $connection->table('users')->where('is_primary_admin', true);
        if ($lockRows) {
            $primaryAdminsQuery->lockForUpdate();
        }
        $primaryAdminIds = $primaryAdminsQuery->orderBy('id')->pluck('id')->map(static fn ($id): int => (int) $id)->all();
        $this->assert($primaryAdminIds === [13], 'Penanda akun utama tidak hanya dimiliki akun ID 13.');

        $usersQuery = $connection->table('users')->whereIn('id', $userIds)->orderBy('id');
        if ($lockRows) {
            $usersQuery->lockForUpdate();
        }
        $users = $usersQuery->get(['id', 'name', 'email', 'role', 'status_aktif', 'is_primary_admin', 'asrama_id']);
        $this->assert($users->count() === count(self::USER_EXPECTATIONS), 'Jumlah user target berubah.');

        foreach ($users as $user) {
            $expected = self::USER_EXPECTATIONS[(int) $user->id] ?? null;
            $this->assert(
                $expected !== null
                && $user->name === $expected[0]
                && $user->email === $expected[1]
                && $user->role === $expected[2]
                && (int) $user->status_aktif === $expected[3]
                && $user->is_primary_admin === null
                && ($user->asrama_id === null ? null : (int) $user->asrama_id) === $expected[4],
                "Identitas user target {$user->id} berubah."
            );
        }

        $asramasQuery = $connection->table('asrama')->whereIn('id', $asramaIds)->orderBy('id');
        if ($lockRows) {
            $asramasQuery->lockForUpdate();
        }
        $asramas = $asramasQuery->get(['id', 'nama_asrama', 'kode_asrama', 'status_aktif']);
        $this->assert($asramas->count() === count(self::ASRAMA_EXPECTATIONS), 'Jumlah asrama target berubah.');

        foreach ($asramas as $asrama) {
            $expected = self::ASRAMA_EXPECTATIONS[(int) $asrama->id] ?? null;
            $this->assert(
                $expected !== null
                && $asrama->nama_asrama === $expected[0]
                && $asrama->kode_asrama === $expected[1]
                && (int) $asrama->status_aktif === 1,
                "Identitas asrama target {$asrama->id} berubah."
            );
        }

        $childrenQuery = $connection->table('anak_asuh')
            ->where('id', 1)
            ->orWhereIn('asrama_id', $asramaIds)
            ->orderBy('id');
        if ($lockRows) {
            $childrenQuery->lockForUpdate();
        }
        $children = $childrenQuery->get([
            'id', 'asrama_id', 'nomor_induk', 'nama_lengkap', 'jenis_kelamin',
            'jenjang_pendidikan', 'status_aktif',
        ]);
        $this->assert(
            $children->count() === 1
            && (int) $children[0]->id === 1
            && (int) $children[0]->asrama_id === 1
            && $children[0]->nomor_induk === 'AA-2026-001'
            && $children[0]->nama_lengkap === 'albagir mustofa-update'
            && $children[0]->jenis_kelamin === 'L'
            && $children[0]->jenjang_pendidikan === 'SD'
            && (int) $children[0]->status_aktif === 1,
            'Data anak asuh target berubah atau ada anak asuh tambahan pada asrama target.'
        );

        $activitiesQuery = $connection->table('kegiatan_harian')
            ->where('id', 1)
            ->orWhereIn('asrama_id', $asramaIds)
            ->orWhereIn('penginput_id', $userIds)
            ->orderBy('id');
        if ($lockRows) {
            $activitiesQuery->lockForUpdate();
        }
        $activities = $activitiesQuery->get([
            'id', 'asrama_id', 'penginput_id', 'tanggal_kegiatan',
            'kategori', 'nama_kegiatan', 'jam_mulai', 'jam_selesai', 'jumlah_peserta',
        ]);
        $this->assert(
            $activities->count() === 1
            && (int) $activities[0]->id === 1
            && (int) $activities[0]->asrama_id === 1
            && (int) $activities[0]->penginput_id === 13
            && (string) $activities[0]->tanggal_kegiatan === '2026-10-21'
            && $activities[0]->kategori === 'belajar'
            && $activities[0]->nama_kegiatan === 'Belajar fikih wudhu'
            && (string) $activities[0]->jam_mulai === '00:30:00'
            && (string) $activities[0]->jam_selesai === '13:20:00'
            && (int) $activities[0]->jumlah_peserta === 5,
            'Kegiatan target berubah atau ada kegiatan tambahan yang terkena relasi target.'
        );

        $evaluationsQuery = $connection->table('evaluasi_diniyah')
            ->whereIn('asrama_id', $asramaIds)
            ->orWhere('anak_asuh_id', 1)
            ->orWhereIn('diajukan_oleh', $userIds)
            ->orWhereIn('dinilai_oleh', $userIds);
        if ($lockRows) {
            $evaluationsQuery->lockForUpdate();
        }
        $this->assert($evaluationsQuery->get(['id'])->isEmpty(), 'Evaluasi diniyah terkait ditemukan.');

        $assignedUsersQuery = $connection->table('users')
            ->whereIn('asrama_id', $asramaIds)
            ->orderBy('id');
        if ($lockRows) {
            $assignedUsersQuery->lockForUpdate();
        }
        $assignedUserIds = $assignedUsersQuery->pluck('id')->map(static fn ($id): int => (int) $id)->all();
        sort($assignedUserIds);
        $this->assert($assignedUserIds === [59, 60, 71, 72, 80, 81], 'Relasi user-asrama berubah.');

        $sessionsQuery = $connection->table('sessions')->whereIn('user_id', $userIds);
        if ($lockRows) {
            $sessionsQuery->lockForUpdate();
        }
        $this->assert($sessionsQuery->get(['id'])->isEmpty(), 'Session yang merujuk user target ditemukan.');

        $targetEmails = array_column(self::USER_EXPECTATIONS, 1);
        $resetTokensQuery = $connection->table('password_reset_tokens')->whereIn('email', $targetEmails);
        if ($lockRows) {
            $resetTokensQuery->lockForUpdate();
        }
        $this->assert($resetTokensQuery->get(['email'])->isEmpty(), 'Password reset token untuk user target ditemukan.');

        $counterQuery = $connection->table('asrama_code_counters')->orderBy('id');
        if ($lockRows) {
            $counterQuery->lockForUpdate();
        }
        $counter = $counterQuery->get(['id', 'last_number']);
        $this->assert(
            $counter->count() === 1
            && (int) $counter[0]->id === 1
            && (int) $counter[0]->last_number === 0,
            'Counter kode asrama berubah dari baseline audit.'
        );
    }

    private function simulateDeletes(Connection $connection): void
    {
        $deletedUsers = $connection->table('users')->whereIn('id', array_keys(self::USER_EXPECTATIONS))->delete();
        $this->assert($deletedUsers === count(self::USER_EXPECTATIONS), 'Jumlah user yang terhapus sementara tidak sesuai.');

        $deletedActivities = $connection->table('kegiatan_harian')->where('id', 1)->delete();
        $this->assert($deletedActivities === 1, 'Kegiatan ID 1 tidak terhapus sementara.');

        $deletedChildren = $connection->table('anak_asuh')->where('id', 1)->delete();
        $this->assert($deletedChildren === 1, 'Anak asuh ID 1 tidak terhapus sementara.');

        $deletedAsramas = $connection->table('asrama')->whereIn('id', array_keys(self::ASRAMA_EXPECTATIONS))->delete();
        $this->assert($deletedAsramas === count(self::ASRAMA_EXPECTATIONS), 'Jumlah asrama yang terhapus sementara tidak sesuai.');

        $this->assert(
            $connection->table('users')->whereIn('id', array_keys(self::USER_EXPECTATIONS))->count() === 0,
            'User target masih ada di dalam transaksi simulasi.'
        );
        $this->assert(
            $connection->table('kegiatan_harian')->where('id', 1)->count() === 0,
            'Kegiatan target masih ada di dalam transaksi simulasi.'
        );
        $this->assert(
            $connection->table('anak_asuh')->where('id', 1)->count() === 0,
            'Anak asuh target masih ada di dalam transaksi simulasi.'
        );
        $this->assert(
            $connection->table('asrama')->whereIn('id', array_keys(self::ASRAMA_EXPECTATIONS))->count() === 0,
            'Asrama target masih ada di dalam transaksi simulasi.'
        );

        $admin = $connection->table('users')->where('id', 13)->first([
            'id', 'name', 'email', 'role', 'status_aktif', 'is_primary_admin', 'asrama_id',
        ]);
        $this->assert(
            $admin !== null
            && $admin->name === 'siti hasanah'
            && $admin->email === 'Adminpusat1@gmail.com'
            && $admin->role === 'admin_pusat'
            && (int) $admin->status_aktif === 1
            && (int) $admin->is_primary_admin === 1
            && $admin->asrama_id === null,
            'Akun utama berubah di dalam transaksi simulasi.'
        );

        $this->assert(
            $connection->table('evaluasi_diniyah')
                ->whereIn('asrama_id', array_keys(self::ASRAMA_EXPECTATIONS))
                ->orWhere('anak_asuh_id', 1)
                ->orWhereIn('diajukan_oleh', array_keys(self::USER_EXPECTATIONS))
                ->orWhereIn('dinilai_oleh', array_keys(self::USER_EXPECTATIONS))
                ->count() === 0,
            'Data evaluasi di luar target terkena dampak simulasi.'
        );
    }

    private function assertStableMetadata(array $baseline, array $afterRollback): void
    {
        foreach (['auto_increment_columns', 'create_fingerprints', 'engines', 'foreign_keys', 'migrations', 'primary_keys', 'triggers'] as $key) {
            $this->assert($baseline[$key] === $afterRollback[$key], "Metadata [{$key}] berubah selama simulasi.");
        }
    }

    /**
     * @param  array<string, array<string, int|null>>  $metadata
     */
    private function reportAutoIncrementMetadata(array $metadata, string $phase): void
    {
        foreach ($metadata as $table => $sources) {
            $values = array_unique(array_values($sources), SORT_REGULAR);
            if (count($values) > 1) {
                $this->warn(sprintf(
                    'AUTO_INCREMENT metadata differs %s for [%s]: %s',
                    $phase,
                    $table,
                    json_encode($sources, JSON_THROW_ON_ERROR),
                ));
            } else {
                $this->line(sprintf(
                    'AUTO_INCREMENT %s [%s]: %s',
                    $phase,
                    $table,
                    json_encode($sources, JSON_THROW_ON_ERROR),
                ));
            }
        }
    }

    /**
     * @param  array<string, array<string, int|null>>  $baseline
     * @param  array<string, array<string, int|null>>  $afterRollback
     */
    private function reportAutoIncrementChanges(array $baseline, array $afterRollback): void
    {
        foreach ($baseline as $table => $sources) {
            if ($sources !== ($afterRollback[$table] ?? null)) {
                $this->warn(sprintf(
                    'AUTO_INCREMENT metadata for [%s] differs from baseline after rollback; this is reported separately and is not treated as proof of a transaction failure.',
                    $table,
                ));
            }
        }
    }

    private function assert(bool $condition, string $message): void
    {
        if (! $condition) {
            throw new RuntimeException($message);
        }
    }
}
