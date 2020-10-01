<?php

if (!function_exists('is_version_compatible')) {
    function is_version_compatible($version)
    {
        return is_php($version);
    }
}

if (!function_exists('is_php')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param    string
     * @return    bool    TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string)$version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}

if (!function_exists('get_root')) {
    function get_root()
    {
        return BASE_ROOT;
    }
}

if (!function_exists('get_protocol')) {
    function get_protocol()
    {
        static $protocol;

        if (!isset($protocol)) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        }

        return $protocol;
    }
}

if (!function_exists('get_base_url')) {
    /**
     * @see https://stackoverflow.com/a/8891890/12154893
     */
    function get_base_url(): string
    {
        static $base = '';

        if (empty($base)) {
            $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
            $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
            $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
            $port     = $s['SERVER_PORT'];
            $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
            $host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
                ? $_SERVER['HTTP_X_FORWARDED_HOST']
                : (isset($_SERVER['HTTP_HOST'])
                    ? $_SERVER['HTTP_HOST']
                    : null);
            $host = isset($host) ? $host : $_SERVER['SERVER_NAME'] . $port;
            $base = $protocol . '://' . $host;
            $base .= '/';
        }

        return $base;
    }
}

if (!function_exists('get_ip_address')) {
    /**
     * Retrieves the best guess of the client's actual IP address.
     * Takes into account numerous HTTP proxy headers due to variations
     * in how different ISPs handle IP addresses in headers between hops.
     *
     * @see https://stackoverflow.com/questions/1634782/what-is-the-most-accurate-way-to-retrieve-a-users-correct-ip-address-in-php
     */
    function get_ip_address()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return 'unknown';
    }
}

if (!function_exists('root2url')) {
    function root2url(string $path)
    {
        $separator = '/';
        $baseRoot = str_replace(['\\', '/'], $separator, BASE_ROOT);
        $baseUrl = str_replace(['\\', '/'], $separator, get_base_url());
        $path = str_replace(['\\', '/'], $separator, $path);

        return str_replace($baseRoot, $baseUrl, $path);
    }
}

if (!function_exists('url2root')) {
    function url2root(string $path)
    {
        $separator = '/';
        $baseRoot = str_replace(['\\', '/'], $separator, BASE_ROOT);
        $baseUrl = str_replace(['\\', '/'], $separator, get_base_url());
        $path = str_replace(['\\', '/'], $separator, $path);

        return str_replace($baseUrl, $baseRoot, $path);
    }
}

if (!function_exists('e')) {
    function e($string, $flags = ENT_QUOTES, $encoding = 'UTF-8', $double_encode = true)
    {
        htmlspecialchars($string, $flags, $encoding, $double_encode);
    }
}

if (!function_exists('truncate')) {
    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     */
    function truncate(string $string, int $length, string $delimiter = '...')
    {
        return mb_strimwidth($string, 0, $length, $delimiter);
    }
}

if (!function_exists('truncate_word')) {
    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     */
    function truncate_word(string $string, int $length, string $delimiter = '...')
    {
        // we don't want new lines in our preview
        $text_only_spaces = preg_replace('/\s+/', ' ', $string);

        // truncates the text
        $text_truncated = mb_substr($text_only_spaces, 0, mb_strpos($text_only_spaces, ' ', $length));

        // prevents last word truncation
        $preview = trim(mb_substr($text_truncated, 0, mb_strrpos($text_truncated, ' '))) . $delimiter;

        return $preview;
    }
}
