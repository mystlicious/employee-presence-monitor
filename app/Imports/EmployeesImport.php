<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Bulk import from workbook sheets (PNS / PPPK / PPPK PW columns: NIP, NAMA, JABATAN NAMA).
 *
 * This project ships without full Laravel Framework. maatwebsite/excel requires Laravel —
 * Composer: composer require phpoffice/phpspreadsheet (already used here as the same engine Laravel Excel wraps).
 *
 * Mirrors multi-sheet imports: one {@see EmployeesSheetImport} per categorized worksheet.
 */
final class EmployeesImport
{
    public function __construct(
        private readonly string $spreadsheetPath
    ) {}

    /** @return int Number of rows upserted successfully */
    public function import(): int
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->spreadsheetPath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->spreadsheetPath);
        $total = 0;
        try {
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $category = EmployeesSheetImport::categoryFromSheetTitle($worksheet->getTitle());
                if ($category === null) {
                    continue;
                }
                $total += (new EmployeesSheetImport($category))->import($worksheet);
            }
        } finally {
            $spreadsheet->disconnectWorksheets();
        }

        return $total;
    }
}

/**
 * Single-sheet import: heading row with NIP, NAMA, JABATAN NAMA; upsert behaviour by NIP.
 */
final class EmployeesSheetImport
{
    public function __construct(
        private readonly string $category
    ) {}

    public static function categoryFromSheetTitle(string $title): ?string
    {
        $t = strtoupper(preg_replace('/\s+/', ' ', trim($title)) ?? '');
        if ($t === '') {
            return null;
        }
        if (str_contains($t, 'PPPK') && str_contains($t, 'PW')) {
            return 'PPPK PW';
        }
        if (str_contains($t, 'PPPK')) {
            return 'PPPK';
        }
        if (str_contains($t, 'PNS')) {
            return 'PNS';
        }

        return null;
    }

    /** @return int Number of rows upserted successfully */
    public function import(Worksheet $worksheet): int
    {
        $rows = $worksheet->toArray(null, true, true, false);
        if ($rows === []) {
            return 0;
        }
        $headerRow = array_shift($rows);
        if (! is_array($headerRow)) {
            return 0;
        }
        $headerMap = self::buildHeaderIndexMap($headerRow);
        $nipCol = self::columnForKeys($headerMap, ['nip']);
        $namaCol = self::columnForKeys($headerMap, ['nama']);
        $jabatanCol = self::columnForKeys($headerMap, ['jabatan nama', 'jabatan_nama']);

        if ($nipCol === null || $namaCol === null || $jabatanCol === null) {
            return 0;
        }

        $processed = 0;
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $rawNip = $row[$nipCol] ?? null;
            $rawNama = $row[$namaCol] ?? null;
            $rawJabatan = $row[$jabatanCol] ?? null;
            $nip = ltrim(trim((string) $rawNip), "'");
            $nama = trim((string) $rawNama);
            if ($nip === '' || $nama === '') {
                continue;
            }
            $position = trim((string) $rawJabatan);
            $position = $position === '' ? null : $position;
            if (Employee::upsertByNip($nip, $nama, $position, $this->category)) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * @param list<mixed> $headerRow
     * @return array<string, int> normalized header => 0-based column index
     */
    private static function buildHeaderIndexMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $i => $cell) {
            $key = self::normalizeHeaderKey((string) $cell);
            if ($key !== '' && ! isset($map[$key])) {
                $map[$key] = (int) $i;
            }
        }

        return $map;
    }

    private static function normalizeHeaderKey(string $raw): string
    {
        $s = strtolower(trim($raw));
        if (str_starts_with($s, "\xEF\xBB\xBF")) {
            $s = substr($s, 3);
        }
        $s = preg_replace('/\s+/u', ' ', $s) ?? $s;
        $s = str_replace(['_', '-'], ' ', $s);

        return trim($s);
    }

    /** @param array<string, int> $headerMap */
    private static function columnForKeys(array $headerMap, array $candidates): ?int
    {
        foreach ($candidates as $c) {
            $k = self::normalizeHeaderKey($c);
            if (isset($headerMap[$k])) {
                return $headerMap[$k];
            }
        }

        return null;
    }
}
