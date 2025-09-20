<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolePermissionSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions for all tests
        $this->seed(RolePermissionSeeder::class);
        
        // Disable CSRF middleware for tests but keep auth middleware
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
        
        // Start session for tests
        $this->startSession();
    }
}
