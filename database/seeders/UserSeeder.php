<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Database\Seeders;

use OminimoBlog\Models\User;
use Illuminate\Database\Seeder;
use OminimoBlog\Enums\Role as RoleEnum;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('SuperSecret11'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole(RoleEnum::ADMIN->value);

        User::factory()
            ->count(10)
            ->create()
            ->each(function (User $user) {
                $user->assignRole(RoleEnum::USER->value);
            });
    }
}
