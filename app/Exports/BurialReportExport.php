<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BurialReportExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle, WithEvents
{
    protected array $data;
    protected array $settings;

    // Row offsets (set dynamically after building the array)
    protected int $execStart    = 4;
    protected int $execEnd      = 10;
    protected int $burialStart  = 13;
    protected int $burialEnd    = 18;
    protected int $burialTotal  = 20;
    protected int $monthlyStart = 23;
    protected int $monthlyEnd   = 24;
    protected int $sigRow       = 28;

    public function __construct(array $data, array $settings)
    {
        $this->data     = $data;
        $this->settings = $settings;
    }

    public function title(): string
    {
        return 'Burial Operations Report ' . ($this->data['year'] ?? now()->year);
    }

    // ─── DATA ARRAY ────────────────────────────────────────────────────────────

    public function array(): array
    {
        $d        = $this->data;
        $s        = $this->settings;
        $year     = $d['year'] ?? now()->year;
        $prepBy   = auth()->user()?->name ?? 'Administrator';
        $mun      = $s['municipality_name'] ?? 'Carmen';
        $prov     = $s['province'] ?? 'Davao del Norte';
        $registrar= $s['registrar_name'] ?? 'MUNICIPAL CIVIL REGISTRAR';

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // Revenue breakdown
        $breakdown = $d['revenueBreakdown'] ?? [];

        return [
            // ── TITLE BLOCK (row 1) ──
            /* 1 */ ["MUNICIPALITY OF {$mun} — BURIAL OPERATIONS REPORT", null, null, null, null],
            /* 2 */ ["Province of {$prov} · Office of the Municipal Civil Registrar · Calendar Year {$year}", null, null, null, null],
            /* 3 */ ["Generated: " . now()->format('F d, Y h:i A') . "   |   Prepared by: {$prepBy}", null, null, null, null],

            // ── SECTION I: EXECUTIVE SUMMARY ──
            /* 4 */ ['SECTION I — EXECUTIVE SUMMARY', null, null, null, null],
            /* 5 */ ['Metric', 'Value', null, null, null],
            /* 6 */ ['Total Permits Registered (All Time)',    $d['totalPermits'],         null, null, null],
            /* 7 */ ['Total Permits This Year ('.$year.')',   $d['totalYearlyPermits'],    null, null, null],
            /* 8 */ ['Active / Valid Permits',                $d['activePermits'],         null, null, null],
            /* 9 */ ['Expiring (Within 30 Days)',             $d['expiringSoon'],          null, null, null],
            /* 10*/ ['Expired / Overdue',                     $d['expiredPermits'],        null, null, null],
            /* 11*/ ['Renewal Permits Tracked',               $d['renewedPermits'],        null, null, null],
            /* 12*/ ['Total Deceased Records',                $d['totalDeceased'],         null, null, null],

            // ── SECTION II: BURIAL TYPE BREAKDOWN ──
            /* 13*/ ['SECTION II — BURIAL TYPE & REVENUE BREAKDOWN', null, null, null, null],
            /* 14*/ ['Burial Category', 'Unit Tariff (PHP)', 'Permit Count', 'Projected Revenue (PHP)', null],

            // Breakdown rows (15-20 for 6 types + total)
            ...array_map(fn($row) => [
                $row['label'],
                (float) $row['unitPrice'],
                (int)   $row['count'],
                (float) $row['total'],
                null,
            ], $breakdown),

            /* 21*/ ['TOTAL GROSS ESTIMATED REVENUE', null, array_sum(array_column($breakdown, 'count')), (float)$d['estimatedRevenue'], null],

            // ── SECTION III: MONTHLY VOLUME ──
            /* 22*/ ['SECTION III — MONTHLY PERMIT REGISTRATION (' . $year . ')', null, null, null, null],
            /* 23*/ [...$months],
            /* 24*/ array_values($d['monthlyData'] ?? array_fill(0, 12, 0)),

            // ── SIGNATURE BLOCK ──
            /* 25*/ [null, null, null, null, null],
            /* 26*/ [null, null, null, null, null],
            /* 27*/ ['Prepared By:', null, 'Certifying Officer:', null, null],
            /* 28*/ [null, null, null, null, null],
            /* 29*/ [$prepBy, null, strtoupper($registrar), null, null],
            /* 30*/ ['System Administrator', null, 'Municipal Civil Registrar', null, null],
        ];
    }

    // ─── COLUMN WIDTHS ─────────────────────────────────────────────────────────

    // Note: ShouldAutoSize handles this, but we can override for key columns
    // via afterSheet event instead.

    // ─── STYLES ────────────────────────────────────────────────────────────────

    public function styles(Worksheet $sheet)
    {
        // Styles are applied via the AfterSheet event for full row/cell control.
        // Returning empty array here is intentional.
        return [];
    }

    // ─── EVENTS (AfterSheet) ───────────────────────────────────────────────────

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyStyles($sheet);
            }
        ];
    }

    private function applyStyles(Worksheet $sheet): void
    {
        $d         = $this->data;
        $breakdown = $d['revenueBreakdown'] ?? [];
        $numTypes  = count($breakdown);

        // ── Dynamic row offsets ──────────────────────────────────────────────
        $titleRow   = 1;
        $subtitleRow= 2;
        $metaRow    = 3;
        $sec1Header = 4;
        $colHeader1 = 5;
        $execDataS  = 6;
        $execDataE  = 5 + 7; // 7 stats rows
        $sec2Header = $execDataE + 2;
        $colHeader2 = $sec2Header + 1;
        $burialDataS= $colHeader2 + 1;
        $burialDataE= $burialDataS + $numTypes - 1;
        $totalRow   = $burialDataE + 1;
        $sec3Header = $totalRow + 2;
        $monthNames = $sec3Header + 1;
        $monthVals  = $monthNames + 1;
        $sigLabel   = $monthVals + 3;
        $sigName    = $sigLabel + 2;
        $sigTitle   = $sigName + 1;

        $lastCol    = 'E';
        $lastDataCol= 'D'; // revenue col

        // ── TITLE BLOCK ─────────────────────────────────────────────────────
        // Merge title across all columns
        $sheet->mergeCells("A{$titleRow}:{$lastCol}{$titleRow}");
        $sheet->mergeCells("A{$subtitleRow}:{$lastCol}{$subtitleRow}");
        $sheet->mergeCells("A{$metaRow}:{$lastCol}{$metaRow}");

        $sheet->getStyle("A{$titleRow}:{$lastCol}{$titleRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($titleRow)->setRowHeight(32);

        $sheet->getStyle("A{$subtitleRow}:{$lastCol}{$subtitleRow}")->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '475569']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("A{$metaRow}:{$lastCol}{$metaRow}")->applyFromArray([
            'font' => ['size' => 9, 'italic' => true, 'color' => ['rgb' => '94A3B8']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── SECTION HEADERS (navy, white text, thick bottom border) ─────────
        foreach ([$sec1Header, $sec2Header, $sec3Header] as $sRow) {
            $sheet->mergeCells("A{$sRow}:{$lastCol}{$sRow}");
            $sheet->getStyle("A{$sRow}:{$lastCol}{$sRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '3B82F6']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
            ]);
            $sheet->getRowDimension($sRow)->setRowHeight(22);
        }

        // ── COLUMN HEADERS — SECTION I ───────────────────────────────────────
        $sheet->getStyle("A{$colHeader1}:B{$colHeader1}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => 'CBD5E1']]],
        ]);

        // ── EXEC DATA ROWS — alternating zebra ───────────────────────────────
        for ($r = $execDataS; $r <= $execDataE; $r++) {
            $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F8FAFC';
            $sheet->getStyle("A{$r}:B{$r}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'font' => ['size' => 10],
            ]);
            // Value column — right align, mono-style bold
            $sheet->getStyle("B{$r}")->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);
        }

        // ── COLUMN HEADERS — SECTION II ──────────────────────────────────────
        $sheet->getStyle("A{$colHeader2}:{$lastDataCol}{$colHeader2}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => 'CBD5E1']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle("A{$colHeader2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // ── BURIAL TYPE ROWS — alternating zebra ─────────────────────────────
        for ($r = $burialDataS; $r <= $burialDataE; $r++) {
            $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F8FAFC';
            $sheet->getStyle("A{$r}:{$lastDataCol}{$r}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'font' => ['size' => 10],
                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
            // Unit tariff as currency format
            $sheet->getStyle("B{$r}")->getNumberFormat()->setFormatCode('"₱"#,##0.00');
            $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Projected revenue as currency
            $sheet->getStyle("D{$r}")->getNumberFormat()->setFormatCode('"₱"#,##0.00');
            $sheet->getStyle("D{$r}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);
        }

        // ── TOTAL ROW ─────────────────────────────────────────────────────────
        $sheet->getStyle("A{$totalRow}:{$lastDataCol}{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1E293B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
            'borders' => [
                'top'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '93C5FD']],
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '93C5FD']],
            ],
        ]);
        $sheet->getStyle("D{$totalRow}")->getNumberFormat()->setFormatCode('"₱"#,##0.00');
        $sheet->getStyle("D{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);
        $sheet->getStyle("C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // ── MONTHLY SECTION ───────────────────────────────────────────────────
        // Month name headers
        $colNames = range('A', 'L'); // A=Jan ... L=Dec
        $sheet->getStyle("A{$monthNames}:L{$monthNames}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        // Month values — highlight peak
        $monthlyData = $this->data['monthlyData'] ?? array_fill(0, 12, 0);
        $peak        = max(array_merge($monthlyData, [1]));
        foreach ($colNames as $i => $col) {
            $val = $monthlyData[$i] ?? 0;
            $bgColor = ($val === $peak && $val > 0) ? '3B82F6' : 'FFFFFF';
            $txtColor= ($val === $peak && $val > 0) ? 'FFFFFF' : '1E293B';
            $sheet->getStyle("{$col}{$monthVals}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $txtColor]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
            // Set column widths for month columns
            $sheet->getColumnDimension($col)->setWidth(8);
        }
        $sheet->getRowDimension($monthVals)->setRowHeight(26);

        // ── SIGNATURE BLOCK ───────────────────────────────────────────────────
        $sheet->getStyle("A{$sigLabel}:A{$sigLabel}")->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle("C{$sigLabel}:C{$sigLabel}")->getFont()->setBold(true)->setSize(9);

        // Underline the name cells
        $sheet->getStyle("A{$sigName}:A{$sigName}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getStyle("C{$sigName}:C{$sigName}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getStyle("A{$sigTitle}:A{$sigTitle}")->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->getStyle("C{$sigTitle}:C{$sigTitle}")->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        // ── GLOBAL WRAPPING & COLUMN WIDTHS ──────────────────────────────────
        $sheet->getStyle("A1:E{$sigTitle}")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(42);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(24);
        $sheet->getColumnDimension('E')->setWidth(6);

        // ── LGU SEAL IMAGE ───────────────────────────────────────────────────
        $logoPath = public_path('images/carmen-seal.png');
        if (file_exists($logoPath)) {
            try {
                $drawing = new Drawing();
                $drawing->setName('LGU Carmen Seal');
                $drawing->setDescription('Municipal Seal');
                $drawing->setPath($logoPath);
                $drawing->setHeight(64);
                $drawing->setCoordinates('E1');
                $drawing->setOffsetX(4);
                $drawing->setOffsetY(4);
                $drawing->setWorksheet($sheet);
            } catch (\Throwable $e) {
                // Logo embedding failed — continue without it
            }
        }

        // ── SET ROW HEIGHTS FOR SPACING ROWS ─────────────────────────────────
        $sheet->getRowDimension($sec1Header - 1 > 0 ? $sec1Header - 1 : 1)->setRowHeight(4);
        $sheet->getRowDimension($sec2Header - 1)->setRowHeight(10);
        $sheet->getRowDimension($sec3Header - 1)->setRowHeight(10);
        $sheet->getRowDimension($monthVals + 1)->setRowHeight(10);
    }
}
