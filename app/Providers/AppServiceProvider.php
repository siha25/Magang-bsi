<?php

namespace App\Providers;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use LogicException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->environment('testing')) {
            return;
        }

        $this->guardTestingDatabaseConfiguration();

        Event::listen(ConnectionEstablished::class, function (ConnectionEstablished $event): void {
            $connection = $event->connection;

            if (
                in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)
                && strtolower((string) $connection->getDatabaseName()) === 'laravel'
            ) {
                throw new LogicException('Tests are not allowed to connect to the active MySQL database [laravel].');
            }
        });

        Event::listen(MigrationStarted::class, function (MigrationStarted $event): void {
            if ($event->name !== '2026_10_21_071850_add_primary_admin_marker_to_users_table') {
                return;
            }

            DB::table('users')->insertOrIgnore([
                'name' => 'Siti Hasanah',
                'email' => 'Adminpusat1@gmail.com',
                'password' => Hash::make('isolated-testing-password'),
                'role' => 'admin_pusat',
                'asrama_id' => null,
                'status_aktif' => true,
            ]);
        });
    }

    private function guardTestingDatabaseConfiguration(): void
    {
        $connections = config('database.connections', []);
        $defaultConnection = config('database.default');

        foreach ($connections as $name => $connection) {
            $url = (string) ($connection['url'] ?? '');
            $urlParts = $url !== '' ? parse_url($url) : false;
            $urlDriver = is_array($urlParts) ? strtolower((string) ($urlParts['scheme'] ?? '')) : '';
            $urlDatabase = is_array($urlParts) ? ltrim((string) ($urlParts['path'] ?? ''), '/') : '';
            $driver = strtolower((string) ($connection['driver'] ?? ''));
            $database = $urlDatabase !== '' ? $urlDatabase : (string) ($connection['database'] ?? '');

            if (
                (in_array($driver, ['mysql', 'mariadb'], true) || in_array($urlDriver, ['mysql', 'mariadb'], true))
                && strtolower($database) === 'laravel'
            ) {
                throw new LogicException("Testing connection [{$name}] targets the active MySQL database [laravel].");
            }
        }

        $default = $connections[$defaultConnection] ?? [];

        if (
            ($default['driver'] ?? null) === 'mysql'
            && strtolower((string) ($default['database'] ?? '')) === 'laravel'
        ) {
            throw new LogicException('Tests must use an isolated database, not the active MySQL database [laravel].');
        }
    }
}
