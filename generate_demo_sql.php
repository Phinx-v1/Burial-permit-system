<?php

// A standalone PHP script to generate SQLite compatible SQL.

$adminId = 2;
$superAdminId = 1;

$firstNames = ['Maria', 'Juan', 'Jose', 'Elena', 'Ricardo', 'Gloria', 'Ramon', 'Teresa', 'Antonio', 'Carmen', 'Lucia', 'Francisco', 'Dolores', 'Pedro', 'Anita', 'Angel', 'Rosa', 'Manuel', 'Estela', 'Roberto', 'Felicidad', 'Nestor', 'Cynthia', 'Rodrigo', 'Maricel'];
$lastNames = ['Garcia', 'Hernandez', 'Lopez', 'Ramos', 'Diaz', 'Cruz', 'Santos', 'Reyes', 'Morales', 'Bautista', 'Perez', 'Mendoza', 'Rivera', 'Aquino', 'Castro', 'Vargas', 'Salazar', 'Panganiban', 'Mercado', 'Bernardo'];
$types = ['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'];
$addresses = ['Poblacion, Carmen', 'Sto. Niño, Carmen', 'San Isidro, Carmen', 'Tagum City'];

$sql = "PRAGMA foreign_keys = OFF;\n";
$sql .= "DELETE FROM import_logs;\n";
$sql .= "DELETE FROM activity_logs;\n";
$sql .= "DELETE FROM burial_permits;\n";
$sql .= "DELETE FROM deceased_persons;\n";
$sql .= "PRAGMA foreign_keys = ON;\n\n";

$dodStartId = 1;
$permitStartId = 1;
$activityStartId = 1;
$importStartId = 1;
$seq = 1000;

function rnd($arr) {
    return $arr[array_rand($arr)];
}

$activePermitsForGrid = [];

function makePermit(&$sql, &$dodStartId, &$permitStartId, &$activityStartId, &$seq, &$activePermitsForGrid, $fn, $ln, $dod, $issued, $expiry, $status, $renewals, $adminId, $type) {
    $age = rand(45, 95);
    $sex = rand(0, 1) ? 'Male' : 'Female';
    $nationality = 'Filipino';
    $address = 'Carmen, Davao del Norte';
    $kindOfBurial = 'Tomb';

    $dodStr = $dod ? "'{$dod}'" : "NULL";
    
    $sql .= "INSERT INTO deceased_persons (id, first_name, last_name, date_of_death, sex, age, nationality, address, kind_of_burial, created_at, updated_at) VALUES ";
    $sql .= "({$dodStartId}, '{$fn}', '{$ln}', {$dodStr}, '{$sex}', {$age}, '{$nationality}', '{$address}', '{$kindOfBurial}', '{$issued}', '{$issued}');\n";

    $year = date('Y', strtotime($issued));
    $seq++;
    $permitNo = "BP-{$year}-" . str_pad($seq, 5, '0', STR_PAD_LEFT);
    $applicant = "Relative of {$fn}";
    
    $sql .= "INSERT INTO burial_permits (id, permit_number, deceased_id, permit_type, status, applicant_name, applicant_relationship, issued_date, expiry_date, processed_by, renewal_count, created_at, updated_at) VALUES ";
    $sql .= "({$permitStartId}, '{$permitNo}', {$dodStartId}, '{$type}', '{$status}', '{$applicant}', 'Child', '{$issued}', '{$expiry}', {$adminId}, {$renewals}, '{$issued}', '{$issued}');\n";

    // Create activity logs for permit
    $sql .= "INSERT INTO activity_logs (id, user_id, action, model_type, model_id, model_label, ip_address, description, created_at, updated_at) VALUES ";
    $sql .= "({$activityStartId}, {$adminId}, 'created', 'BurialPermit', {$permitStartId}, '{$permitNo}', '127.0.0.1', 'Created permit {$permitNo}', '{$issued}', '{$issued}');\n";
    $activityStartId++;

    if ($renewals > 0) {
        $renewalDate = date('Y-m-d H:i:s', strtotime($issued . " +5 years"));
        for($r=1; $r<=$renewals; $r++) {
            $prevExpiry = date('Y-m-d', strtotime($renewalDate . " -5 years"));
            $newExpiry = date('Y-m-d', strtotime($renewalDate . " +5 years"));
            
            $sql .= "INSERT INTO activity_logs (id, user_id, action, model_type, model_id, model_label, old_values, new_values, ip_address, description, created_at, updated_at) VALUES ";
            $sql .= "({$activityStartId}, {$adminId}, 'renewed', 'BurialPermit', {$permitStartId}, '{$permitNo}', '{\"expiry_date\":\"{$prevExpiry}\"}', '{\"expiry_date\":\"{$newExpiry}\"}', '127.0.0.1', 'Permit {$permitNo} renewed until {$newExpiry}', '{$renewalDate}', '{$renewalDate}');\n";
            $activityStartId++;
            
            $renewalDate = date('Y-m-d H:i:s', strtotime($renewalDate . " +5 years"));
        }
    }
    
    if ($status === 'active' && in_array($type, ['niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th'])) {
        $activePermitsForGrid[] = [
            'deceased_id' => $dodStartId,
            'deceased_name' => "{$fn} {$ln}",
            'permit_number' => $permitNo,
            'permit_status' => $status
        ];
    }

    $dodStartId++;
    $permitStartId++;
}

// 1. HISTORICAL ACTIVE PERMITS
for ($month = 24; $month >= 1; $month--) {
    $count = rand(8, 15);
    for ($i = 0; $i < $count; $i++) {
        $issued = date('Y-m-d H:i:s', strtotime("-{$month} months +" . rand(0, 27) . " days"));
        $dod = date('Y-m-d H:i:s', strtotime($issued . " -" . rand(2, 10) . " days"));
        $expiry = date('Y-m-d H:i:s', strtotime($issued . " +5 years"));
        
        makePermit($sql, $dodStartId, $permitStartId, $activityStartId, $seq, $activePermitsForGrid, rnd($firstNames), rnd($lastNames), $dod, $issued, $expiry, 'active', 0, $adminId, rnd($types));
    }
}

// 2. EXPIRED PERMITS
for ($i = 0; $i < 30; $i++) {
    $issued = date('Y-m-d H:i:s', strtotime("-" . rand(6, 12) . " years -" . rand(0, 300) . " days"));
    $dod = date('Y-m-d H:i:s', strtotime($issued . " -" . rand(3, 15) . " days"));
    $expiry = date('Y-m-d H:i:s', strtotime("-" . rand(1, 18) . " months"));
    
    makePermit($sql, $dodStartId, $permitStartId, $activityStartId, $seq, $activePermitsForGrid, rnd($firstNames), rnd($lastNames), $dod, $issued, $expiry, 'expired', rand(1, 3), $adminId, rnd($types));
}

// 3. EXPIRING SOON
for ($i = 0; $i < 20; $i++) {
    $issued = date('Y-m-d H:i:s', strtotime("-5 years -" . rand(0, 60) . " days"));
    $dod = date('Y-m-d H:i:s', strtotime($issued . " -" . rand(2, 10) . " days"));
    $expiry = date('Y-m-d H:i:s', strtotime("+" . rand(1, 29) . " days"));
    
    makePermit($sql, $dodStartId, $permitStartId, $activityStartId, $seq, $activePermitsForGrid, rnd($firstNames), rnd($lastNames), $dod, $issued, $expiry, 'expiring', rand(0, 2), $adminId, rnd($types));
}

// 4. DATA QUALITY ISSUES
for ($d = 0; $d < 3; $d++) {
    $dod = date('Y-m-d H:i:s', strtotime("-2 years -" . ($d * 10) . " days"));
    $issued = date('Y-m-d H:i:s', strtotime("-2 years"));
    $expiry = date('Y-m-d H:i:s', strtotime("+3 years"));
    
    makePermit($sql, $dodStartId, $permitStartId, $activityStartId, $seq, $activePermitsForGrid, 'Maria', 'Santos', $dod, $issued, $expiry, 'active', 0, $adminId, 'cemented');
}

$dupNo = 'BP-2025-DUP99';
$issued = date('Y-m-d H:i:s', strtotime("-1 year"));
$expiry = date('Y-m-d H:i:s', strtotime("+4 years"));
$dod = date('Y-m-d H:i:s', strtotime($issued . " -5 days"));

$sql .= "INSERT INTO deceased_persons (id, first_name, last_name, date_of_death, sex, age, nationality, address, kind_of_burial, created_at, updated_at) VALUES ";
$sql .= "({$dodStartId}, 'Rosa', 'Reyes', '{$dod}', 'Female', 65, 'Filipino', 'Carmen', 'Niche', '{$issued}', '{$issued}');\n";

$sql .= "INSERT OR IGNORE INTO burial_permits (id, permit_number, deceased_id, permit_type, status, applicant_name, applicant_relationship, issued_date, expiry_date, processed_by, renewal_count, created_at, updated_at) VALUES ";
$sql .= "({$permitStartId}, '{$dupNo}', {$dodStartId}, 'bone_niches', 'active', 'Family of Rosa', 'Sibling', '{$issued}', '{$expiry}', {$adminId}, 0, '{$issued}', '{$issued}');\n";
$dodStartId++;
$permitStartId++;

$dod = date('Y-m-d H:i:s', strtotime($issued . " -10 days"));
$sql .= "INSERT INTO deceased_persons (id, first_name, last_name, date_of_death, sex, age, nationality, address, kind_of_burial, created_at, updated_at) VALUES ";
$sql .= "({$dodStartId}, 'Luz', 'Reyes', '{$dod}', 'Female', 66, 'Filipino', 'Carmen', 'Niche', '{$issued}', '{$issued}');\n";

$sql .= "INSERT OR IGNORE INTO burial_permits (id, permit_number, deceased_id, permit_type, status, applicant_name, applicant_relationship, issued_date, expiry_date, processed_by, renewal_count, created_at, updated_at) VALUES ";
$sql .= "({$permitStartId}, '{$dupNo}', {$dodStartId}, 'bone_niches', 'active', 'Family of Luz', 'Sibling', '{$issued}', '{$expiry}', {$adminId}, 0, '{$issued}', '{$expiry}');\n";
$dodStartId++;
$permitStartId++;

$sql .= "INSERT INTO burial_permits (id, permit_number, deceased_id, permit_type, status, applicant_name, applicant_relationship, issued_date, expiry_date, processed_by, renewal_count, remarks, created_at, updated_at) VALUES ";
$sql .= "({$permitStartId}, 'BP-ORPHAN-001', NULL, 'cemented', 'active', 'Unknown Applicant', 'Unknown', '" . date('Y-m-d H:i:s', strtotime("-3 months")) . "', '" . date('Y-m-d H:i:s', strtotime("+5 years")) . "', {$adminId}, 0, 'DATA ISSUE: Missing deceased record', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "');\n";
$permitStartId++;

$issued = date('Y-m-d H:i:s', strtotime("-1 year"));
$expiry = date('Y-m-d H:i:s', strtotime("+4 years"));
makePermit($sql, $dodStartId, $permitStartId, $activityStartId, $seq, $activePermitsForGrid, 'Pedro', 'NoDeath', null, $issued, $expiry, 'active', 0, $adminId, 'cemented');

// 5. IMPORT LOGS
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
            'reason' => rnd([
                'Duplicate permit number',
                'Missing last name',
                'Invalid date of death',
                'Blank applicant name',
                'Unknown permit type',
            ]),
        ];
    }
    
    $date = date('Y-m-d H:i:s', strtotime("-{$log['month']} months"));
    $detailsJson = json_encode($skippedDetails);
    
    $sql .= "INSERT INTO import_logs (id, file_name, total_rows, imported, skipped, skip_reasons, uploaded_by, created_at, updated_at) VALUES ";
    $sql .= "({$importStartId}, '{$log['file']}', {$total}, {$imported}, {$skipped}, '{$detailsJson}', {$adminId}, '{$date}', '{$date}');\n";
    
    $importStartId++;
}

// 6. UPDATE NICHE GRIDS
shuffle($activePermitsForGrid);
$ptr = 0;

// Update Grid 1 (Niche Block A - 30 cols)
$rows1 = 4;
$cols1 = 30;
$cells1 = [];
for ($r=1; $r<=$rows1; $r++) {
    for ($c=1; $c<=$cols1; $c++) {
        if (rand(0,2) !== 0 && isset($activePermitsForGrid[$ptr])) {
            $p = $activePermitsForGrid[$ptr];
            $cells1["{$r}-{$c}"] = [
                'status' => 'occupied',
                'deceased_id' => $p['deceased_id'],
                'deceased_name' => $p['deceased_name'],
                'permit_number' => $p['permit_number'],
                'permit_status' => $p['permit_status']
            ];
            $ptr++;
        }
    }
}
$cellsJson1 = json_encode($cells1);
$sql .= "UPDATE niche_grids SET cells = '{$cellsJson1}' WHERE id = 1;\n";

// Update Grid 2 (Niche Block A - 40 cols)
$rows2 = 4;
$cols2 = 40;
$cells2 = [];
for ($r=1; $r<=$rows2; $r++) {
    for ($c=1; $c<=$cols2; $c++) {
        if (rand(0,2) !== 0 && isset($activePermitsForGrid[$ptr])) {
            $p = $activePermitsForGrid[$ptr];
            $cells2["{$r}-{$c}"] = [
                'status' => 'occupied',
                'deceased_id' => $p['deceased_id'],
                'deceased_name' => $p['deceased_name'],
                'permit_number' => $p['permit_number'],
                'permit_status' => $p['permit_status']
            ];
            $ptr++;
        }
    }
}
$cellsJson2 = json_encode($cells2);
$sql .= "UPDATE niche_grids SET cells = '{$cellsJson2}' WHERE id = 2;\n";

file_put_contents('demo_data.sql', $sql);
echo "SQL generated successfully as demo_data.sql\n";
