<?php

if (!function_exists('load_view')) {
    function load_view(string $filename, array $data = [], ?string $ext = 'php', int $type = \Sim\Loader\Loader::TYPE_INCLUDE)
    {
        \loader()->setData($data)->load(view_path($filename, false), $ext, $type);
    }
}

if (!function_exists('load_partial')) {
    function load_partial(string $filename, array $data = [], ?string $ext = 'php', int $type = \Sim\Loader\Loader::TYPE_INCLUDE)
    {
        \loader()->setData($data)->load(partial_path($filename, false), $ext, $type);
    }
}
