<?php

namespace Sim\Traits;


trait TraitGeneral
{
    /**
     * @param array|string $str
     * @param array|string $from
     * @param string $to
     * @return array|string
     */
    protected function slashConverter(&$str, $from = ['\\', '/'], $to = DIRECTORY_SEPARATOR)
    {
        if (is_array($str)) {
            $newStr = [];
            foreach ($str as $key => $value) {
                $newStr[$key] = $this->slashConverter($value, $from, $to);
            }
            return $newStr;
        }
        if (is_array($from)) {
            foreach ($from as $item) {
                $this->slashConverter($str, $item, $to);
            }
        }
        if (is_string($from)) {
            $str = str_replace($from, $to, $str);
        }
        return $str;
    }

    /**
     * Test if $name is a valid variable name
     *
     * @param $name
     * @return bool
     */
    protected function isValidName($name): bool
    {
        return preg_match('/[a-zA-Z_][a-zA-Z0-9_]*/', $name);
    }

    /**
     * Check if a timestamp is valid or not
     *
     * @param $timestamp
     * @return bool
     */
    protected function isValidTimestamp($timestamp): bool
    {
        if (is_numeric($timestamp)) {
            return ($timestamp <= PHP_INT_MAX)
                && ($timestamp >= ~PHP_INT_MAX);
        } elseif (is_string($timestamp)) {
            return ((string)(int)$timestamp === $timestamp)
                && ($timestamp <= PHP_INT_MAX)
                && ($timestamp >= ~PHP_INT_MAX);
        }
        return false;
    }
}