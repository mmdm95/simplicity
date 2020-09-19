<?php

if (!function_exists('get_path')) {
    function get_path($alias, $path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        $newPath = null;;
        $newPath = rtrim(strtr(path()->get($alias), ['\\' => $separator, '/' => $separator]), '/\\')
            . $separator . (!empty($path) ? $path . $separator : '');
        $newPath = false === $trailingSlash ? rtrim($newPath, '\\/') : $newPath;

        return $newPath;
    }
}

if (!function_exists('app_path')) {
    function app_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('app', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('logic_path')) {
    function logic_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('logic', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('design_path')) {
    function design_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('design', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('view_path')) {
    function view_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('view', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('layout_path')) {
    function layout_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('layout', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('partial_path')) {
    function partial_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('partial', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('error_path')) {
    function error_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('error', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('controller_path')) {
    function controller_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('controller', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('model_path')) {
    function model_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('model', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('middleware_path')) {
    function middleware_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('middleware', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('translation_path')) {
    function translation_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('i18n', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('config_path')) {
    function config_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('config', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('public_path')) {
    function asset_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('public', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('core_path')) {
    function core_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('core', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('helper_path')) {
    function helper_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('helper', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('log_path')) {
    function log_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('log', $path, $trailingSlash, $separator);
    }
}

if (!function_exists('cache_path')) {
    function cache_path($path = '', $trailingSlash = true, $separator = DIRECTORY_SEPARATOR)
    {
        return get_path('cache', $path, $trailingSlash, $separator);
    }
}
