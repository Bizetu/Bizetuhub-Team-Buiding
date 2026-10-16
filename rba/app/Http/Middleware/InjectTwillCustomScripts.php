<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Listings\Filters\MultiSelectFilter;

/**
 * InjectTwillCustomScripts
 *
 * Appends twill-multiselect.js to every Twill admin HTML response and injects
 * a window.TwillMultiSelectFilters config object built by introspecting the
 * CURRENT route's controller.
 *
 * This means:
 *  - No options are duplicated in middleware — they live only in your controller.
 *  - Works automatically for ANY Twill module that uses MultiSelectFilter.
 *  - Company-HR scoping and other per-controller logic is respected because we
 *    call the real controller->filters() method.
 *
 * Registration (Laravel 12 — bootstrap/app.php):
 *
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->append(\App\Http\Middleware\InjectTwillCustomScripts::class);
 *   })
 */
class InjectTwillCustomScripts
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldInject($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if (! str_contains($content, '</body>')) {
            return $response;
        }

        $injection = "\n"
            . $this->buildConfigScript($request)
            . "\n"
            . '<script defer src="' . asset('js/twill-multiselect.js')
            . '?v=' . config('app.version', '3.1') . '"></script>'
            . "\n";

        $response->setContent(
            str_replace('</body>', $injection . '</body>', $content)
        );

        return $response;
    }

    // ─── Config builder ───────────────────────────────────────────────────────

    /**
     * Resolves the current route's controller, calls filters() on it, and
     * extracts every MultiSelectFilter into the JS config shape:
     *
     *   {
     *     "course_id": { "label": "Course",   "options": [{value,label}, ...] },
     *     "branch_id": { "label": "Branch",   "options": [...] },
     *   }
     *
     * The key is always the queryString (e.g. "course_id") so the JS writes
     * the correct key into the filter URL parameter.
     */
    private function buildConfigScript(Request $request): string
    {
        $config = [];

        try {
            $config = $this->resolveMultiSelectFilters($request);
        } catch (\Throwable $e) {
            // Silently degrade — the page still loads, filters just don't appear.
            // Uncomment for debugging:
            // logger()->warning('TwillMultiSelect config error: ' . $e->getMessage());
        }

        $json = json_encode($config, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        return "<script>window.TwillMultiSelectFilters={$json};</script>";
    }

    /**
     * Resolve MultiSelectFilter instances from the current route's controller.
     *
     * We instantiate the controller through the IoC container so that any
     * constructor dependencies (Auth, repositories, etc.) are injected correctly,
     * and per-user scoping (e.g. Company HR) is respected.
     */
    private function resolveMultiSelectFilters(Request $request): array
    {
        $route = $request->route();
        if (! $route) return [];

        // Route::current() uses array('controller@method') or ['uses' => '...']
        $action = $route->getActionName(); // e.g. "App\Http\Controllers\Twill\UserLicenseController@index"

        if (! $action || ! str_contains($action, '@')) return [];

        [$controllerClass] = explode('@', $action);

        if (! class_exists($controllerClass)) return [];

        // Resolve through the container so constructor injection works
        $controller = app($controllerClass);

        if (! method_exists($controller, 'filters')) return [];

        $tableFilters = $controller->filters();

        if (! $tableFilters) return [];

        // TableFilters is iterable; pull out MultiSelectFilter instances
        $config = [];

        foreach ($tableFilters as $filter) {
            if (! ($filter instanceof MultiSelectFilter)) continue;
            if (! $filter->isEnabled()) continue;

            $qs = $filter->getQueryString();

            $config[$qs] = [
                'label'   => $filter->getLabel(),
                'options' => $filter->getOptionsArray(),
            ];
        }

        return $config;
    }

    // ─── Guard ────────────────────────────────────────────────────────────────

    private function shouldInject(Request $request, Response $response): bool
    {
        $adminPath = trim(config('twill.admin_app_path', 'admin'), '/');

        return ($request->is($adminPath) || $request->is($adminPath . '/*'))
            && str_contains($response->headers->get('Content-Type', 'text/html'), 'text/html');
    }
}