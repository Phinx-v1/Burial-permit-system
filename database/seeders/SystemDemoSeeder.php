<?php

namespace Database\Seeders;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemDemoSeeder extends Seeder
{
    // ── Filipino names pool ───────────────────────────────────────────────────
    private array $firstNames = [
        'Maria', 'Juan', 'Jose', 'Elena', 'Ricardo', 'Gloria', 'Ramon',
        'Teresa', 'Antonio', 'Carmen', 'Lucia', 'Francisco', 'Dolores',
        'Pedro', 'Anita', 'Angel', 'Rosa', 'Manuel', 'Estela', 'Roberto',
        'Felicidad', 'Nestor', 'Cynthia', 'Rodrigo', 'Maricel', 'Ernesto',
        'Ligaya', 'Renato', 'Lourdes', 'Alfredo',
    ];
    private array $lastNames = [
        'Garcia', 'Hernandez', 'Lopez', 'Ramos', 'Diaz', 'Cruz', 'Santos',
        'Reyes', 'Morales', 'Bautista', 'Perez', 'Mendoza', 'Rivera',
        'Aquino', 'Castro', 'Vargas', 'Salazar', 'Panganiban', 'Mercado',
        'Bernardo', 'Fernandez', 'Dela Cruz', 'Villanueva', 'Ocampo',
        'Soriano', 'Magno', 'Abad', 'Tolentino', 'Florendo', 'Castillo',
    ];
    private array $types     = ['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'];
    private array $addresses = [
        'Poblacion, Carmen, Davao del Norte',
        'Sto. Niño, Carmen, Davao del Norte',
        'San Isidro, Carmen, Davao del Norte',
        'New Corella, Davao del Norte',
        'Tagum City, Davao del Norte',
        'Asuncion, Davao del Norte',
    ];

    // ── Counter for unique permit numbers ─────────────────────────────────────
    private int $seq = 1000;

    public function run(): void
    {
        // ── 0. Wipe old data ─────────────────────────────────────────────────
        Schema::disableForeignKeyConstraints();
        DB::table('import_logs')->truncate();
        DB::table('activity_logs')->truncate();
        DB::table('burial_permits')->truncate();
        DB::table('deceased_persons')->truncate();
        // Keep niche_grids and users intact
        Schema::enableForeignKeyConstraints();

        $admin      = User::where('role', 'admin')->first()      ?? User::first();
        $superAdmin = User::where('role', 'super_admin')->first() ?? $admin;
        $adminId    = $admin?->id    ?? 1;
        $superAdminId = $superAdmin?->id ?? $adminId;

        // ── 1. HISTORICAL ACTIVE PERMITS (past 24 months) ────────────────────
        $this->command->info('Seeding historical active permits…');
        for ($month = 24; $month >= 1; $month--) {
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $issued = now()->subMonths($month)->startOfMonth()->addDays(rand(0, 27));
                $this->makePermit(
                    fn: $this->rnd($this->firstNames),
                    ln: $this->rnd($this->lastNames),
                    type: $this->rnd($this->types),
                    dod: $issued->copy()->subDays(rand(2, 10)),
                    issued: $issued,
                    expiry: $issued->copy()->addYears(5),
                    status: 'active',
                    adminId: $adminId,
                    renewals: 0,
                );
            }
        }

        // ── 2. EXPIRED PERMITS (with renewals) ───────────────────────────────
        $this->command->info('Seeding expired permits with renewal history…');
        for ($i = 0; $i < 25; $i++) {
            $issued  = now()->subYears(rand(6, 10))->subDays(rand(0, 300));
            $renewals = rand(1, 4);
            $this->makePermit(
                fn: $this->rnd($this->firstNames),
                ln: $this->rnd($this->lastNames),
                type: $this->rnd($this->types),
                dod: $issued->copy()->subDays(rand(3, 15)),
                issued: $issued,
                expiry: now()->subMonths(rand(1, 18)),
                status: 'expired',
                adminId: $adminId,
                renewals: $renewals,
            );
        }

        // ── 3. EXPIRING SOON (within 30 days) ────────────────────────────────
        $this->command->info('Seeding permits expiring soon…');
        for ($i = 0; $i < 15; $i++) {
            $issued = now()->subYears(5)->subDays(rand(0, 60));
            $this->makePermit(
                fn: $this->rnd($this->firstNames),
                ln: $this->rnd($this->lastNames),
                type: $this->rnd($this->types),
                dod: $issued->copy()->subDays(rand(2, 10)),
                issued: $issued,
                expiry: now()->addDays(rand(1, 29)),
                status: 'expiring',
                adminId: $adminId,
                renewals: rand(0, 3),
            );
        }

        // ── 4. NICHE BLOCK SLOTS — cells pre-filled with occupants ───────────
        $this->command->info('Seeding niche grid slot occupants…');
        $grid = DB::table('niche_grids')->first();
        if ($grid) {
            $existingCells = json_decode($grid->cells ?? '{}', true) ?: [];
            $rows = $grid->rows ?? 4;
            $cols = $grid->cols ?? 10;
            for ($r = 1; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    if (rand(0, 2) === 0) continue; // leave ~33% empty
                    $key = "R{$r}-C{$c}";
                    if (!isset($existingCells[$key])) {
                        // Create a permit for this cell
                        $issued  = now()->subMonths(rand(1, 48));
                        $permit  = $this->makePermit(
                            fn: $this->rnd($this->firstNames),
                            ln: $this->rnd($this->lastNames),
                            type: $r === 1 ? 'niche_1st' : ($r === 2 ? 'niche_2nd' : ($r === 3 ? 'niche_3rd' : 'niche_4th')),
                            dod: $issued->copy()->subDays(rand(2, 8)),
                            issued: $issued,
                            expiry: $issued->copy()->addYears(5),
                            status: 'active',
                            adminId: $adminId,
                            renewals: 0,
                            returnModel: true,
                        );
                        $existingCells[$key] = [
                            'status'       => 'occupied',
                            'permit_id'    => $permit->id,
                            'permit_no'    => $permit->permit_number,
                            'deceased'     => $permit->deceased?->full_name ?? 'Unknown',
                        ];
                    }
                }
            }
            DB::table('niche_grids')->where('id', $grid->id)->update([
                'cells' => json_encode($existingCells),
            ]);
            $this->command->info('Niche grid cells populated: ' . count($existingCells) . ' slots.');
        } else {
            $this->command->warn('No niche grid found — skipping cell seeding.');
        }

        // ── 5. IMPORT HISTORY (last 12 months) ───────────────────────────────
        $this->command->info('Seeding import logs…');
        $importFiles = [
            ['file' => 'burial_records_jan_2025.xlsx', 'month' => 16],
            ['file' => 'burial_records_feb_2025.xlsx', 'month' => 15],
            ['file' => 'burial_records_mar_2025.xlsx', 'month' => 14],
            ['file' => 'burial_records_apr_2025.xlsx', 'month' => 13],
            ['file' => 'burial_records_may_2025.xlsx', 'month' => 12],
            ['file' => 'burial_records_jun_2025.xlsx', 'month' => 11],
            ['file' => 'burial_records_jul_2025.xlsx', 'month' => 10],
            ['file' => 'burial_records_aug_2025.xlsx', 'month' => 9],
            ['file' => 'burial_records_sep_2025.xlsx', 'month' => 8],
            ['file' => 'burial_records_oct_2025.xlsx', 'month' => 7],
            ['file' => 'burial_records_nov_2025.xlsx', 'month' => 6],
            ['file' => 'burial_records_dec_2025.xlsx', 'month' => 5],
            ['file' => 'bulk_import_q1_2026.xlsx',     'month' => 3],
            ['file' => 'batch_correction_apr2026.xlsx','month' => 1],
        ];
        foreach ($importFiles as $log) {
            $total    = rand(15, 80);
            $imported = rand((int)($total * 0.7), $total);
            $skipped  = $total - $imported;
            $skippedDetails = [];
            for ($s = 0; $s < $skipped; $s++) {
                $skippedDetails[] = [
                    'row'    => rand(2, $total + 2),
                    'reason' => $this->rnd([
                        'Duplicate permit number',
                        'Missing last name',
                        'Invalid date of death',
                        'Blank applicant name',
                        'Unknown permit type',
                    ]),
                ];
            }
            DB::table('import_logs')->insert([
                'filename'        => $log['file'],
                'total_rows'      => $total,
                'imported'        => $imported,
                'skipped'         => $skipped,
                'skipped_details' => json_encode($skippedDetails),
                'imported_by'     => $adminId,
                'created_at'      => now()->subMonths($log['month']),
                'updated_at'      => now()->subMonths($log['month']),
            ]);
        }

        // ── 6. ACTIVITY / AUDIT LOGS ─────────────────────────────────────────
        $this->command->info('Seeding activity logs…');
        $permits = BurialPermit::limit(30)->get();
        $actions = ['created', 'updated', 'renewed', 'printed', 'deleted'];
        foreach ($permits as $permit) {
            $logCount = rand(1, 4);
            for ($l = 0; $l < $logCount; $l++) {
                $action = $this->rnd($actions);
                DB::table('activity_logs')->insert([
                    'user_id'     => rand(0, 1) ? $adminId : $superAdminId,
                    'action'      => $action,
                    'model_type'  => 'BurialPermit',
                    'model_id'    => $permit->id,
                    'model_label' => $permit->permit_number,
                    'description' => ucfirst($action) . ' permit ' . $permit->permit_number,
                    'ip_address'  => '127.0.0.1',
                    'old_values'  => null,
                    'new_values'  => null,
                    'created_at'  => now()->subDays(rand(0, 180)),
                    'updated_at'  => now()->subDays(rand(0, 180)),
                ]);
            }
        }

        // ── 7. DATA QUALITY ISSUES ────────────────────────────────────────────
        $this->command->info('Injecting data quality issues…');

        // 7a. Duplicate names (exact same full name, 3 records)
        for ($d = 0; $d < 3; $d++) {
            $dec = DeceasedPerson::create([
                'first_name'    => 'Maria',
                'last_name'     => 'Santos',
                'middle_name'   => 'Cruz',
                'date_of_death' => now()->subYears(2)->subDays($d * 10),
                'sex'           => 'Female',
                'age'           => 72,
                'nationality'   => 'Filipino',
                'address'       => $this->rnd($this->addresses),
            ]);
            BurialPermit::create([
                'permit_number'          => 'BP-DUP-NAME-' . str_pad($d + 1, 3, '0', STR_PAD_LEFT),
                'deceased_id'            => $dec->id,
                'permit_type'            => 'cemented',
                'status'                 => 'active',
                'issued_date'            => now()->subYears(2),
                'expiry_date'            => now()->addYears(3),
                'applicant_name'         => 'Relative of Maria',
                'applicant_relationship' => 'Child',
                'processed_by'           => $adminId,
            ]);
        }

        // 7b. Duplicate permit numbers
        $dupNo = 'BP-2025-DUP99';
        foreach (['Rosa', 'Luz'] as $fn) {
            $dec2 = DeceasedPerson::create([
                'first_name'    => $fn,
                'last_name'     => 'Reyes',
                'date_of_death' => now()->subYear(),
                'sex'           => 'Female',
                'age'           => 65,
                'nationality'   => 'Filipino',
            ]);
            try {
                BurialPermit::create([
                    'permit_number'          => $dupNo,
                    'deceased_id'            => $dec2->id,
                    'permit_type'            => 'bone_niches',
                    'status'                 => 'active',
                    'issued_date'            => now()->subYear(),
                    'expiry_date'            => now()->addYears(4),
                    'applicant_name'         => 'Family of ' . $fn,
                    'applicant_relationship' => 'Sibling',
                    'processed_by'           => $adminId,
                ]);
            } catch (\Exception) { /* ignore unique constraint on 2nd */ }
        }

        // 7c. Orphan permit — no deceased linked
        BurialPermit::create([
            'permit_number'          => 'BP-ORPHAN-001',
            'deceased_id'            => null,
            'permit_type'            => 'cemented',
            'status'                 => 'active',
            'issued_date'            => now()->subMonths(3),
            'expiry_date'            => now()->addYears(5),
            'applicant_name'         => 'Unknown Applicant',
            'applicant_relationship' => 'Unknown',
            'processed_by'           => $adminId,
            'remarks'                => 'DATA ISSUE: Missing deceased record',
        ]);

        // 7d. Missing date of death
        DeceasedPerson::create([
            'first_name'    => 'Pedro',
            'last_name'     => 'NoDeath',
            'date_of_death' => null,
            'sex'           => 'Male',
            'age'           => 78,
            'nationality'   => 'Filipino',
        ]);

        // 7e. Future date of death (impossible)
        DeceasedPerson::create([
            'first_name'    => 'Future',
            'last_name'     => 'Deceased',
            'date_of_death' => now()->addYears(3),
            'sex'           => 'Male',
            'age'           => 40,
            'nationality'   => 'Filipino',
        ]);

        // 7f. Invalid age (zero)
        DeceasedPerson::create([
            'first_name'    => 'Zero',
            'last_name'     => 'AgeRecord',
            'date_of_death' => now()->subMonths(6),
            'sex'           => 'Male',
            'age'           => 0,
            'nationality'   => 'Filipino',
        ]);

        // 7g. Multiple active permits for one deceased
        $multiDec = DeceasedPerson::create([
            'first_name'    => 'Carlos',
            'last_name'     => 'MultiPermit',
            'date_of_death' => now()->subYears(3),
            'sex'           => 'Male',
            'age'           => 55,
            'nationality'   => 'Filipino',
        ]);
        foreach (['cemented', 'niche_2nd'] as $idx => $ptype) {
            BurialPermit::create([
                'permit_number'          => 'BP-MULTI-' . ($idx + 1),
                'deceased_id'            => $multiDec->id,
                'permit_type'            => $ptype,
                'status'                 => 'active',
                'issued_date'            => now()->subYears(3),
                'expiry_date'            => now()->addYears(2),
                'applicant_name'         => 'Family of Carlos',
                'applicant_relationship' => 'Child',
                'processed_by'           => $adminId,
            ]);
        }

        // 7h. Deceased with no permit at all (unlinked record)
        for ($u = 0; $u < 5; $u++) {
            DeceasedPerson::create([
                'first_name'    => $this->rnd($this->firstNames),
                'last_name'     => 'Unpermitted',
                'date_of_death' => now()->subMonths(rand(3, 24)),
                'sex'           => rand(0, 1) ? 'Male' : 'Female',
                'age'           => rand(50, 90),
                'nationality'   => 'Filipino',
            ]);
        }

        // 7i. Invalid/corrupted permit type
        $badDec = DeceasedPerson::create([
            'first_name'    => 'Bad',
            'last_name'     => 'TypeRecord',
            'date_of_death' => now()->subYear(),
            'sex'           => 'Male',
            'age'           => 60,
        ]);
        $badPermit = BurialPermit::create([
            'permit_number'          => 'BP-BADTYPE-001',
            'deceased_id'            => $badDec->id,
            'permit_type'            => 'cemented',
            'status'                 => 'active',
            'issued_date'            => now()->subYear(),
            'expiry_date'            => now()->addYears(4),
            'applicant_name'         => 'Bad Type Applicant',
            'applicant_relationship' => 'Unknown',
            'processed_by'           => $adminId,
        ]);
        DB::table('burial_permits')->where('id', $badPermit->id)->update(['permit_type' => 'unknown_category_xyz']);

        $this->command->info('');
        $this->command->info('✅  SystemDemoSeeder complete!');
        $this->command->info('   Active (historical) : ~' . (24 * 4) . ' permits');
        $this->command->info('   Expired + renewed   : 25 permits');
        $this->command->info('   Expiring soon       : 15 permits');
        $this->command->info('   Import logs         : ' . count($importFiles) . ' entries');
        $this->command->info('   Data quality issues : 8 types injected');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function makePermit(
        string $fn, string $ln, string $type,
        Carbon $dod, Carbon $issued, Carbon $expiry,
        string $status, int $adminId, int $renewals,
        bool $returnModel = false,
    ): BurialPermit|null {
        $deceased = DeceasedPerson::create([
            'first_name'    => $fn,
            'last_name'     => $ln,
            'date_of_death' => $dod,
            'nationality'   => 'Filipino',
            'sex'           => rand(0, 1) ? 'Male' : 'Female',
            'age'           => rand(45, 95),
            'kind_of_burial'=> $this->rnd(['Tomb', 'Niche', 'Bone Niche']),
            'address'       => $this->rnd($this->addresses),
        ]);

        $year    = $issued->year;
        $this->seq++;
        $permitNo = "BP-{$year}-" . str_pad($this->seq, 5, '0', STR_PAD_LEFT);

        $permit = BurialPermit::create([
            'permit_number'          => $permitNo,
            'deceased_id'            => $deceased->id,
            'permit_type'            => $type,
            'issued_date'            => $issued,
            'expiry_date'            => $expiry,
            'status'                 => $status,
            'applicant_name'         => 'Relative of ' . $fn,
            'applicant_relationship' => $this->rnd(['Child', 'Spouse', 'Sibling', 'Parent', 'Relative']),
            'processed_by'           => $adminId,
            'renewal_count'          => $renewals,
            'created_at'             => $issued,
            'updated_at'             => $issued,
        ]);

        return $returnModel ? $permit : null;
    }

    private function rnd(array $arr): mixed
    {
        return $arr[array_rand($arr)];
    }
}
