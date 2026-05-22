<?php

/**
 * Absence proof uploads: PDF, JPEG, PNG only; max 5MB; stored outside public web root.
 *
 * @throws RuntimeException
 */
function absence_proof_disk_root(): string
{
    static $root;
    if ($root !== null) {
        return $root;
    }
    $cfg = require dirname(__DIR__) . '/config/filesystem.php';
    $root = (string) ($cfg['disks']['absence_proofs']['root'] ?? (dirname(__DIR__) . '/storage/app/absence_proofs'));
    if (! is_dir($root)) {
        if (! mkdir($root, 0755, true) && ! is_dir($root)) {
            throw new RuntimeException('Could not create absence proof storage directory.');
        }
    }

    return $root;
}

/**
 * Resolve a stored relative path to an absolute file path, or null if invalid / missing.
 */
function absence_proof_absolute_path(string $relative): ?string
{
    $diskRoot = realpath(absence_proof_disk_root());
    if ($diskRoot === false) {
        return null;
    }
    $rel = str_replace('\\', '/', trim($relative));
    if ($rel === '' || str_contains($rel, '..')) {
        return null;
    }
    $full = $diskRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    $real = realpath($full);
    if ($real === false || ! is_file($real)) {
        return null;
    }
    $diskNorm = rtrim(str_replace('\\', '/', $diskRoot), '/');
    $realNorm = str_replace('\\', '/', $real);
    if (! str_starts_with($realNorm, $diskNorm)) {
        return null;
    }

    return $real;
}

/** Statuses for which the UI offers proof upload (optional). */
function absence_proof_allowed_statuses(): array
{
    return ['Dinas Luar', 'Sakit', 'Cuti Tahunan'];
}

/**
 * @param array<string, mixed>|null $file $_FILES['proof'] or null
 */
function process_absence_proof_upload(?array $file): ?string
{
    if ($file === null || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ((int) ($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
        throw new RuntimeException(__('upload_failed'));
    }
    $maxBytes = 5 * 1024 * 1024;
    if ((int) ($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException(__('proof_too_large'));
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || ! is_uploaded_file($tmp)) {
        throw new RuntimeException(__('upload_invalid'));
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];
    if (! isset($map[$mime])) {
        throw new RuntimeException(__('proof_type_error'));
    }
    $ext = $map[$mime];

    $subdir = date('Y/m');
    $destDir = absence_proof_disk_root() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subdir);
    if (! is_dir($destDir)) {
        if (! mkdir($destDir, 0755, true) && ! is_dir($destDir)) {
            throw new RuntimeException('Could not create upload subdirectory.');
        }
    }

    $basename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dest = $destDir . DIRECTORY_SEPARATOR . $basename;
    if (! move_uploaded_file($tmp, $dest)) {
        throw new RuntimeException(__('proof_save_failed'));
    }

    return $subdir . '/' . $basename;
}
