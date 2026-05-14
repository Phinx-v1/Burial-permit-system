<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$grids = \App\Models\CemeteryGrid::all();
$permits = \App\Models\BurialPermit::with('deceased')->get();
$permitMap = $permits->shuffle()->toArray();
$permitIdx = 0;

foreach ($grids as $grid) {
    if (!$grid->rows || !$grid->cols) continue;

    $cells = [];
    
    // Fill the grid
    for ($r = 1; $r <= $grid->rows; $r++) {
        for ($c = 1; $c <= $grid->cols; $c++) {
            $key = "{$r}-{$c}";
            
            // Randomly decide if this cell is occupied 
            if ($permitIdx < count($permitMap) && rand(1, 100) <= 30) {
                $p = $permitMap[$permitIdx++];
                $cells[$key] = [
                    'status' => 'occupied',
                    'label' => $p['deceased']['first_name'] . ' ' . $p['deceased']['last_name'],
                    'deceased_id' => $p['deceased']['id'],
                    'deceased_name' => $p['deceased']['first_name'] . ' ' . $p['deceased']['last_name'],
                    'permit_number' => $p['permit_number'],
                    'permit_status' => $p['status']
                ];
            } else {
                $cells[$key] = ['status' => 'available'];
            }
        }
    }
    
    $grid->cells = $cells;
    $grid->save();
}

// Ensure Legacy Plots remain empty
\App\Models\CemeteryPlot::truncate();
echo "Re-synced cells with available status arrays successfully.";
