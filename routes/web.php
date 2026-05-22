<?php

// Minimal router for this lightweight app. Returns a callable used by public/index.php
use App\Http\Kernel;
use App\Http\Middleware\AdminAuth;
use App\Models\Employee;
use App\Models\PresenceLog;
use App\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeController;

require_once dirname(__DIR__) . '/bootstrap/admin_upload.php';
require_once dirname(__DIR__) . '/bootstrap/absence_proof_upload.php';

return function() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // normalize
    $path = rtrim($path, '/') ?: '/';
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $requestUri = $path . ($queryString !== '' ? ('?' . $queryString) : '');

    $isAdminAuthenticated = AdminAuth::check();

    /**
     * Route::middleware(['admin.auth'])->prefix('admin')->group(...)
     * All /admin/* (except /admin/login) and /admin-panel/* management paths.
     */
    $enforceAdminAuth = static function () use ($path, $requestUri): bool {
        if (! path_requires_admin_auth($path)) {
            return false;
        }

        return Kernel::runMiddleware(Kernel::adminMiddleware(), $requestUri);
    };

    if ($path === '/admin/locale' && ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['locale']))) {
        $locale = (string) ($_POST['locale'] ?? $_GET['locale'] ?? '');
        set_app_locale($locale);
        $redirect = locale_safe_redirect((string) ($_POST['redirect'] ?? $_GET['redirect'] ?? '/admin-panel'));
        header('Location: ' . $redirect);

        return '';
    }

    // handle POST actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($path === '/admin/login') {
            $password = (string)($_POST['password'] ?? '');
            $redirect = (string)($_POST['redirect'] ?? '/admin/employees');
            if ($redirect === '' || strpos($redirect, '/') !== 0 || strpos($redirect, '//') === 0) {
                $redirect = '/admin/employees';
            }
            try {
                $expected = admin_panel_password();
            } catch (RuntimeException $e) {
                header('Location: /admin/login?error=' . urlencode($e->getMessage()) . '&redirect=' . rawurlencode($redirect));
                return '';
            }
            if (hash_equals($expected, $password)) {
                $_SESSION[AdminAuth::SESSION_KEY] = true;
                session_regenerate_id(true);
                header('Location: ' . $redirect);
                return '';
            }
            header('Location: /admin/login?error=' . urlencode(__('invalid_password')) . '&redirect=' . rawurlencode($redirect));
            return '';
        }

        if ($enforceAdminAuth()) {
            return '';
        }

        if ($path === '/input-form' || $path === '/mobile-mode') {
            $employee = $_POST['employee_name'] ?? null;
            $status = $_POST['status'] ?? null;
            $location = trim($_POST['location'] ?? '');
            $note = $_POST['note'] ?? null;
            $logDate = $_POST['log_date'] ?? date('Y-m-d');
            // Submitted-at time is always server time when the form is posted (no manual edit).
            $logTime = date('H:i:s');
            $startTime = $_POST['start_time'] ?? null;
            $endTime = $_POST['end_time'] ?? null;
            $isTimeGated = ($status === 'Izin Keluar');
            $hasRequiredTimeWindow = !$isTimeGated || ($startTime && $endTime);
            $proofAllowed = in_array($status, absence_proof_allowed_statuses(), true);
            $proofFile = $_FILES['proof'] ?? null;
            $proofHasUpload = is_array($proofFile)
                && (int) ($proofFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE
                && (int) ($proofFile['error'] ?? 0) === UPLOAD_ERR_OK;
            if (! $proofAllowed && $proofHasUpload) {
                header('Location: /input-form?error=' . rawurlencode('Bukti hanya untuk status Dinas Luar, Sakit, atau Cuti Tahunan.'));
                return '';
            }
            if ($employee && $status && $location && $logDate && $hasRequiredTimeWindow) {
                $attachmentPath = null;
                if ($proofAllowed) {
                    try {
                        $attachmentPath = process_absence_proof_upload($proofFile);
                    } catch (Exception $e) {
                        header('Location: /input-form?error=' . urlencode($e->getMessage()));
                        return '';
                    }
                }
                try {
                    PresenceLog::createLog($employee, [
                        'status' => $status,
                        'location' => $location,
                        'note' => $note,
                        'log_date' => $logDate,
                        'log_time' => $logTime,
                        'start_time' => $isTimeGated ? ($startTime . ':00') : null,
                        'end_time' => $isTimeGated ? ($endTime . ':00') : null,
                        'attachment_path' => $attachmentPath,
                    ]);
                    header('Location: /input-form/thanks');
                    return '';
                } catch (Exception $e) {
                    if ($attachmentPath !== null && $attachmentPath !== '') {
                        $absRm = absence_proof_absolute_path($attachmentPath);
                        if ($absRm !== null && is_file($absRm)) {
                            @unlink($absRm);
                        }
                    }
                    header('Location: /input-form?error=' . urlencode($e->getMessage()));
                    return '';
                }
            }
            header('Location: /input-form?error=' . rawurlencode(__('complete_required')));
            return '';
        }

        if ($path === '/admin-panel/employees/import') {
            if (! isset($_FILES['import_file'])) {
                header('Location: /admin/employees?error=' . rawurlencode(__('no_file_uploaded')));
                return '';
            }
            try {
                $count = EmployeeController::importFromSpreadsheetUpload($_FILES['import_file']);
                $msg = __('imported_count', ['n' => (string) $count]);
                header('Location: /admin/employees?success=' . rawurlencode($msg));
            } catch (Exception $e) {
                header('Location: /admin/employees?error=' . urlencode($e->getMessage()));
            }
            return '';
        }

        if ($path === '/admin-panel/employee') {
            $name = trim($_POST['name'] ?? '');
            $nip = ltrim(trim((string) ($_POST['nip'] ?? '')), "'");
            $position = trim((string) ($_POST['position'] ?? ''));
            $category = trim((string) ($_POST['category'] ?? ''));
            if ($name === '' || $nip === '') {
                header('Location: /admin-panel/employees/new?error=' . rawurlencode(__('nip_name_required')));
                return '';
            }
            try {
                $photoPath = process_employee_photo_upload($_FILES['photo'] ?? null);
                Employee::create($nip, $name, $position === '' ? null : $position, $category, $photoPath);
                header('Location: /admin/employees?success=' . rawurlencode(__('employee_added')));
            } catch (Exception $e) {
                header('Location: /admin-panel/employees/new?error=' . urlencode($e->getMessage()));
            }
            return '';
        }

        if ($path === '/admin-panel/employee/update') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $nip = ltrim(trim((string) ($_POST['nip'] ?? '')), "'");
            $position = trim((string) ($_POST['position'] ?? ''));
            $category = trim((string) ($_POST['category'] ?? ''));
            $removePhoto = !empty($_POST['remove_photo']);
            if ($id <= 0 || $name === '' || $nip === '') {
                header('Location: /admin/employees?error=' . rawurlencode(__('invalid_update')));
                return '';
            }
            try {
                $upload = process_employee_photo_upload($_FILES['photo'] ?? null);
                Employee::update($id, $nip, $name, $position === '' ? null : $position, $category, $upload, $removePhoto && !$upload);
                header('Location: /admin/employees?success=' . rawurlencode(__('employee_updated')));
            } catch (Exception $e) {
                header('Location: /admin-panel/employees/edit?id=' . $id . '&error=' . urlencode($e->getMessage()));
            }
            return '';
        }

        if ($path === '/admin-panel/employee/delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                Employee::deleteById($id);
            }
            header('Location: /admin/employees?success=' . rawurlencode(__('employee_removed')));
            return '';
        }
    }

    // GET routes
    if ($path === '/admin/login') {
        if ($isAdminAuthenticated) {
            header('Location: /admin/employees');
            return '';
        }
        $redirect = (string)($_GET['redirect'] ?? '/admin/employees');
        if ($redirect === '' || strpos($redirect, '/') !== 0 || strpos($redirect, '//') === 0) {
            $redirect = '/admin/employees';
        }
        ob_start();
        include dirname(__DIR__) . '/resources/views/admin-auth.php';
        return ob_get_clean();
    }

    if ($path === '/admin/logout') {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: /admin/login?success=' . urlencode(__('logged_out')));
        return '';
    }

    if ($enforceAdminAuth()) {
        return '';
    }

    // --- Public routes (no admin.auth middleware) ---

    if ($path === '/') {
        ob_start();
        include dirname(__DIR__) . '/resources/views/home.php';
        return ob_get_clean();
    }

    if ($path === '/display-mode' || $path === '/tv-mode') {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $statusFilter = trim($_GET['status'] ?? '');
        $total = PresenceLog::countEmployees();
        // Everyone is treated as in-office by default; only employees with at least one log on this day count as not in office (no check-in/out yet).
        $notIn = PresenceLog::uniqueEmployeesForDate($selectedDate);
        $inOffice = max(0, $total - $notIn);
        $logs = $statusFilter !== ''
            ? PresenceLog::logsByDateAndStatus($selectedDate, $statusFilter)
            : PresenceLog::logsByDate($selectedDate);
        // Use current employee profile photo; presence_logs.photo is often empty on older rows.
        if (!empty($logs)) {
            $names = array_column($logs, 'employee_name');
            $photoMap = Employee::photosByNames($names);
            foreach ($logs as &$log) {
                $n = $log['employee_name'] ?? '';
                if ($n !== '' && !empty($photoMap[$n])) {
                    $log['photo'] = $photoMap[$n];
                }
            }
            unset($log);
        }
        $dayTs = strtotime($selectedDate . ' 12:00:00') ?: time();
        $prevDate = date('Y-m-d', strtotime('-1 day', $dayTs));
        $nextDate = date('Y-m-d', strtotime('+1 day', $dayTs));
        $filterStatuses = ['Izin Keluar', 'WFH', 'Sakit', 'Cuti Tahunan', 'Dinas Luar'];
        ob_start();
        include dirname(__DIR__) . '/resources/views/tv-dashboard.php';
        return ob_get_clean();
    }

    if ($path === '/input-form' || $path === '/mobile-mode') {
        $employees = PresenceLog::distinctEmployees();
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $statuses = ['Izin Keluar', 'Sakit', 'Cuti Tahunan', 'WFH', 'Dinas Luar'];
        ob_start();
        include dirname(__DIR__) . '/resources/views/mobile-mode.php';
        return ob_get_clean();
    }

    if ($path === '/input-form/thanks') {
        ob_start();
        include dirname(__DIR__) . '/resources/views/input-thanks.php';
        return ob_get_clean();
    }

    // --- Admin routes (protected by admin.auth middleware above) ---

    if ($path === '/admin-panel') {
        $today = date('Y-m-d');
        $totalEmployees = Employee::count();
        $employeesOutToday = PresenceLog::uniqueEmployeesForDate($today);
        $employeesInToday = max(0, $totalEmployees - $employeesOutToday);
        $weekly = AdminDashboardController::buildAnalyticsData($today);
        $last7AbsenceTotal = array_sum($weekly['weeklyCounts'] ?? []);
        ob_start();
        include dirname(__DIR__) . '/resources/views/admin-home.php';
        return ob_get_clean();
    }

    if ($path === '/admin/dashboard') {
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $analytics = AdminDashboardController::buildAnalyticsData($selectedDate);
        ob_start();
        include dirname(__DIR__) . '/resources/views/admin-dashboard.php';
        return ob_get_clean();
    }

    if ($path === '/admin/presence-proof') {
        $id = (int) ($_GET['id'] ?? 0);
        $row = PresenceLog::findById($id);
        $rel = trim((string) ($row['attachment_path'] ?? ''));
        if (! $row || $rel === '') {
            http_response_code(404);
            return '<!doctype html><html lang="' . htmlspecialchars(app_lang_attr()) . '"><body><h1>404</h1><p>' . htmlspecialchars(__('proof_not_found')) . '</p><p><a href="/admin/employees">' . htmlspecialchars(__('employees')) . '</a></p></body></html>';
        }
        $abs = absence_proof_absolute_path($rel);
        if ($abs === null) {
            http_response_code(404);
            return '<!doctype html><html lang="' . htmlspecialchars(app_lang_attr()) . '"><body><h1>404</h1><p>' . htmlspecialchars(__('file_missing')) . '</p></body></html>';
        }
        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'application/octet-stream',
        };
        $download = isset($_GET['download']);
        header('Content-Type: ' . $mime);
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=0');
        $fn = 'absence-proof-' . $id . '.' . $ext;
        header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . $fn . '"');
        header('Content-Length: ' . (string) filesize($abs));
        readfile($abs);
        exit;
    }

    if (preg_match('#^/admin/employees/([0-9]+)$#', $path, $m)) {
        $show = EmployeeController::buildShow((int) $m[1]);
        if ($show === null) {
            http_response_code(404);
            return '<!doctype html><html lang="' . htmlspecialchars(app_lang_attr()) . '"><body><h1>404</h1><p>' . htmlspecialchars(__('employee_not_found')) . '</p><p><a href="/admin/employees">' . htmlspecialchars(__('back_directory')) . '</a></p></body></html>';
        }
        ob_start();
        include dirname(__DIR__) . '/resources/views/employees/show.blade.php';
        return ob_get_clean();
    }

    if ($path === '/admin/employees') {
        $dir = EmployeeController::buildList($_GET);
        ob_start();
        include dirname(__DIR__) . '/resources/views/employees/index.blade.php';
        return ob_get_clean();
    }

    if ($path === '/admin-panel/employees') {
        $q = $_SERVER['QUERY_STRING'] ?? '';
        $to = '/admin/employees' . ($q !== '' ? '?' . $q : '');
        header('Location: ' . $to, true, 302);
        return '';
    }

    if ($path === '/admin-panel/employees/new') {
        $adminPage = 'new';
        $editEmployee = null;
        ob_start();
        include dirname(__DIR__) . '/resources/views/admin-panel.php';
        return ob_get_clean();
    }

    if ($path === '/admin-panel/employees/edit') {
        $adminPage = 'edit';
        $editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $editEmployee = ($editId > 0) ? Employee::findById($editId) : null;
        if ($editId <= 0 || !$editEmployee) {
            header('Location: /admin/employees?error=' . urlencode(__('employee_not_found')));
            return '';
        }
        ob_start();
        include dirname(__DIR__) . '/resources/views/admin-panel.php';
        return ob_get_clean();
    }

    // fallback: 404
    http_response_code(404);
    return '<h1>404 ' . htmlspecialchars(__('not_found')) . '</h1>';
};

