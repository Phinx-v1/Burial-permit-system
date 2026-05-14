<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create roles, permissions, and base users first
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Seed all demo data (permits, deceased, import logs, activity logs,
        //    niche grid slots, data quality issues)
        $this->call(SystemDemoSeeder::class);
    }
}
