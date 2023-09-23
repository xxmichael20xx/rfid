<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (! function_exists('isRouteActive'))
{
    /**
     * Check if the current route and return "active"
     */
    function isRouteActive(array $toMatch)
    {
        $isActive = '';
        $current = Route::currentRouteName();

        foreach ($toMatch as $value) {
            if ($current == $value) {
                $isActive = 'active';
                break;
            }
        }

        return $isActive;
    }

    /**
     * Define a function to do a character limiter
     */
    function strLimit(string $string, int $limit = 40)
    {
        return Str::limit($string, $limit, '&raquo');
    }
}