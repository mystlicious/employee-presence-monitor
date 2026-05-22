<?php
// Minimal bootstrap: load .env and create a PDO connection available via $app['pdo']
$app = [];

/**
 * Parse a single .env value (strip surrounding quotes like Laravel/Dotenv).
 */
function parse_env_value(string $value): string
{
	$value = trim($value);
	if ($value === '') {
		return '';
	}
	if ((str_starts_with($value, '"') && str_ends_with($value, '"'))
		|| (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
		$inner = substr($value, 1, -1);
		if ($value[0] === '"') {
			return str_replace(['\\n', '\\r', '\\t', '\\"', '\\\\'], ["\n", "\r", "\t", '"', '\\'], $inner);
		}

		return $inner;
	}

	return $value;
}

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
	$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (strpos(trim($line), '#') === 0) continue;
		if (!str_contains($line, '=')) continue;
		[$k, $v] = explode('=', $line, 2);
		$k = trim($k);
		$v = parse_env_value($v);
		putenv("{$k}={$v}");
		$_ENV[$k] = $v;
	}
}

// Keep all server-side timestamps consistent for submitted/log times.
// Default to GMT+8; can still be overridden via APP_TIMEZONE in .env.
$timezone = getenv('APP_TIMEZONE') ?: 'Asia/Singapore';
date_default_timezone_set($timezone);

$driver = getenv('DB_CONNECTION') ?: 'mysql';

try {
	if ($driver !== 'mysql') {
		throw new RuntimeException('Only MySQL is supported. Set DB_CONNECTION=mysql in .env');
	}

	$host = getenv('DB_HOST') ?: '127.0.0.1';
	$port = getenv('DB_PORT') ?: '3306';
	$db = getenv('DB_DATABASE') ?: '';
	$user = getenv('DB_USERNAME') ?: '';
	$pass = getenv('DB_PASSWORD') ?: '';
	$charset = getenv('DB_CHARSET') ?: 'utf8mb4';
	$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
	try {
		$pdo = new PDO($dsn, $user, $pass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		]);
	} catch (PDOException $e) {
		// If database is missing, create it automatically and retry once.
		if (str_contains($e->getMessage(), 'Unknown database')) {
			$serverPdo = new PDO("mysql:host={$host};port={$port};charset={$charset}", $user, $pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			]);
			$serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET {$charset} COLLATE utf8mb4_unicode_ci");
			$pdo = new PDO($dsn, $user, $pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			]);
		} else {
			throw $e;
		}
	}
} catch (Exception $e) {
	// expose $app for error display later
	$app['pdo_error'] = $e->getMessage();
	$app['pdo'] = null;
	return $app;
}

$app['pdo'] = $pdo;

// also expose globally for simple access
$GLOBALS['APP_PDO'] = $pdo;

// ensure base tables exist (idempotent)
$pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS employees (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL UNIQUE,
	photo TEXT NULL,
	created_at DATETIME NULL,
	updated_at DATETIME NULL
);
SQL
);

try { $pdo->exec('ALTER TABLE employees ADD COLUMN nip VARCHAR(64) NULL'); } catch (Exception $e) {}
try { $pdo->exec('ALTER TABLE employees ADD COLUMN position VARCHAR(512) NULL'); } catch (Exception $e) {}
try { $pdo->exec('ALTER TABLE employees ADD COLUMN category VARCHAR(32) NULL'); } catch (Exception $e) {}
try { $pdo->exec('ALTER TABLE employees DROP COLUMN division'); } catch (Exception $e) {}
try {
	$pdo->exec('CREATE UNIQUE INDEX employees_nip_unique ON employees (nip)');
} catch (Exception $e) {
	/* index exists or incompatible rows */
}

$pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS presence_logs (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	employee_name VARCHAR(255) NOT NULL,
	photo TEXT NULL,
	status VARCHAR(100) NULL,
	location VARCHAR(255) NULL,
	note TEXT NULL,
	log_date DATE NULL,
	log_time TIME NULL,
	start_time TIME NULL,
	end_time TIME NULL,
	attachment_path VARCHAR(512) NULL,
	is_in_office TINYINT(1) DEFAULT 0,
	updated_at DATETIME NULL,
	created_at DATETIME NULL
);
SQL
);

// Add new logging columns for older databases (safe if already present).
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN location VARCHAR(255) NULL"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN log_date DATE NULL"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN log_time TIME NULL"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN start_time TIME NULL"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN end_time TIME NULL"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE presence_logs ADD COLUMN attachment_path VARCHAR(512) NULL"); } catch (Exception $e) {}

// Remove unique constraint on employee_name so each submission becomes a log row.
try {
	$idxStmt = $pdo->query("SHOW INDEX FROM presence_logs WHERE Column_name = 'employee_name' AND Non_unique = 0");
	$indexes = $idxStmt ? $idxStmt->fetchAll() : [];
	foreach ($indexes as $index) {
		$keyName = $index['Key_name'] ?? '';
		if ($keyName !== '' && $keyName !== 'PRIMARY') {
			$pdo->exec("ALTER TABLE presence_logs DROP INDEX `{$keyName}`");
		}
	}
} catch (Exception $e) {}

return $app;
