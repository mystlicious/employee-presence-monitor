<?php

namespace App\Controllers;

use App\Models\Employee;

class AdminDashboardController
{
    protected static function pdo()
    {
        if (!empty($GLOBALS['APP_PDO'])) return $GLOBALS['APP_PDO'];
        throw new \RuntimeException('Database connection not initialized.');
    }

    /**
     * Build analytics data for admin dashboard.
     * Returns status distribution + weekly absence trend.
     */
    public static function buildAnalyticsData(?string $selectedDate = null): array
    {
        $pdo = self::pdo();
        $selectedDate = $selectedDate ?: date('Y-m-d');

        $totalEmployees = Employee::count();

        // Latest status per employee on selected date (snapshot style).
        $latestSql = <<<SQL
SELECT l.employee_name, l.status
FROM presence_logs l
INNER JOIN (
  SELECT employee_name, MAX(id) AS max_id
  FROM presence_logs
  WHERE log_date = :d
  GROUP BY employee_name
) latest ON latest.max_id = l.id
SQL;
        $stmt = $pdo->prepare($latestSql);
        $stmt->execute(['d' => $selectedDate]);
        $latestRows = $stmt->fetchAll();

        $statusCounts = [
            __('status.in_office') => max(0, $totalEmployees - count($latestRows)),
            'WFH' => 0,
            'Sakit' => 0,
            'Cuti Tahunan' => 0,
            'Dinas Luar' => 0,
            __('status.izin') => 0,
        ];

        $izinKey = __('status.izin');
        foreach ($latestRows as $row) {
            $status = trim((string)($row['status'] ?? ''));
            if ($status === 'WFH') $statusCounts['WFH']++;
            elseif ($status === 'Sakit') $statusCounts['Sakit']++;
            elseif ($status === 'Cuti Tahunan') $statusCounts['Cuti Tahunan']++;
            elseif ($status === 'Dinas Luar') $statusCounts['Dinas Luar']++;
            elseif ($status === 'Izin Keluar') $statusCounts[$izinKey]++;
        }

        $statusPercentages = [];
        foreach ($statusCounts as $key => $count) {
            $statusPercentages[$key] = $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0.0;
        }

        // Last 7 days (inclusive): absence = unique employees with any log on each day.
        $end = new \DateTimeImmutable($selectedDate);
        $start = $end->modify('-6 days');
        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');

        $dailySql = <<<SQL
SELECT log_date, COUNT(DISTINCT employee_name) AS absent_count
FROM presence_logs
WHERE log_date BETWEEN :s AND :e
GROUP BY log_date
ORDER BY log_date ASC
SQL;
        $dailyStmt = $pdo->prepare($dailySql);
        $dailyStmt->execute(['s' => $startDate, 'e' => $endDate]);
        $rows = $dailyStmt->fetchAll();
        $dailyMap = [];
        foreach ($rows as $r) {
            $dailyMap[$r['log_date']] = (int)($r['absent_count'] ?? 0);
        }

        $weeklyLabels = [];
        $weeklyCounts = [];
        $cursor = $start;
        for ($i = 0; $i < 7; $i++) {
            $k = $cursor->format('Y-m-d');
            $weeklyLabels[] = $cursor->format('d M');
            $weeklyCounts[] = $dailyMap[$k] ?? 0;
            $cursor = $cursor->modify('+1 day');
        }

        $trend7d = self::buildDailyAbsentTrend($pdo, $selectedDate, 7);
        $trend30d = self::buildDailyAbsentTrend($pdo, $selectedDate, 30);
        $trend1y = self::buildMonthlyAbsentTrend($pdo, $selectedDate, 12);

        return [
            'selectedDate' => $selectedDate,
            'totalEmployees' => $totalEmployees,
            'statusCounts' => $statusCounts,
            'statusPercentages' => $statusPercentages,
            'weeklyLabels' => $weeklyLabels,
            'weeklyCounts' => $weeklyCounts,
            'trends' => [
                '7d' => $trend7d,
                '30d' => $trend30d,
                '1y' => $trend1y,
            ],
        ];
    }

    /**
     * @return array{labels: list<string>, counts: list<int>}
     */
    private static function buildDailyAbsentTrend(\PDO $pdo, string $endDate, int $days): array
    {
        $days = max(1, min(366, $days));
        $end = new \DateTimeImmutable($endDate . ' 12:00:00');
        $start = $end->modify('-' . ($days - 1) . ' days');
        $startDate = $start->format('Y-m-d');
        $endDateSql = $end->format('Y-m-d');

        $dailySql = <<<SQL
SELECT log_date, COUNT(DISTINCT employee_name) AS absent_count
FROM presence_logs
WHERE log_date BETWEEN :s AND :e
GROUP BY log_date
ORDER BY log_date ASC
SQL;
        $dailyStmt = $pdo->prepare($dailySql);
        $dailyStmt->execute(['s' => $startDate, 'e' => $endDateSql]);
        $rows = $dailyStmt->fetchAll();
        $dailyMap = [];
        foreach ($rows as $r) {
            $dailyMap[$r['log_date']] = (int) ($r['absent_count'] ?? 0);
        }

        $labels = [];
        $counts = [];
        $cursor = $start;
        for ($i = 0; $i < $days; $i++) {
            $k = $cursor->format('Y-m-d');
            $labels[] = $days <= 14 ? $cursor->format('d M') : $cursor->format('j M');
            $counts[] = $dailyMap[$k] ?? 0;
            $cursor = $cursor->modify('+1 day');
        }

        return ['labels' => $labels, 'counts' => $counts];
    }

    /**
     * Last N complete calendar months ending at the month of $endDate (inclusive).
     *
     * @return array{labels: list<string>, counts: list<float>}
     */
    private static function buildMonthlyAbsentTrend(\PDO $pdo, string $endDate, int $months): array
    {
        $months = max(1, min(24, $months));
        $anchor = new \DateTimeImmutable($endDate . ' 12:00:00');
        $endMonth = $anchor->modify('first day of this month');

        $labels = [];
        $counts = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = $endMonth->modify('-' . $i . ' months');
            $first = $monthStart->format('Y-m-d');
            $last = $monthStart->modify('last day of this month')->format('Y-m-d');

            $dailySql = <<<SQL
SELECT log_date, COUNT(DISTINCT employee_name) AS absent_count
FROM presence_logs
WHERE log_date BETWEEN :s AND :e
GROUP BY log_date
ORDER BY log_date ASC
SQL;
            $dailyStmt = $pdo->prepare($dailySql);
            $dailyStmt->execute(['s' => $first, 'e' => $last]);
            $rows = $dailyStmt->fetchAll();
            $dayMap = [];
            foreach ($rows as $r) {
                $dayMap[$r['log_date']] = (int) ($r['absent_count'] ?? 0);
            }
            $daysInMonth = (int) $monthStart->format('t');
            $sum = 0;
            $walk = new \DateTimeImmutable($first . ' 12:00:00');
            for ($d = 0; $d < $daysInMonth; $d++) {
                $k = $walk->format('Y-m-d');
                $sum += $dayMap[$k] ?? 0;
                $walk = $walk->modify('+1 day');
            }
            $avg = $daysInMonth > 0 ? $sum / $daysInMonth : 0.0;

            $labels[] = $monthStart->format('M y');
            $counts[] = round($avg, 2);
        }

        return ['labels' => $labels, 'counts' => $counts];
    }
}

