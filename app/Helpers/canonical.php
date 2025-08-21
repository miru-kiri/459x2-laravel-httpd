<?php
if (!function_exists('canonical_url')) {
    function canonical_url()
    {
        $base = url()->current();
        $routePath = request()->path();

        $paramOrder = match (true) {
            $routePath === '/' => [],
            str_contains($routePath, 'detail/cast/detail') => ['genre_id', 'area_id', 'site_id', 'cast_id', 'tab_id'],
            str_contains($routePath, 'detail/blog/detail') => ['genre_id', 'area_id', 'site_id', 'tab_id','category_id' ,'id'],
            str_contains($routePath, 'detail/top') => ['genre_id', 'area_id', 'site_id', 'tab_id'],
            str_contains($routePath, 'detail/area') => ['genre_id', 'area_id'],
            str_contains($routePath, 'detail') => ['genre_id'],
            default => []
        };

        $params = collect($paramOrder)
            ->filter(fn($key) => request()->has($key))
            ->mapWithKeys(fn($key) => [$key => request()->get($key)])
            ->toArray();

        return empty($params) ? $base : $base . '?' . http_build_query($params);
    }
}
