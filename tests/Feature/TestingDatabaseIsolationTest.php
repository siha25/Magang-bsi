<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use LogicException;
use Tests\TestCase;

class TestingDatabaseIsolationTest extends TestCase
{
    public function test_connection_to_active_mysql_database_is_rejected(): void
    {
        config([
            'database.connections.mysql.driver' => 'mysql',
            'database.connections.mysql.url' => null,
            'database.connections.mysql.database' => 'laravel',
            'database.connections.mysql.host' => '127.0.0.1',
        ]);
        DB::purge('mysql');

        try {
            DB::connection('mysql');
            $this->fail('The active MySQL database connection should be rejected before connecting.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString('active MySQL database [laravel]', $exception->getMessage());
        } finally {
            config(['database.connections.mysql.database' => ':memory:']);
            DB::purge('mysql');
        }
    }
}
