<?php

namespace TrackAnyDevice\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hard-aborts with 404 when the Filament admin panel is requested anywhere
 * other than the dedicated admin host (env ADMIN_DOMAIN). After the host
 * move the panel lives at admin.{APP_DOMAIN}; the bare central host and
 * every tenant subdomain must 404 on admin URLs.
 *
 * Falls back to also accepting the bare central host (APP_DOMAIN) so the
 * panel is still reachable locally during the transition when ADMIN_DOMAIN
 * is not yet set in env. Once ADMIN_DOMAIN is configured the fallback path
 * becomes irrelevant.
 */
class EnsureAdminDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $adminDomain = (string) env('ADMIN_DOMAIN', '');

        // Primary case: explicit ADMIN_DOMAIN configured → must match exactly.
        if ($adminDomain !== '') {
            if ($host !== $adminDomain) {
                abort(404);
            }

            return $next($request);
        }

        // Transitional fallback for environments that have not yet set
        // ADMIN_DOMAIN — accept any central_domains entry so the panel
        // still works locally / in tests against the bare central host.
        $centralDomains = (array) config('tenancy.central_domains', []);
        if (! in_array($host, $centralDomains, true)) {
            abort(404);
        }

        return $next($request);
    }
}
