<?php

if (!function_exists('load_view')) {
    function load_view(string $filename, ?string $ext = 'php', int $type = \Sim\Loader\Loader::TYPE_INCLUDE)
    {
        \loader()->load(view_path($filename, false), $ext, $type);
    }
}

if (!function_exists('load_partial')) {
    function load_partial(string $filename, ?string $ext = 'php', int $type = \Sim\Loader\Loader::TYPE_INCLUDE)
    {
        \loader()->load(partial_path($filename, false), $ext, $type);
    }
}
