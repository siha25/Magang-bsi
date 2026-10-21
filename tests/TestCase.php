<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LogicException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (config('database.connections', []) as $name => $connection) {
            $url = (string) ($connection['url'] ?? '');
            $urlParts = $url !== '' ? parse_url($url) : false;
            $urlDriver = is_array($urlParts) ? strtolower((string) ($urlParts['scheme'] ?? '')) : '';
            $urlDatabase = is_array($urlParts) ? ltrim((string) ($urlParts['path'] ?? ''), '/') : '';
            $driver = strtolower((string) ($connection['driver'] ?? ''));
            $database = strtolower($urlDatabase !== '' ? $urlDatabase : (string) ($connection['database'] ?? ''));

            if (
                (in_array($driver, ['mysql', 'mariadb'], true) || in_array($urlDriver, ['mysql', 'mariadb'], true))
                && $database === 'laravel'
            ) {
                throw new LogicException("Tests cannot use database [laravel] through connection [{$name}].");
            }
        }
    }
}
