<?php

$healthPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($healthPath === '/healthz') {
	http_response_code(200);
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'ok';
	exit;
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/helpers.php';
$app = require __DIR__ . '/../bootstrap/app.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
locale_init();

// If bootstrap recorded a PDO error, show it and stop for easier debugging
if (!empty($app['pdo_error'])) {
	http_response_code(500);
	$dbHost = function_exists('env_value') ? env_value('DB_HOST') : (getenv('DB_HOST') ?: '');
	$dbPort = function_exists('env_value') ? env_value('DB_PORT', '3306') : (getenv('DB_PORT') ?: '3306');
	$sslCa = function_exists('env_value') ? env_value('MYSQL_ATTR_SSL_CA') : (getenv('MYSQL_ATTR_SSL_CA') ?: '');
	echo "<h1>Database initialization error</h1>\n";
	echo "<pre>" . htmlspecialchars($app['pdo_error']) . "</pre>\n";
	echo "<p><strong>Configured target:</strong> "
		. htmlspecialchars($dbHost !== '' ? "{$dbHost}:{$dbPort}" : '(DB_HOST not set — defaults to 127.0.0.1)')
		. "</p>\n";
	echo "<p><strong>SSL CA:</strong> "
		. htmlspecialchars($sslCa !== '' ? $sslCa : '(not set — Aiven requires AIVEN_CA_PEM or MYSQL_ATTR_SSL_CA)')
		. "</p>\n";
	echo "<p>On Railway, set variables on this web service (not only locally in .env), then redeploy.</p>";
	exit(1);
}

// Execute the route callable and print response
$response = require __DIR__ . '/../routes/web.php';
echo $response();
