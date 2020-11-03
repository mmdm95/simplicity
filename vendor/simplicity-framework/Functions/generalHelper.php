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
    function get_root(): string
    {
        return BASE_ROOT;
    }
}

if (!function_exists('get_protocol')) {
    function get_protocol(): string
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
            $port = $_SERVER['SERVER_PORT'];
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
    function get_ip_address(): string
    {
        foreach (array('HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key) {
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
    /**
     * @param string $path
     * @return string
     */
    function root2url(string $path): string
    {
        $separator = '/';
        $baseRoot = str_replace(['\\', '/'], $separator, BASE_ROOT);
        $baseUrl = str_replace(['\\', '/'], $separator, get_base_url());
        $path = str_replace(['\\', '/'], $separator, $path);

        return str_replace($baseRoot, $baseUrl, $path);
    }
}

if (!function_exists('url2root')) {
    /**
     * @param string $path
     * @return string
     */
    function url2root(string $path): string
    {
        $separator = '/';
        $baseRoot = str_replace(['\\', '/'], $separator, BASE_ROOT);
        $baseUrl = str_replace(['\\', '/'], $separator, get_base_url());
        $path = str_replace(['\\', '/'], $separator, $path);

        return str_replace($baseUrl, $baseRoot, $path);
    }
}

if (!function_exists('e')) {
    /**
     * @param $string
     * @param int $flags
     * @param string $encoding
     * @param bool $double_encode
     * @return string
     */
    function e($string, $flags = ENT_QUOTES, $encoding = 'UTF-8', $double_encode = true): string
    {
        return htmlspecialchars($string, $flags, $encoding, $double_encode);
    }
}



if (!function_exists('hexentities')) {
    /**
     * To convert a string to hex value like convert
     * email address to hex to prevent spam bots to
     * index them and etc.
     *
     * @see https://www.php.net/manual/en/function.bin2hex.php#48861
     * @param $str
     * @return string
     */
    function hexentities($str): string
    {
        $return = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $return .= '&#x' . bin2hex(substr($str, $i, 1)) . ';';
        }
        return $return;
    }
}
