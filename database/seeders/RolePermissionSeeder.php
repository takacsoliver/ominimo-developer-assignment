<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use OminimoBlog\Enums\Role as RoleEnum;
use OminimoBlog\Enums\Permission as PermissionEnum;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value]);
        $userRole = Role::firstOrCreate(['name' => RoleEnum::USER->value]);

        $adminRole->givePermissionTo(Permission::all());

        $userRole->givePermissionTo([
            PermissionEnum::VIEW_POSTS->value,
            PermissionEnum::CREATE_POSTS->value,
            PermissionEnum::VIEW_COMMENTS->value,
            PermissionEnum::CREATE_COMMENTS->value,
        ]);
    }
}