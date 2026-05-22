<?php

namespace App\Http\Middleware;

/**
 * Ensures an active admin session exists before protected routes execute.
 */
class AdminAuth
{
    public const SESSION_KEY = 'admin_authenticated';

    public static function check(): bool
    {
        return ! empty($_SESSION[self::SESSION_KEY]);
    }

    /**
     * @return bool True when the request was intercepted (redirect issued).
     */
    public static function handle(string $requestUri): bool
    {
        if (self::check()) {
            return false;
        }

        flash_put('flash_error', __('auth_required'));

        $redirect = '/admin/login';
        if ($requestUri !== '' && str_starts_with($requestUri, '/') && ! str_starts_with($requestUri, '//')) {
            $redirect .= '?redirect=' . rawurlencode($requestUri);
        }

        header('Location: ' . $redirect);

        return true;
    }
}
