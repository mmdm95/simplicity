<?php

namespace Sim\Utils;

class ArrayUtil
{
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function mergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::mergeRecursiveDistinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }
        return $merged;
    }

    /**
     * This function uniques a multidimensional array that have one level deep
     *
     * @param $array
     * @param $key
     * @return array
     * @author Krunal Lathiya
     * @see https://appdividend.com/2019/04/12/how-to-remove-duplicate-values-from-an-array-in-php/
     * @example
     * input:
     *
     * $data = array(
     *   0 => array("id"=>"1", "name"=>"Krunal",  "age"=>"26"),
     *   1 => array("id"=>"2", "name"=>"Ankit", "age"=>"25"),
     *   2 => array("id"=>"1", "name"=>"Krunal",  "age"=>"26"),
     * );
     *
     * output:
     *
     * array(
     *   [0] => array(
     *     [id] => 1
     *     [name] => Krunal
     *     [age] => 26
     *   )
     *  [1] => array(
     *     [id] => 2
     *     [name] => Ankit
     *     [age] => 25
     *   )
     * )
     */
    public static function uniqueMulti($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;

        // Commented part does exact the same thing but in more expensive way
//        $tempArr = array_unique(array_column($array, $key));
//        return array_intersect_key($array, $tempArr);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function uniqueRecursive(array &$array)
    {
        $uniqued = array_unique($array, SORT_REGULAR);
        foreach ($uniqued as $key => &$value) {
            if (is_array($value)) {
                $uniqued[$key] = self::uniqueRecursive($value);
            }
        }
        return $uniqued;
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param string $key Property to sort by.
     * @param array $data Array that stores multiple associative arrays.
     * @param array $wantedKeys Array of columns that you need your new grouped array have.
     * @param bool $reverseWantedKeys
     * @return array
     */
    public static function arrayGroupBy($key, $data, $wantedKeys = [], $reverseWantedKeys = false)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $newVal = $val;
                if (is_array($wantedKeys) && count($wantedKeys)) {
                    if (!($reverseWantedKeys === true)) {
                        $newVal = [];
                    }
                    foreach ($wantedKeys as $wantedKey) {
                        if (array_key_exists($wantedKey, $val)) {
                            if ($reverseWantedKeys === true) {
                                unset($newVal[$wantedKey]);
                            } else {
                                $newVal[$wantedKey] = $val[$wantedKey];
                            }
                        }
                    }
                }
                $result[$val[$key]][] = $newVal;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }

    /**
     * @param $array
     * @param $key
     * @param mixed|null $value
     */
    public static function set(&$array, $key, $value = null)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function get(&$array, $key, $default = null)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return $default;
            }
            $array = &$array[$key];
        }
        return $array[array_shift($keys)] ?? $default;
    }

    /**
     * @param $array
     * @param $key
     */
    public static function remove(&$array, $key)
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return;
            }
            $array = &$array[$key];
        }
        unset($array[array_shift($keys)]);
    }

    /**
     * @param $array
     * @param $key
     * @param bool $is_null_ok
     * @return bool
     */
    public static function has(&$array, $key, $is_null_ok = true): bool
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return false;
            }
            $array = &$array[$key];
        }
        $result = true;
        $last = array_shift($keys);
        if (!isset($array[$last])) $result = false;
        if ((bool)$is_null_ok && array_key_exists($last, $array) && is_null($array[$last])) {
            $result = true;
        }
        return $result;
    }
}