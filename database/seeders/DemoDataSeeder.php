<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $sql = file_get_contents(base_path('demo_data.sql'));
        DB::unprepared($sql);
        $this->command->info('SQL file executed successfully!');
    }
}
