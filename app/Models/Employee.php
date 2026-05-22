<?php

namespace App\Models;

class Employee
{
    protected static function pdo()
    {
        if (!empty($GLOBALS['APP_PDO'])) return $GLOBALS['APP_PDO'];
        throw new \RuntimeException('Database connection not initialized.');
    }

    public static function findById(int $id): ?array
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT id, nip, name, photo, position, category, created_at, updated_at FROM employees WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function all()
    {
        $pdo = self::pdo();
        $stmt = $pdo->query('SELECT id, nip, name, photo, position, category, created_at, updated_at FROM employees ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public static function names()
    {
        $rows = self::all();
        return array_column($rows, 'name');
    }

    /**
     * Map employee name => photo path/url for display enrichment.
     * @param string[] $names
     * @return array<string, string>
     */
    public static function photosByNames(array $names): array
    {
        $names = array_values(array_unique(array_filter($names, static function ($n) {
            return $n !== null && $n !== '';
        })));
        if ($names === []) {
            return [];
        }
        $pdo = self::pdo();
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $stmt = $pdo->prepare("SELECT name, photo FROM employees WHERE name IN ($placeholders)");
        $stmt->execute($names);
        $map = [];
        while ($row = $stmt->fetch()) {
            if (!empty($row['photo'])) {
                $map[$row['name']] = $row['photo'];
            }
        }
        return $map;
    }

    public static function count()
    {
        $pdo = self::pdo();
        $stmt = $pdo->query('SELECT COUNT(*) as c FROM employees');
        $row = $stmt->fetch();
        return (int)($row['c'] ?? 0);
    }

    public static function create(string $nip, string $name, ?string $position, string $category, ?string $photo = null): void
    {
        $pdo = self::pdo();
        $nip = trim($nip);
        $name = trim($name);
        if ($nip === '' || $name === '') {
            throw new \RuntimeException(__('nip_name_category_required'));
        }
        if (!in_array($category, ['PNS', 'PPPK', 'PPPK PW'], true)) {
            throw new \RuntimeException(__('category_invalid'));
        }
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare('INSERT INTO employees (nip, name, photo, position, category, created_at, updated_at) VALUES (:nip, :name, :photo, :position, :category, :created_at, :updated_at)');
        $stmt->execute([
            'nip' => $nip,
            'name' => $name,
            'photo' => $photo,
            'position' => $position !== null && trim($position) !== '' ? trim($position) : null,
            'category' => $category,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Update employee. Renames presence_logs rows when name changes.
     * $newUploadPath: if set, replaces photo with uploaded file path.
     * $removePhoto: if true and no new upload, clears photo (and deletes local file).
     */
    public static function update(int $id, string $nip, string $name, ?string $position, string $category, ?string $newUploadPath, bool $removePhoto): void
    {
        $pdo = self::pdo();
        $row = self::findById($id);
        if (!$row) {
            throw new \RuntimeException(__('employee_not_found'));
        }
        $oldName = $row['name'];
        $oldPhoto = $row['photo'];

        $nip = trim($nip);
        $name = trim($name);
        if ($nip === '' || $name === '') {
            throw new \RuntimeException(__('nip_name_category_required'));
        }
        if (!in_array($category, ['PNS', 'PPPK', 'PPPK PW'], true)) {
            throw new \RuntimeException(__('category_invalid'));
        }

        if (strcasecmp($name, $oldName) !== 0) {
            $check = $pdo->prepare('SELECT id FROM employees WHERE name = :name AND id <> :id LIMIT 1');
            $check->execute(['name' => $name, 'id' => $id]);
            if ($check->fetch()) {
                throw new \RuntimeException(__('name_duplicate'));
            }
        }

        $checkNip = $pdo->prepare('SELECT id FROM employees WHERE nip = :nip AND id <> :id LIMIT 1');
        $checkNip->execute(['nip' => $nip, 'id' => $id]);
        if ($checkNip->fetch()) {
            throw new \RuntimeException(__('nip_duplicate'));
        }

        $newPhoto = $oldPhoto;
        if ($newUploadPath) {
            self::deleteLocalPhotoFile($oldPhoto);
            $newPhoto = $newUploadPath;
        } elseif ($removePhoto) {
            self::deleteLocalPhotoFile($oldPhoto);
            $newPhoto = null;
        }

        $now = date('Y-m-d H:i:s');
        $pdo->beginTransaction();
        try {
            if ($name !== $oldName) {
                $u = $pdo->prepare('UPDATE presence_logs SET employee_name = :new WHERE employee_name = :old');
                $u->execute(['new' => $name, 'old' => $oldName]);
            }
            $stmt = $pdo->prepare('UPDATE employees SET nip = :nip, name = :name, photo = :photo, position = :position, category = :category, updated_at = :u WHERE id = :id');
            $stmt->execute([
                'nip' => $nip,
                'name' => $name,
                'photo' => $newPhoto,
                'position' => $position !== null && trim($position) !== '' ? trim($position) : null,
                'category' => $category,
                'u' => $now,
                'id' => $id,
            ]);
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /** Remove file under public/ if path is a local upload. */
    public static function deleteLocalPhotoFile(?string $photo): void
    {
        if ($photo === null || $photo === '') {
            return;
        }
        if (strpos($photo, '/uploads/employees/') !== 0) {
            return;
        }
        $root = dirname(__DIR__, 2) . '/public';
        $path = $root . $photo;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    public static function deleteById(int $id)
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT name FROM employees WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) {
            return false;
        }

        $name = $row['name'];
        self::deleteLocalPhotoFile($row['photo'] ?? null);

        $delEmployee = $pdo->prepare('DELETE FROM employees WHERE id = :id');
        $delEmployee->execute(['id' => $id]);

        $delPresence = $pdo->prepare('DELETE FROM presence_logs WHERE employee_name = :name');
        $delPresence->execute(['name' => $name]);
        return true;
    }

    /**
     * Import upsert keyed by nip (spreadsheet imports). Keeps presence_logs.employee_name in sync when name changes.
     *
     * @return bool false when the row could not be applied (e.g. duplicate name on another record)
     */
    public static function upsertByNip(string $nip, string $name, ?string $position, string $category): bool
    {
        $pdo = self::pdo();
        $nip = trim($nip);
        $name = trim($name);
        if ($nip === '' || $name === '') {
            return false;
        }

        $stmt = $pdo->prepare('SELECT id, name FROM employees WHERE nip = :nip LIMIT 1');
        $stmt->execute(['nip' => $nip]);
        $existing = $stmt->fetch();

        if ($existing) {
            return self::applyImportedUpdate(
                $pdo,
                (int) $existing['id'],
                (string) $existing['name'],
                $name,
                $position,
                $category
            );
        }

        $now = date('Y-m-d H:i:s');
        try {
            $ins = $pdo->prepare('INSERT INTO employees (nip, name, photo, position, category, created_at, updated_at)
                VALUES (:nip, :name, NULL, :position, :category, :c, :u)');
            $ins->execute([
                'nip' => $nip,
                'name' => $name,
                'position' => $position,
                'category' => $category,
                'c' => $now,
                'u' => $now,
            ]);
        } catch (\PDOException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                return false;
            }
            throw $e;
        }

        return true;
    }

    /**
     * @return bool false if another employee already uses this name
     */
    private static function applyImportedUpdate(\PDO $pdo, int $id, string $oldName, string $newName, ?string $position, string $category): bool
    {
        if (strcasecmp($newName, $oldName) !== 0) {
            $check = $pdo->prepare('SELECT id FROM employees WHERE name = :name AND id <> :id LIMIT 1');
            $check->execute(['name' => $newName, 'id' => $id]);
            if ($check->fetch()) {
                return false;
            }
        }

        $now = date('Y-m-d H:i:s');
        $pdo->beginTransaction();
        try {
            if ($newName !== $oldName) {
                $u = $pdo->prepare('UPDATE presence_logs SET employee_name = :new WHERE employee_name = :old');
                $u->execute(['new' => $newName, 'old' => $oldName]);
            }
            $stmt = $pdo->prepare('UPDATE employees SET name = :name, position = :position, category = :category, updated_at = :u WHERE id = :id');
            $stmt->execute([
                'name' => $newName,
                'position' => $position,
                'category' => $category,
                'u' => $now,
                'id' => $id,
            ]);
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        return true;
    }
}
