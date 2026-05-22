<?php

const APP_LOCALE_SESSION_KEY = 'app_locale';
const APP_LOCALE_COOKIE = 'pm_locale';

/**
 * Supported UI locales.
 *
 * @return list<string>
 */
function app_locales(): array
{
    return ['en', 'id'];
}

function app_locale(): string
{
    $locale = $_SESSION[APP_LOCALE_SESSION_KEY] ?? $_COOKIE[APP_LOCALE_COOKIE] ?? 'id';

    return in_array($locale, app_locales(), true) ? $locale : 'id';
}

function set_app_locale(string $locale): void
{
    if (! in_array($locale, app_locales(), true)) {
        return;
    }

    $_SESSION[APP_LOCALE_SESSION_KEY] = $locale;

    if (! headers_sent()) {
        setcookie(APP_LOCALE_COOKIE, $locale, [
            'expires' => time() + 365 * 24 * 60 * 60,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

/**
 * Restore locale from cookie into session (call after session_start).
 */
function locale_init(): void
{
    if (! empty($_SESSION[APP_LOCALE_SESSION_KEY]) && in_array($_SESSION[APP_LOCALE_SESSION_KEY], app_locales(), true)) {
        return;
    }
    if (! empty($_COOKIE[APP_LOCALE_COOKIE]) && in_array($_COOKIE[APP_LOCALE_COOKIE], app_locales(), true)) {
        $_SESSION[APP_LOCALE_SESSION_KEY] = $_COOKIE[APP_LOCALE_COOKIE];
    }
}

function app_lang_attr(): string
{
    return app_locale() === 'en' ? 'en' : 'id';
}

/**
 * UI translation for the active locale.
 */
function __(string $key, array $replace = []): string
{
    static $cache = [];
    $locale = app_locale();

    if (! isset($cache[$locale])) {
        $path = dirname(__DIR__) . '/resources/lang/' . $locale . '.php';
        if (! is_file($path)) {
            $path = dirname(__DIR__) . '/resources/lang/id.php';
        }
        $cache[$locale] = is_file($path) ? require $path : [];
    }

    $text = $cache[$locale][$key] ?? $key;
    foreach ($replace as $name => $value) {
        $text = str_replace(':' . $name, (string) $value, $text);
    }

    return $text;
}

function ui_status_label(string $status): string
{
    $map = [
        'In Office' => __('status.in_office'),
        'Izin Keluar' => 'Izin Keluar',
        'Izin' => __('status.izin'),
    ];

    return $map[$status] ?? $status;
}

/**
 * Safe redirect target: same-site path only.
 */
function locale_safe_redirect(string $redirect, string $fallback = '/admin-panel'): string
{
    $redirect = trim($redirect);
    if ($redirect === '' || $redirect[0] !== '/' || str_starts_with($redirect, '//')) {
        return $fallback;
    }

    return $redirect;
}

/**
 * Lightweight config() helper (Laravel-compatible dot keys for app.*).
 */
function config(string $key, mixed $default = null): mixed
{
    static $loaded = false;
    static $config = [];

    if (! $loaded) {
        $appPath = dirname(__DIR__) . '/config/app.php';
        $config['app'] = is_file($appPath) ? require $appPath : [];
        $loaded = true;
    }

    if (! str_contains($key, '.')) {
        return $config[$key] ?? $default;
    }

    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (! is_array($value) || ! array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function admin_panel_password(): string
{
    $password = config('app.admin_panel_password');
    if ($password === null || $password === '') {
        throw new RuntimeException('ADMIN_PANEL_PASSWORD is not set. Add it to your .env file.');
    }

    return (string) $password;
}

function path_requires_admin_auth(string $path): bool
{
    if ($path === '/admin/login' || $path === '/admin/locale') {
        return false;
    }
    if (str_starts_with($path, '/admin/')) {
        return true;
    }
    if (str_starts_with($path, '/admin-panel')) {
        return true;
    }

    return false;
}

function flash_put(string $key, string $message): void
{
    $_SESSION[$key] = $message;
}

function flash_pull(string $key): ?string
{
    if (empty($_SESSION[$key])) {
        return null;
    }
    $msg = (string) $_SESSION[$key];
    unset($_SESSION[$key]);

    return $msg;
}
