<?php

namespace App\Models;

// Minimal PDO-backed PresenceLog replacement so app can run without Laravel/Eloquent
class PresenceLog
{
    protected static function pdo()
    {
        if (!empty($GLOBALS['APP_PDO'])) return $GLOBALS['APP_PDO'];
        throw new \RuntimeException('Database connection not initialized.');
    }

    public static function countEmployees()
    {
        return Employee::count();
    }

    public static function countForDate(string $date)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM presence_logs WHERE log_date = :d');
        $stmt->execute(['d' => $date]);
        $r = $stmt->fetch();
        return (int)($r['c'] ?? 0);
    }

    public static function uniqueEmployeesForDate(string $date)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT COUNT(DISTINCT employee_name) as c FROM presence_logs WHERE log_date = :d');
        $stmt->execute(['d' => $date]);
        $r = $stmt->fetch();
        return (int)($r['c'] ?? 0);
    }

    public static function uniqueLocationsForDate(string $date)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT COUNT(DISTINCT location) as c FROM presence_logs WHERE log_date = :d AND location IS NOT NULL AND location <> ''");
        $stmt->execute(['d' => $date]);
        $r = $stmt->fetch();
        return (int)($r['c'] ?? 0);
    }

    public static function logsByDate(string $date)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT * FROM presence_logs WHERE log_date = :d ORDER BY COALESCE(start_time, log_time, '00:00:00') DESC, created_at DESC, id DESC");
        $stmt->execute(['d' => $date]);
        return $stmt->fetchAll();
    }

    /**
     * Logs for a day, optionally filtered by exact status match.
     */
    public static function logsByDateAndStatus(string $date, string $status)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT * FROM presence_logs WHERE log_date = :d AND status = :s ORDER BY COALESCE(start_time, log_time, '00:00:00') DESC, created_at DESC, id DESC");
        $stmt->execute(['d' => $date, 's' => $status]);
        return $stmt->fetchAll();
    }

    public static function distinctEmployees()
    {
        return Employee::names();
    }

    /**
     * Block double-submit spam (same employee, day, status, location within a short window).
     */
    public static function hasRecentDuplicate(
        string $employeeName,
        string $logDate,
        string $status,
        string $location,
        int $withinSeconds = 90
    ): bool {
        if ($employeeName === '' || $logDate === '' || $status === '') {
            return false;
        }
        $withinSeconds = max(1, min(300, $withinSeconds));
        $since = date('Y-m-d H:i:s', time() - $withinSeconds);
        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id FROM presence_logs
             WHERE employee_name = :n AND log_date = :d AND status = :s AND location = :loc
               AND created_at >= :since
             LIMIT 1'
        );
        $stmt->execute([
            'n' => $employeeName,
            'd' => $logDate,
            's' => $status,
            'loc' => $location,
            'since' => $since,
        ]);

        return (bool) $stmt->fetch();
    }

    public static function createLog(string $employeeName, array $data)
    {
        $pdo = self::pdo();
        $checkEmployee = $pdo->prepare('SELECT id FROM employees WHERE name = :name LIMIT 1');
        $checkEmployee->execute(['name' => $employeeName]);
        if (!$checkEmployee->fetch()) {
            throw new \RuntimeException(__('employee_not_registered'));
        }

        $now = date('Y-m-d H:i:s');
        $logDate = $data['log_date'] ?? date('Y-m-d');
        $logTime = $data['log_time'] ?? date('H:i:s');
        $sql = 'INSERT INTO presence_logs (employee_name, photo, status, location, note, is_in_office, log_date, log_time, start_time, end_time, attachment_path, created_at, updated_at) VALUES (:emp, :photo, :status, :location, :note, 0, :log_date, :log_time, :start_time, :end_time, :attachment_path, :c, :u)';
        $s = $pdo->prepare($sql);
        $s->execute([
            'emp' => $employeeName,
            'photo' => $data['photo'] ?? null,
            'status' => $data['status'] ?? null,
            'location' => $data['location'] ?? null,
            'note' => $data['note'] ?? null,
            'log_date' => $logDate,
            'log_time' => $logTime,
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'attachment_path' => $data['attachment_path'] ?? null,
            'c' => $now,
            'u' => $now,
        ]);
        return $pdo->lastInsertId();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT * FROM presence_logs WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Distinct log dates per employee within an inclusive date range (counts a day once per employee).
     *
     * @param string[] $names
     * @return array<string,int> employee_name => day count
     */
    public static function distinctLogDatesByEmployeeForRange(array $names, string $startDate, string $endDate): array
    {
        $names = array_values(array_unique(array_filter($names, static function ($n) {
            return $n !== null && $n !== '';
        })));
        if ($names === []) {
            return [];
        }
        $pdo = self::pdo();
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $sql = "SELECT employee_name, COUNT(DISTINCT log_date) AS c
                FROM presence_logs
                WHERE employee_name IN ($placeholders)
                  AND log_date BETWEEN ? AND ?
                GROUP BY employee_name";
        $stmt = $pdo->prepare($sql);
        $params = array_merge($names, [$startDate, $endDate]);
        $stmt->execute($params);
        $out = [];
        while ($row = $stmt->fetch()) {
            $out[$row['employee_name']] = (int)($row['c'] ?? 0);
        }
        return $out;
    }

    /**
     * Latest log row id per employee for a calendar day (by max id).
     *
     * @param string[] $names optional filter; empty = all employees with a log that day
     * @return array<string,string> employee_name => status
     */
    public static function latestStatusByEmployeeForDate(string $date, array $names = []): array
    {
        $pdo = self::pdo();
        if ($names !== []) {
            $names = array_values(array_unique(array_filter($names, static function ($n) {
                return $n !== null && $n !== '';
            })));
        }
        if ($names !== []) {
            $ph = implode(',', array_fill(0, count($names), '?'));
            $sql = "SELECT l.employee_name, l.status
                    FROM presence_logs l
                    INNER JOIN (
                      SELECT employee_name, MAX(id) AS max_id
                      FROM presence_logs
                      WHERE log_date = ? AND employee_name IN ($ph)
                      GROUP BY employee_name
                    ) x ON x.max_id = l.id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge([$date], $names));
        } else {
            $sql = <<<SQL
SELECT l.employee_name, l.status
FROM presence_logs l
INNER JOIN (
  SELECT employee_name, MAX(id) AS max_id
  FROM presence_logs
  WHERE log_date = :d
  GROUP BY employee_name
) x ON x.max_id = l.id
SQL;
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['d' => $date]);
        }
        $out = [];
        while ($row = $stmt->fetch()) {
            $out[$row['employee_name']] = (string)($row['status'] ?? '');
        }
        return $out;
    }

    /**
     * Most recent log_date per employee (any time in history).
     *
     * @param string[] $names
     * @return array<string,?string> employee_name => Y-m-d or null
     */
    public static function lastLogDateByEmployee(array $names): array
    {
        $names = array_values(array_unique(array_filter($names, static function ($n) {
            return $n !== null && $n !== '';
        })));
        if ($names === []) {
            return [];
        }
        $pdo = self::pdo();
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $sql = "SELECT employee_name, MAX(log_date) AS d FROM presence_logs WHERE employee_name IN ($placeholders) GROUP BY employee_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($names);
        $out = [];
        while ($row = $stmt->fetch()) {
            $d = $row['d'] ?? null;
            $out[$row['employee_name']] = $d !== null && $d !== '' ? (string)$d : null;
        }
        return $out;
    }

    /**
     * Count distinct log days for a single employee in a date range (inclusive).
     */
    public static function countDistinctLogDaysForEmployee(string $name, string $startDate, string $endDate): int
    {
        if ($name === '' || $startDate === '' || $endDate === '') {
            return 0;
        }
        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            "SELECT COUNT(DISTINCT log_date) AS c FROM presence_logs
             WHERE employee_name = :n AND log_date IS NOT NULL AND log_date <> ''
               AND log_date BETWEEN :s AND :e"
        );
        $stmt->execute(['n' => $name, 's' => $startDate, 'e' => $endDate]);
        $row = $stmt->fetch();

        return (int)($row['c'] ?? 0);
    }

    /**
     * All logs for an employee, newest first.
     *
     * @return list<array<string,mixed>>
     */
    public static function allLogsForEmployeeOrderByDateDesc(string $name): array
    {
        if ($name === '') {
            return [];
        }
        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT * FROM presence_logs WHERE employee_name = :n
             ORDER BY log_date DESC, id DESC'
        );
        $stmt->execute(['n' => $name]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Latest "In Office" log row (by max id) per employee in $names.
     * Status match is case-insensitive and trimmed.
     *
     * @param string[] $names
     * @return array<string, array{log_date:?string,log_time:?string,created_at:?string, id:int}>
     */
    public static function lastInOfficeRowsByNames(array $names): array
    {
        $names = array_values(array_unique(array_filter($names, static function ($n) {
            return $n !== null && $n !== '';
        })));
        if ($names === []) {
            return [];
        }
        $pdo = self::pdo();
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $sql = "SELECT p.employee_name, p.id, p.log_date, p.log_time, p.created_at
                FROM presence_logs p
                INNER JOIN (
                    SELECT employee_name, MAX(id) AS max_id
                    FROM presence_logs
                    WHERE LOWER(TRIM(COALESCE(status, ''))) = 'in office'
                      AND employee_name IN ($placeholders)
                    GROUP BY employee_name
                ) t ON t.max_id = p.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($names);
        $out = [];
        while ($row = $stmt->fetch()) {
            $k = (string)($row['employee_name'] ?? '');
            if ($k === '') {
                continue;
            }
            $out[$k] = [
                'id' => (int)($row['id'] ?? 0),
                'log_date' => isset($row['log_date']) && $row['log_date'] !== '' ? (string)$row['log_date'] : null,
                'log_time' => isset($row['log_time']) && $row['log_time'] !== '' ? (string)$row['log_time'] : null,
                'created_at' => isset($row['created_at']) && $row['created_at'] !== '' ? (string)$row['created_at'] : null,
            ];
        }
        return $out;
    }
}

