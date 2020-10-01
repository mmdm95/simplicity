<?php

if (!function_exists('get_path')) {
    function get_path($alias, string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        $newPath = '';
        $newPath = rtrim(str_replace(['\\', '/'], $separator, \path()->addTrailingSlash($trailing_slash)->get($alias)), '\\/')
            . $separator . (!empty($path) ? $path . $separator : '');
        $newPath = false === $trailing_slash ? rtrim($newPath, '\\/') : $newPath;

        return $newPath;
    }
}

if (!function_exists('app_path')) {
    function app_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('app', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('logic_path')) {
    function logic_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('logic', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('design_path')) {
    function design_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('design', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('view_path')) {
    function view_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('view', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('layout_path')) {
    function layout_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('layout', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('partial_path')) {
    function partial_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('partial', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('error_path')) {
    function error_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('error', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('controller_path')) {
    function controller_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('controller', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('model_path')) {
    function model_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('model', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('middleware_path')) {
    function middleware_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('middleware', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('config_path')) {
    function config_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('config', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('translation_path')) {
    function translation_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('i18n', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('cache_path')) {
    function cache_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('cache', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('log_path')) {
    function log_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('log', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('asset_path')) {
    function asset_path(string $path = '', bool $add_version = true)
    {
        return get_base_url() . $path . ($add_version ? '?' . APP_VERSION : '');
    }
}

if (!function_exists('resource_path')) {
    function resource_path(string $path = '', bool $add_version = true)
    {
        return get_base_url() . $path . ($add_version ? '?' . APP_VERSION : '');
    }
}

if (!function_exists('core_path')) {
    function core_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('core', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('helper_path')) {
    function helper_path(string $path = '', bool $trailing_slash = false, $separator = '/')
    {
        return get_path('helper', $path, $trailing_slash, $separator);
    }
}

if (!function_exists('hashed_path')) {
    function hashed_path($asset_dir, $asset_name): string
    {
        $separator = '/';
        $manifest = manifest_content();
        $assetName = $manifest[$asset_name] ?? '';
        if (!empty($asset_dir)) {
            $asset_dir = trim($asset_dir, '\\/') . $separator;
        }
        $assetDir = get_base_url() . $asset_dir . $assetName;

        return $assetDir;
    }
}
