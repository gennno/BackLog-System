<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BacklogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/backlog.csv');

        if (!file_exists($filePath)) {
            $this->command?->error("CSV file not found: " . $filePath);
            return;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->command?->error("Unable to open CSV: " . $filePath);
            return;
        }

        // Read header row
        $header = fgetcsv($handle);
        if (!$header) {
            $this->command?->error("CSV header missing or empty.");
            fclose($handle);
            return;
        }

        // Normalize header keys (trim)
        $header = array_map(function ($h) {
            return trim($h);
        }, $header);

        $rows = [];
        $now = Carbon::now();

        $rowNumber = 1; // including header

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Skip completely empty lines
            if (count(array_filter($row, fn($v) => trim((string)$v) !== '')) === 0) {
                continue;
            }

            $data = array_combine($header, $row);

            // Helper access with default null
            $get = function ($key) use ($data) {
                return array_key_exists($key, $data) ? trim((string)$data[$key]) : null;
            };

            $tanggalTemuanRaw = $get('INSPECTION DATE');

            $record = [
                'tanggal_temuan' => self::toDate($tanggalTemuanRaw),
                'id_inspeksi'    => $get('IDINSPEKSI'),
                'code_number'    => $get('CODE NUMBER') ?: null,
                'hm'             => self::toIntOrNull($get('HM')),
                'component'      => $get('COMPONENT') ?: null,
                'plan_repair'    => self::normalizePlanRepair($get('PLAN REPAIR')),
                'status'         => self::normalizeStatus($get('STATUS')),
                'condition'      => self::normalizeCondition($get('CONDITION')),
                'gl_pic'         => $get('GL PIC') ?: null,
                'pic_daily'      => $get('PIC DAILY') ?: null,
                'evidence'       => null,
                'deskripsi'      => $get('PROBLEM DESCRIPTION') ?: null,
                'part_number'    => $get('PART NUMBER REQUIRED') ?: null,
                'part_name'      => $get('PART DESCRIPTION') ?: null,
                'no_figure'      => $get('NO. FIGURE') ?: null,
                'qty'            => self::toIntOrNull($get('QTY')),
                'close_by'       => null,
                'id_action'      => null,
                'made_by'        => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            $rows[] = $record;

            // Chunked insert every 500 rows to save memory
            if (count($rows) >= 500) {
                DB::table('backlog')->insert($rows);
                $rows = [];
            }
        }

        fclose($handle);

        if (!empty($rows)) {
            DB::table('backlog')->insert($rows);
        }

        $this->command?->info("Backlog seeding completed. Total rows processed: " . ($rowNumber - 1));
    }

    /**
     * Convert mixed Excel/CSV date field to Y-m-d.
     * Supports Excel serial (1900 system), "Y-m-d", "d/m/Y", etc.
     */
private static function toDate($value): string
{
    // Fallback: always return a valid date
    $fallback = Carbon::now()->format('Y-m-d');

    if ($value === null || $value === '') {
        return $fallback;
    }

    // Numeric Excel serial
    if (is_numeric($value)) {
        try {
            $origin = Carbon::create(1899, 12, 30); // Excel 1900 system
            return $origin->copy()->addDays((int)$value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    // Try parse common date strings
    $value = trim((string)$value);
    $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
    foreach ($formats as $fmt) {
        try {
            return Carbon::createFromFormat($fmt, $value)->format('Y-m-d');
        } catch (\Throwable $e) {}
    }

    // Fallback to Carbon::parse
    try {
        return Carbon::parse($value)->format('Y-m-d');
    } catch (\Throwable $e) {
        return $fallback;
    }
}


    private static function toIntOrNull($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        // Handle floats like "1.0"
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }
        return null;
    }

    private static function normalizePlanRepair(?string $value): ?string
    {
        if (!$value) return null;
        $v = strtolower(trim($value));
        if (in_array($v, ['next ps', 'next_ps', 'nextps', 'next'])) {
            return 'Next PS';
        }
        if (in_array($v, ['no repair', 'norepair', 'no_rep', 'no'])) {
            return 'No Repair';
        }
        // Unknown -> null to satisfy enum
        return null;
    }

    private static function normalizeStatus(?string $value): string
    {
        if (!$value) return 'Open BL';
        $v = strtolower(trim($value));
        return $v === 'close' ? 'Close' : 'Open BL';
    }

    private static function normalizeCondition(?string $value): ?string
    {
        if (!$value) return null;
        $v = strtolower(trim($value));
        if ($v === 'urgent') return 'Urgent';
        if ($v === 'caution') return 'Caution';
        return null;
    }
}
