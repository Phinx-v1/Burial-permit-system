<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-dashboard',
            'manage-permits',
            'create-permit',
            'approve-permit',
            'release-permit',
            'manage-deceased',
            'manage-cemetery',
            'send-sms',
            'generate-reports',
            'import-excel',
            'upload-documents',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — dashboard only
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions([
            'view-dashboard',
            'manage-users',
            'generate-reports',
        ]);

        // Admin — full permit processing
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'view-dashboard',
            'manage-permits',
            'create-permit',
            'approve-permit',
            'release-permit',
            'manage-deceased',
            'manage-cemetery',
            'send-sms',
            'generate-reports',
            'import-excel',
            'upload-documents',
        ]);

        // Cemetery Manager — map and grid management
        $cemeteryManager = Role::firstOrCreate(['name' => 'cemetery_manager']);
        $cemeteryManager->syncPermissions([
            'view-dashboard',
            'manage-cemetery',
        ]);

        // Create Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@lgucarmen.gov.ph'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('superadmin123'),
                'role' => 'super_admin',
            ]
        );
        $superAdminUser->update(['role' => 'super_admin']);

        // Create Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@lgucarmen.gov.ph'],
            [
                'name' => 'MCR Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );
        $adminUser->update(['role' => 'admin']);

        $this->command->info('Roles and users created!');
        $this->command->info('Super Admin: superadmin@lgucarmen.gov.ph / superadmin123');
        $this->command->info('Admin:       admin@lgucarmen.gov.ph / admin123');
    }
}
