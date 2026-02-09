<?php
// Include security configuration first (headers, session, error handling)
require_once __DIR__ . '/security_config.php';

// Load simple .env if present (KEY=VALUE lines)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
	$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (strpos(trim($line), '#') === 0) continue;
		[$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => '']);
		if ($k !== '') {
			putenv("$k=$v");
			$_ENV[$k] = $v;
			$_SERVER[$k] = $v;
		}
	}
}

$serverName = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'amvrss';

$dbh = mysqli_connect($serverName, $username, $password, $dbname);
if (!$dbh) {
	error_log('Database connection failed: ' . mysqli_connect_error());
	die('Database connection failed. Check configuration.');
}

// Include helpers after database connection is established
require_once __DIR__ . '/helpers.php';