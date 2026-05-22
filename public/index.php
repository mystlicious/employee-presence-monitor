<?php
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
	echo "<h1>Database initialization error</h1>\n";
	echo "<pre>" . htmlspecialchars($app['pdo_error']) . "</pre>\n";
	echo "<p>Check your DB settings in .env and ensure PDO extensions are enabled (pdo, pdo_mysql for MySQL).</p>";
	exit(1);
}

// Execute the route callable and print response
$response = require __DIR__ . '/../routes/web.php';
echo $response();
