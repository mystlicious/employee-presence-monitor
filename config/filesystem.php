<?php

/**
 * Disk configuration (Laravel-style) for secure document storage.
 * This app does not ship Laravel's Storage facade; paths are read here by
 * bootstrap/absence_proof_upload.php and admin proof routes in routes/web.php.
 */
$base = dirname(__DIR__);

return [
    'default' => 'local',

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => $base . '/storage/app',
        ],

        /** Private: not exposed under /public; served only via authenticated admin routes. */
        'absence_proofs' => [
            'driver' => 'local',
            'root' => $base . '/storage/app/absence_proofs',
            'visibility' => 'private',
        ],
    ],
];
