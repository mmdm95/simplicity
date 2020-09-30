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
    function get_base_url()
    {
        static $base;

        if (!isset($base)) {
            $base = get_protocol() . $_SERVER['SERVER_NAME'];
            if (!is_null($_SERVER['SERVER_PORT']) &&
                (('http' == get_protocol() && 80 != $_SERVER['SERVER_PORT']) ||
                    ('https' == get_protocol() && 443 != $_SERVER['SERVER_PORT']))) {
                $base .= ':' . $_SERVER['SERVER_PORT'];
            }
        }

        return $base;
    }
}

/**
 * Retrieves the best guess of the client's actual IP address.
 * Takes into account numerous HTTP proxy headers due to variations
 * in how different ISPs handle IP addresses in headers between hops.
 *
 * @see https://stackoverflow.com/questions/1634782/what-is-the-most-accurate-way-to-retrieve-a-users-correct-ip-address-in-php
 */
if (!function_exists('get_ip_address')) {
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

if (!function_exists('e')) {
    function e($string, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8', $double_encode = true)
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
