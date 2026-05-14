<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$grids = \App\Models\CemeteryGrid::all();
foreach ($grids as $grid) {
    if ($grid->cells) {
        echo $grid->name . " ->\n";
        echo mb_substr(json_encode($grid->cells), 0, 500) . "\n\n";
    }
}
