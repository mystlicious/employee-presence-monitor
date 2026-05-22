<?php

/**
 * Ensure public/uploads/employees exists; process one optional image upload.
 * Returns web path like /uploads/employees/abc.jpg or null if no file.
 *
 * @throws RuntimeException on validation / move failure
 */
function process_employee_photo_upload(?array $file): ?string
{
    if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
        throw new RuntimeException(__('photo_upload_failed'));
    }
    $maxBytes = 2 * 1024 * 1024;
    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException(__('photo_too_large'));
    }
    $tmp = $file['tmp_name'] ?? '';
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        throw new RuntimeException(__('upload_invalid'));
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($map[$mime])) {
        throw new RuntimeException(__('photo_type_error'));
    }
    $ext = $map[$mime];

    $publicRoot = dirname(__DIR__) . '/public';
    $dir = $publicRoot . '/uploads/employees';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException('Could not create upload directory.');
        }
    }

    $basename = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $dir . '/' . $basename;
    if (!move_uploaded_file($tmp, $dest)) {
        throw new RuntimeException(__('photo_save_failed'));
    }

    return '/uploads/employees/' . $basename;
}
