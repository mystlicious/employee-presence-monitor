<?php

namespace App\Http;

use App\Http\Middleware\AdminAuth;

/**
 * Middleware registry (Laravel-compatible aliases for this lightweight router).
 */
class Kernel
{
    /** @var array<string, class-string> */
    public const ROUTE_MIDDLEWARE = [
        'admin.auth' => AdminAuth::class,
    ];

    /**
     * Run middleware stack. Returns true when a middleware halted the request.
     *
     * @param list<string> $middleware
     */
    public static function runMiddleware(array $middleware, string $requestUri): bool
    {
        foreach ($middleware as $alias) {
            if ($alias === 'admin.auth') {
                if (AdminAuth::handle($requestUri)) {
                    return true;
                }
                continue;
            }
            throw new \InvalidArgumentException("Unknown middleware alias: {$alias}");
        }

        return false;
    }

    /** @return list<string> */
    public static function adminMiddleware(): array
    {
        return ['admin.auth'];
    }
}
