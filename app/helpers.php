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
}

if (! function_exists('isRouteShown'))
{
    /**
     * Check if the current route and return either "show" or "collapse"
     */
    function isRouteShown(array $toMatch)
    {
        return isRouteActive($toMatch) == 'active' ? 'show' : 'collapse';
    }
}

if (! function_exists('strLimit')) {
    /**
     * Define a function to do a character limiter
     */
    function strLimit(string $string, int $limit = 40)
    {
        return Str::limit($string, $limit, '&raquo');
    }
}

if (! function_exists('toTitle')) {
    /**
     * Define a function to convert a string into capitalize text
     */
    function toTitle(string $string)
    {
        return Str::replace('-', ' ', Str::title($string));
    }
}

if (! function_exists('getOrdinalSuffix')) {
    /**
     * Define a function to identify a number's ordinal suffix
     */
    function getOrdinalSuffix(int $number)
    {
        $type = null;

        if ($number % 10 == 1 && $number % 100 != 11) {
            $type = 'st';
        } elseif ($number % 10 == 2 && $number % 100 != 12) {
            $type = 'nd';
        } elseif ($number % 10 == 3 && $number % 100 != 13) {
            $type = 'rd';
        } else {
            $type = 'th';
        }

        return $number . $type;
    }
}