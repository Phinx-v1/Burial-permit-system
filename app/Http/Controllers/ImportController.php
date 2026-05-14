<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;



class ImportController extends Controller
{
    public function showImport()
    {
        $logs = $this->getLogs();

        return view('admin.import.index', compact('logs'));
    }

    public function index()
    {
        return $this->showImport();
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10485760'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());
        $tmpPath = $file->getRealPath();

        try {
            $rows = $ext === 'csv' ? $this->readCsv($tmpPath) : $this->readXlsx($tmpPath);
        } catch (\Throwable $e) {
            $this->saveLog($originalName, 0, 0, 0, ['Could not read file: '.$e->getMessage()]);

            return back()->with('import_error', 'Could not read file: '.$e->getMessage());
        }

        if (empty($rows)) {
            $this->saveLog($originalName, 0, 0, 0, ['File is empty']);

            return back()->with('import_error', 'The file appears to be empty.');
        }

        // Find header row
        $headerRowIdx = null;
        $headerMap = [];

        foreach (array_slice($rows, 0, 6, true) as $idx => $row) {
            foreach ((array) $row as $cell) {
                if ($this->norm($cell) === 'first_name') {
                    $headerRowIdx = $idx;
                    break 2;
                }
            }
        }

        if ($headerRowIdx === null) {
            $this->saveLog($originalName, 0, 0, 0, ['Header row not found']);

            return back()->with('import_error',
                'Header row not found. File must contain: first_name, last_name, date_of_death'
            );
        }

        foreach ((array) $rows[$headerRowIdx] as $col => $cell) {
            $k = $this->norm($cell);
            if ($k !== '') {
                $headerMap[$k] = $col;
            }
        }

        $dataRows = array_slice($rows, $headerRowIdx + 1, null, true);
        $imported = 0;
        $skipped = 0;
        $skipReasons = [];
        $year = now()->year;

        foreach ($dataRows as $rowIdx => $row) {
            $row = (array) $row;
            $display = $rowIdx + 1;

            if (empty(array_filter($row, fn ($v) => trim((string) $v) !== ''))) {
                continue;
            }

            $firstName = trim((string) $this->col($row, $headerMap, 'first_name', ''));
            $lastName = trim((string) $this->col($row, $headerMap, 'last_name', ''));
            $dateOfDeath = $this->parseDate($this->col($row, $headerMap, 'date_of_death'));

            if ($firstName === '' || $lastName === '') {
                $skipped++;
                $skipReasons[] = "Row {$display}: Missing first_name or last_name";

                continue;
            }
            if (! $dateOfDeath) {
                $skipped++;
                $skipReasons[] = "Row {$display}: Invalid date_of_death ({$firstName} {$lastName})";

                continue;
            }

            $validTypes = ['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'];
            $rawType = strtolower(trim((string) $this->col($row, $headerMap, 'permit_type', 'cemented')));
            $permitType = in_array($rawType, $validTypes) ? $rawType : 'cemented';

            $validStatuses = ['active', 'expiring', 'expired'];
            $rawStatus = strtolower(trim((string) $this->col($row, $headerMap, 'status', 'active')));
            $status = in_array($rawStatus, $validStatuses) ? $rawStatus : 'active';

            try {
                DB::transaction(function () use (
                    &$imported, $row, $headerMap,
                    $firstName, $lastName, $dateOfDeath,
                    $permitType, $status, $year
                ) {
                    $deceased = DeceasedPerson::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'date_of_death' => $dateOfDeath,
                        'nationality' => (string) ($this->col($row, $headerMap, 'nationality', 'Filipino') ?: 'Filipino'),
                        'age' => is_numeric($this->col($row, $headerMap, 'age')) ? (int) $this->col($row, $headerMap, 'age') : null,
                        'sex' => $this->col($row, $headerMap, 'sex') ?? null,
                        'kind_of_burial' => $this->col($row, $headerMap, 'kind_of_burial') ?? null,
                    ]);

                    $permitNo = $this->generateUniquePermitNumber($year);

                    BurialPermit::create([
    'permit_number'          => $permitNo,
    'deceased_id'            => $deceased->id,
    'permit_type'            => $permitType,
    'kind_of_burial'         => $this->col($row, $headerMap, 'kind_of_burial') ?? null,
    'applicant_name'         => (string) ($this->col($row, $headerMap, 'applicant_name', 'Unknown') ?: 'Unknown'),
    'applicant_relationship' => (string) ($this->col($row, $headerMap, 'applicant_relationship', '') ?? ''),
    'applicant_contact'      => (string) ($this->col($row, $headerMap, 'applicant_contact', '') ?? ''),
    'applicant_address'      => $this->col($row, $headerMap, 'applicant_address') ?? null,
    'status'                 => $status,
    'issued_date'            => $this->parseDate($this->col($row, $headerMap, 'issued_date')),
    'expiry_date'            => $this->parseDate($this->col($row, $headerMap, 'expiry_date')),
    'renewal_count'          => (int) ($this->col($row, $headerMap, 'renewal_count', 0) ?? 0),  // ← add this
    'remarks'                => (string) ($this->col($row, $headerMap, 'remarks', '') ?? ''),    // ← add this
    'processed_by'           => Auth::id(),
]);

                    $imported++;
                });
            } catch (\Throwable $e) {
                $skipped++;
                $skipReasons[] = "Row {$display} ({$firstName} {$lastName}): ".$e->getMessage();
            }
        }

        // Always save the log
        
        $this->saveLog($originalName, count($dataRows), $imported, $skipped, $skipReasons);

        if ($imported > 0) {
            ActivityLog::record(
                action: 'imported',
                modelType: 'BurialPermit',
                modelId: null,
                modelLabel: $originalName,
                newValues: ['imported' => $imported, 'skipped' => $skipped],
                description: "Imported {$imported} permit(s) from {$originalName}" . ($skipped > 0 ? " ({$skipped} skipped)" : '')
            );
        }

        return back()
            ->with('import_success', true)
            ->with('skip_reasons', $skipReasons)
            ->with('_import_imported', $imported)
            ->with('_import_skipped', $skipped);
    }

    // Returns JSON for the live-refresh fetch call
    public function historyJson()
    {
        try {
            if (! Schema::hasTable('import_logs')) {
                return response()->json(['total' => 0, 'rows' => []]);
            }

            $logs = ImportLog::with('user')->latest()->take(20)->get();

            return response()->json([
                'total' => ImportLog::count(),
                'rows' => $logs->map(fn ($l) => [
                    'id' => $l->id,
                    'file_name' => $l->file_name,
                    'uploaded_by' => optional($l->user)->name ?? 'Admin',
                    'date' => $l->created_at->format('M d, Y · g:i A'),
                    'total_rows' => $l->total_rows,
                    'imported' => $l->imported,
                    'skipped' => $l->skipped,
                    'skip_reasons' => is_array($l->skip_reasons)
                        ? $l->skip_reasons
                        : json_decode($l->skip_reasons ?? '[]', true),
                ]),
            ]);
        } catch (\Throwable $e) {
            Log::warning('historyJson error: '.$e->getMessage());

            return response()->json(['total' => 0, 'rows' => [], 'error' => $e->getMessage()]);
        }
    }

    private function generateUniquePermitNumber(int $year): string
    {
        for ($i = 0; $i < 100; $i++) {
            $count = BurialPermit::count() + $i + 1;
            $number = 'BP-'.$year.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);
            if (! BurialPermit::where('permit_number', $number)->exists()) {
                return $number;
            }
        }

        return 'BP-'.$year.'-'.substr((string) now()->valueOf(), -7);
    }

    private function getLogs()
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('import_logs')) {
                \Illuminate\Support\Facades\Log::warning("IMPORT_LOG_DEBUG: Table 'import_logs' DOES NOT EXIST.");
                return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
            }

            $count = \App\Models\ImportLog::count();
            \Illuminate\Support\Facades\Log::info("IMPORT_LOG_DEBUG: Table exists. Count: {$count}");

            return ImportLog::with('user')->latest()->paginate(5);
        } catch (\Throwable) {
            return new LengthAwarePaginator([], 0, 5);
        }
    }

    private function saveLog(string $name, int $total, int $imported, int $skipped, array $reasons): void
    {
        try {
            if (! Schema::hasTable('import_logs')) {
                Log::warning('import_logs table missing — run: php artisan migrate');

                return;
            }
            ImportLog::create([
                'file_name' => $name,
                'uploaded_by' => Auth::id(),
                'total_rows' => $total,
                'imported' => $imported,
                'skipped' => $skipped,
                'skip_reasons' => $reasons,
            ]);
        } catch (\Throwable $e) {
            Log::error('saveLog failed: '.$e->getMessage());
        }
    }

    private function readXlsx(string $path): array
    {
        if (! class_exists(IOFactory::class)) {
            throw new \RuntimeException('Run: composer require phpoffice/phpspreadsheet');
        }

        return array_values(
            IOFactory::load($path)
                ->getActiveSheet()
                ->toArray(null, true, true, false)
        );
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            while (($d = fgetcsv($h)) !== false) {
                $rows[] = array_values($d);
            }
            fclose($h);
        }

        return $rows;
    }

    private function norm(mixed $v): string
    {
        $s = strtolower(trim((string) $v));
        $s = preg_replace('/[\s\r\n\t]+/', '_', $s);
        $s = preg_replace('/[^a-z0-9_]/', '', $s);

        return trim($s, '_');
    }

    private function col(array $row, array $map, string $key, mixed $default = null): mixed
    {
        if (! isset($map[$key])) {
            return $default;
        }
        $v = $row[$map[$key]] ?? null;

        return ($v === null || trim((string) $v) === '') ? $default : $v;
    }

    private function parseDate(mixed $val): ?string
    {
        if ($val === null || trim((string) $val) === '') {
            return null;
        }
        if (is_numeric($val) && (float) $val > 1000) {
            try {
                if (class_exists(Date::class)) {
                    return Date::excelToDateTimeObject((float) $val)->format('Y-m-d');
                }

                return date('Y-m-d', (int) (((float) $val - 25569) * 86400));
            } catch (\Throwable) {
            }
        }
        $s = trim((string) $val);
        foreach (['Y-m-d', 'Y/m/d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y', 'd.m.Y', 'Y-m-d H:i:s', 'n/j/Y', 'j/n/Y', 'M d, Y', 'F d, Y', 'd M Y'] as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, $s);
            if ($dt) {
                return $dt->format('Y-m-d');
            }
        }
        $ts = strtotime($s);

        return $ts !== false ? date('Y-m-d', $ts) : null;
    }
}
