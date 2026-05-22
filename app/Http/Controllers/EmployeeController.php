<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\PresenceLog;
use Carbon\Carbon;

/**
 * Employee list + detail (admin). Date math uses app default timezone (Carbon).
 */
class EmployeeController
{

    /** @var string[] */
    public const STATUS_FILTERS = ['all', 'in_office', 'wfh', 'sakit', 'cuti_tahunan', 'dinas', 'izin'];
    /** @var string[] */
    public const CATEGORY_FILTERS = ['all', 'PNS', 'PPPK', 'PPPK PW'];

    public static function now(): Carbon
    {
        $tz = date_default_timezone_get() ?: 'UTC';

        return Carbon::now($tz);
    }

    /**
     * @param array<string,string> $query
     * @return array{start: Carbon, end: Carbon, label: string, start_input: string, end_input: string}
     */
    public static function resolveDateRangeFromQuery(array $query): array
    {
        $now = self::now();
        $tz = $now->getTimezone();
        $s = trim((string)($query['start_date'] ?? ''));
        $e = trim((string)($query['end_date'] ?? ''));

        if ($s === '' && $e === '') {
            $start = $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $end = $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
        } elseif ($s !== '' && $e !== '') {
            $start = Carbon::parse($s, $tz)->startOfDay();
            $end = Carbon::parse($e, $tz)->endOfDay();
            if ($start->gt($end)) {
                $tmp = $start->copy();
                $start = $end->copy()->startOfDay();
                $end = $tmp->endOfDay();
            }
        } elseif ($s !== '') {
            $start = Carbon::parse($s, $tz)->startOfDay();
            $end = $start->copy()->endOfDay();
        } else {
            $end = Carbon::parse($e, $tz)->endOfDay();
            $start = $end->copy()->startOfDay();
        }

        $label = $start->format('j M Y') . ' – ' . $end->format('j M Y');

        return [
            'start' => $start,
            'end' => $end,
            'label' => $label,
            'start_input' => $start->toDateString(),
            'end_input' => $end->toDateString(),
        ];
    }

    /**
     * Latest status today → UI bucket (for filter matching).
     *
     * @return array{label:string,variant:string,bucket:string}
     */
    public static function badgeForTodayStatus(string $latestStatusToday): array
    {
        $s = trim($latestStatusToday);
        if ($s === '') {
            return ['label' => __('status.in_office'), 'variant' => 'green', 'bucket' => 'in_office'];
        }
        if ($s === 'WFH') {
            return ['label' => 'WFH', 'variant' => 'blue', 'bucket' => 'wfh'];
        }
        if ($s === 'Sakit') {
            return ['label' => 'Sakit', 'variant' => 'red', 'bucket' => 'sakit'];
        }
        if ($s === 'Cuti Tahunan') {
            return ['label' => 'Cuti Tahunan', 'variant' => 'yellow', 'bucket' => 'cuti_tahunan'];
        }
        if ($s === 'Dinas Luar') {
            return ['label' => 'Dinas Luar', 'variant' => 'violet', 'bucket' => 'dinas'];
        }
        if ($s === 'Izin Keluar' || $s === 'Izin') {
            return ['label' => __('status.izin'), 'variant' => 'orange', 'bucket' => 'izin'];
        }

        return ['label' => $s, 'variant' => 'slate', 'bucket' => 'other_out'];
    }

    /**
     * Safe HTML: highlight first case-insensitive occurrence of $needle in $name.
     */
    public static function highlightName(string $name, string $needle): string
    {
        $needle = trim($needle);
        if ($needle === '') {
            return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        }
        if (! extension_loaded('mbstring')) {
            $pos = stripos($name, $needle);
            if ($pos === false) {
                return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            }
            $before = substr($name, 0, $pos);
            $match = substr($name, $pos, strlen($needle));
            $after = substr($name, $pos + strlen($needle));

            return htmlspecialchars($before, ENT_QUOTES, 'UTF-8')
                . '<mark class="tw-bg-amber-200 tw-text-slate-900 tw-rounded tw-px-0.5">'
                . htmlspecialchars($match, ENT_QUOTES, 'UTF-8')
                . '</mark>'
                . htmlspecialchars($after, ENT_QUOTES, 'UTF-8');
        }
        $lowerName = mb_strtolower($name, 'UTF-8');
        $lowerNeedle = mb_strtolower($needle, 'UTF-8');
        $pos = mb_strpos($lowerName, $lowerNeedle, 0, 'UTF-8');
        if ($pos === false) {
            return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        }
        $len = mb_strlen($needle, 'UTF-8');
        $before = mb_substr($name, 0, $pos, 'UTF-8');
        $match = mb_substr($name, $pos, $len, 'UTF-8');
        $after = mb_substr($name, $pos + $len, null, 'UTF-8');

        return htmlspecialchars($before, ENT_QUOTES, 'UTF-8')
            . '<mark class="tw-bg-amber-200 tw-text-slate-900 tw-rounded tw-px-0.5">'
            . htmlspecialchars($match, ENT_QUOTES, 'UTF-8')
            . '</mark>'
            . htmlspecialchars($after, ENT_QUOTES, 'UTF-8');
    }

    public static function formatLastAbsenceLine(Carbon $now, ?string $lastLogDate): string
    {
        if ($lastLogDate === null || $lastLogDate === '') {
            return __('no_absence');
        }
        $at = Carbon::parse($lastLogDate, $now->getTimezone());
        if ($at->isToday()) {
            return __('today');
        }
        if ($at->isYesterday()) {
            return __('yesterday');
        }
        $days = $at->copy()->startOfDay()->diffInDays($now->copy()->startOfDay());
        if ($days < 7) {
            return __('days_ago', ['n' => (string) $days]);
        }

        return $at->format('j M Y');
    }

    /**
     * @param array<string,string> $get
     * @return array{
     *   rows: list<array<string,mixed>>,
     *   q: string,
     *   status: string,
     *   category: string,
     *   start_date: string,
     *   end_date: string,
     *   range_label: string,
     *   totalFiltered: int,
     *   registeredTotal: int
     * }
     */
    public static function buildList(array $get): array
    {
        $now = self::now();
        $today = $now->toDateString();
        $range = self::resolveDateRangeFromQuery($get);
        $startStr = $range['start']->toDateString();
        $endStr = $range['end']->toDateString();

        $employees = Employee::all();
        $names = array_column($employees, 'name');

        $absenceDays = PresenceLog::distinctLogDatesByEmployeeForRange($names, $startStr, $endStr);
        $latestToday = PresenceLog::latestStatusByEmployeeForDate($today, $names);
        $lastAbsenceDates = PresenceLog::lastLogDateByEmployee($names);

        $statusFilter = (string)($get['status'] ?? 'all');
        if (! in_array($statusFilter, self::STATUS_FILTERS, true)) {
            $statusFilter = 'all';
        }
        $categoryFilter = trim((string)($get['category'] ?? 'all'));
        if (! in_array($categoryFilter, self::CATEGORY_FILTERS, true)) {
            $categoryFilter = 'all';
        }
        $q = trim((string)($get['q'] ?? ''));

        $enriched = [];
        foreach ($employees as $emp) {
            $name = (string)($emp['name'] ?? '');
            $id = (int)($emp['id'] ?? 0);
            $statusToday = (string)($latestToday[$name] ?? '');
            $badge = self::badgeForTodayStatus($statusToday);
            $bucket = (string)($badge['bucket'] ?? 'other_out');
            $lastAbsence = self::formatLastAbsenceLine($now, $lastAbsenceDates[$name] ?? null);
            $position = trim((string)($emp['position'] ?? ''));
            $category = trim((string)($emp['category'] ?? ''));
            $subtitle = $position !== '' ? $position : __('no_position');
            if ($category !== '') {
                $subtitle .= ' · ' . $category;
            }
            $initial = extension_loaded('mbstring')
                ? mb_substr($name, 0, 1, 'UTF-8')
                : substr($name, 0, 1);

            $enriched[] = [
                'id' => $id,
                'name' => $name,
                'photo' => (string)($emp['photo'] ?? ''),
                'division_line' => $subtitle,
                'initial' => $initial !== '' ? $initial : '?',
                'badge' => $badge,
                'absence_days' => (int)($absenceDays[$name] ?? 0),
                'last_absence' => $lastAbsence,
                'bucket' => $bucket,
                'detail_url' => '/admin/employees/' . $id,
                'isActivePulse' => $bucket === 'in_office',
                'category' => $category,
            ];
        }

        $needle = $q !== '' && extension_loaded('mbstring') ? mb_strtolower($q, 'UTF-8') : strtolower($q);
        $filtered = array_values(array_filter($enriched, static function (array $row) use ($needle, $q, $statusFilter, $categoryFilter): bool {
            if ($q !== '') {
                $name = (string)($row['name'] ?? '');
                $hay = extension_loaded('mbstring') ? mb_strtolower($name, 'UTF-8') : strtolower($name);
                if (strpos($hay, $needle) === false) {
                    return false;
                }
            }
            if ($categoryFilter !== 'all') {
                $category = (string) ($row['category'] ?? '');
                if ($category !== $categoryFilter) {
                    return false;
                }
            }
            if ($statusFilter !== 'all') {
                $bucket = (string)($row['bucket'] ?? '');
                return $bucket === $statusFilter;
            }
            return true;
        }));

        $totalFiltered = count($filtered);

        return [
            'rows' => $filtered,
            'q' => $q,
            'status' => $statusFilter,
            'category' => $categoryFilter,
            'start_date' => $range['start_input'],
            'end_date' => $range['end_input'],
            'range_label' => $range['label'],
            'totalFiltered' => $totalFiltered,
            'registeredTotal' => count($employees),
        ];
    }

    /**
     * @return array{
     *   employee: array<string,mixed>,
     *   badge: array{label:string,variant:string,bucket:string},
     *   absence_week: int,
     *   absence_month: int,
     *   absence_year: int,
     *   last_absence: string,
     *   logs: list<array{id:int,date_label:string,status:string,note:string,has_proof:bool,proof_ext:string,proof_view_url:string,proof_download_url:string}>
     * }|null
     */
    public static function buildShow(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $emp = Employee::findById($id);
        if (! $emp) {
            return null;
        }
        $name = (string)($emp['name'] ?? '');
        if ($name === '') {
            return null;
        }
        $now = self::now();
        $today = $now->toDateString();
        $tz = $now->getTimezone();

        $statusToday = (string) (PresenceLog::latestStatusByEmployeeForDate($today, [$name])[$name] ?? '');
        $badge = self::badgeForTodayStatus($statusToday);
        $lastAbsenceMap = PresenceLog::lastLogDateByEmployee([$name]);
        $lastAbsence = self::formatLastAbsenceLine($now, $lastAbsenceMap[$name] ?? null);

        $w0 = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $w1 = $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $m0 = $now->copy()->startOfMonth()->toDateString();
        $m1 = $now->copy()->endOfMonth()->toDateString();
        $y0 = $now->copy()->startOfYear()->toDateString();
        $y1 = $now->copy()->endOfYear()->toDateString();

        $absenceWeek = PresenceLog::countDistinctLogDaysForEmployee($name, $w0, $w1);
        $absenceMonth = PresenceLog::countDistinctLogDaysForEmployee($name, $m0, $m1);
        $absenceYear = PresenceLog::countDistinctLogDaysForEmployee($name, $y0, $y1);

        $raw = PresenceLog::allLogsForEmployeeOrderByDateDesc($name);
        $logs = [];
        foreach ($raw as $r) {
            $ld = $r['log_date'] ?? null;
            $dateLabel = '—';
            if ($ld !== null && $ld !== '') {
                try {
                    $dateLabel = Carbon::parse((string) $ld, $tz)->format('j M Y');
                } catch (\Throwable) {
                    $dateLabel = (string) $ld;
                }
            }
            $logId = (int) ($r['id'] ?? 0);
            $ap = trim((string) ($r['attachment_path'] ?? ''));
            $ext = $ap !== '' ? strtolower(pathinfo($ap, PATHINFO_EXTENSION)) : '';
            if ($ext === 'jpeg') {
                $ext = 'jpg';
            }
            $hasProof = $ap !== '' && in_array($ext, ['pdf', 'jpg', 'png'], true);
            $logs[] = [
                'id' => $logId,
                'date_label' => $dateLabel,
                'status' => trim((string)($r['status'] ?? '')) !== '' ? (string) $r['status'] : '—',
                'note' => trim((string)($r['note'] ?? '')) !== '' ? (string) $r['note'] : '—',
                'has_proof' => $hasProof,
                'proof_ext' => $hasProof ? $ext : '',
                'proof_view_url' => $hasProof ? ('/admin/presence-proof?id=' . $logId) : '',
                'proof_download_url' => $hasProof ? ('/admin/presence-proof?id=' . $logId . '&download=1') : '',
            ];
        }

        return [
            'employee' => $emp,
            'badge' => $badge,
            'absence_week' => $absenceWeek,
            'absence_month' => $absenceMonth,
            'absence_year' => $absenceYear,
            'last_absence' => $lastAbsence,
            'logs' => $logs,
        ];
    }

    /**
     * @param array{name?: string, type?: string, tmp_name?: string, error?: int, size?: int} $file
     * @throws \RuntimeException|\InvalidArgumentException
     */
    public static function importFromSpreadsheetUpload(array $file): int
    {
        $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException('No file uploaded or upload error.');
        }
        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || ! is_uploaded_file($tmp)) {
            throw new \InvalidArgumentException('Invalid upload.');
        }
        $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (! in_array($ext, ['xlsx', 'xls', 'xlsm'], true)) {
            throw new \InvalidArgumentException(__('excel_required'));
        }

        return (new EmployeesImport($tmp))->import();
    }

}
