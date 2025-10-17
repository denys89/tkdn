<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ServiceItemsCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Clear cache setelah operasi update/create/delete service items
        if ($this->shouldClearCache($request)) {
            $this->clearServiceItemsCache($request);
        }

        return $response;
    }

    /**
     * Determine if cache should be cleared
     */
    protected function shouldClearCache(Request $request): bool
    {
        $method = $request->method();
        $route = $request->route();

        if (! $route) {
            return false;
        }

        $routeName = $route->getName();

        // Clear cache pada operasi yang mengubah service items
        $clearCacheRoutes = [
            'service.store',
            'service.update',
            'service.destroy',
            'service.generate-form',
            'service.regenerate-form',
        ];

        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])
            && in_array($routeName, $clearCacheRoutes);
    }

    /**
     * Clear service items cache
     */
    protected function clearServiceItemsCache(Request $request): void
    {
        try {
            $serviceId = $this->getServiceIdFromRequest($request);

            if ($serviceId) {
                Log::info('Clearing service items cache', [
                    'service_id' => $serviceId,
                    'route' => $request->route()->getName(),
                    'method' => $request->method(),
                ]);

                // Clear cache untuk semua kemungkinan classification
                $classifications = ['3.1', '3.2', '3.3', '3.4', '3.5', '4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7'];
                $projectTypes = ['tkdn_jasa', 'tkdn_barang_jasa'];

                foreach ($classifications as $classification) {
                    Cache::forget("service_items_{$serviceId}_{$classification}");
                }

                foreach ($projectTypes as $projectType) {
                    Cache::forget("service_items_{$serviceId}_{$projectType}");
                }

                Log::info('Service items cache cleared successfully', [
                    'service_id' => $serviceId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear service items cache', [
                'error' => $e->getMessage(),
                'service_id' => $serviceId ?? 'unknown',
            ]);
        }
    }

    /**
     * Extract service ID from request
     */
    protected function getServiceIdFromRequest(Request $request): ?string
    {
        // Coba dari route parameter
        $service = $request->route('service');
        if ($service) {
            return is_object($service) ? $service->id : $service;
        }

        // Coba dari form data
        $serviceId = $request->input('service_id');
        if ($serviceId) {
            return $serviceId;
        }

        // Coba dari URL segments
        $segments = $request->segments();
        $serviceIndex = array_search('service', $segments);
        if ($serviceIndex !== false && isset($segments[$serviceIndex + 1])) {
            return $segments[$serviceIndex + 1];
        }

        return null;
    }
}
