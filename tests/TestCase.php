<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Safety net: tests refresh the database, so refuse to run against anything but a *_test database.
        $db = DB::connection()->getDatabaseName();
        if (! str_ends_with((string) $db, '_test')) {
            $this->fail("Refusing to run tests against database \"{$db}\". Use a *_test database.");
        }
    }
}
