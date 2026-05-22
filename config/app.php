<?php

return [
    'name' => getenv('APP_NAME') ?: 'AttendanceWeb',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => getenv('APP_DEBUG') !== 'false',
    /** Master admin password — set ADMIN_PANEL_PASSWORD in .env (never commit real values). */
    'admin_panel_password' => $_ENV['ADMIN_PANEL_PASSWORD'] ?? getenv('ADMIN_PANEL_PASSWORD') ?: '',
];
