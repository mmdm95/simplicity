<?php

namespace Sim\Database\Drivers\Mysql;


interface IStringFunctions
{
    /**
     * @param $column
     */
    public function ascii($column);

    /**
     * @param $column
     */
    public function charLength($column);

    /**
     * @param $column
     */
    public function characterLength($column);

    /**
     * @param mixed ...$_
     */
    public function concat(...$_);

    /**
     * @param $separator
     * @param mixed ...$_
     */
    public function concatWS($separator, ...$_);

    /**
     * @param $value
     * @param mixed ...$_
     */
    public function field($value, ...$_);

    /**
     * @param $column
     * @param $string_list
     * @return mixed
     */
    public function findInSet($column, $string_list);

    /**
     * @param $column
     * @param $format
     * @return mixed
     */
    public function format($column, $format);

    /**
     * @param $first_string
     * @param $position
     * @param $number
     * @param $second_string
     * @return mixed
     */
    public function insert($first_string, $position, $number, $second_string);

    /**
     * @param $first_string
     * @param $second_string
     * @return mixed
     */
    public function instr($first_string, $second_string);

    /**
     * @param $column
     * @return mixed
     */
    public function lCase($column);

    /**
     * @param $column
     * @param $number_of_chars
     * @return mixed
     */
    public function left($column, $number_of_chars);

    /**
     * @param $column
     * @return mixed
     */
    public function length($column);

    /**
     * @param $substring
     * @param $string
     * @param int $start
     * @return mixed
     */
    public function locate($substring, $string, $start = 1);

    /**
     * @param $column
     * @return mixed
     */
    public function lower($column);

    /**
     * @param $column
     * @param $length
     * @param $pad_string
     * @return mixed
     */
    public function lPad($column, $length, $pad_string);

    /**
     * @param $column
     * @return mixed
     */
    public function ltrim($column);

    /**
     * @param $column
     * @param $start
     * @param null $length
     * @return mixed
     */
    public function mid($column, $start, $length = null);

    /**
     * @param $substring
     * @param $column
     * @return mixed
     */
    public function position($substring, $column);

    /**
     * @param $string
     * @param $times
     * @return mixed
     */
    public function repeat($string, $times);

    /**
     * @param $string
     * @param $prev_string
     * @param $new_string
     * @return mixed
     */
    public function replace($string, $prev_string, $new_string);

    /**
     * @param $column
     * @return mixed
     */
    public function reverse($column);

    /**
     * @param $column
     * @param $number_of_chars
     * @return mixed
     */
    public function right($column, $number_of_chars);

    /**
     * @param $column
     * @param $length
     * @param $pad_string
     * @return mixed
     */
    public function rPad($column, $length, $pad_string);

    /**
     * @param $column
     * @return mixed
     */
    public function rtrim($column);

    /**
     * @param $number
     * @return mixed
     */
    public function space($number);

    /**
     * @param $first
     * @param $second
     * @return mixed
     */
    public function strcmp($first, $second);

    /**
     * @param $column
     * @param $from
     * @param null $length
     * @return mixed
     */
    public function substr($column, $from, $length = null);

    /**
     * @param $column
     * @param $from
     * @param null $length
     * @return mixed
     */
    public function subString($column, $from, $length = null);

    /**
     * @param $column
     * @param $delimiter
     * @param $number
     * @return mixed
     */
    public function subStringIndex($column, $delimiter, $number);

    /**
     * @param $column
     * @return mixed
     */
    public function trim($column);

    /**
     * @param $column
     * @return mixed
     */
    public function uCase($column);

        /**
     * @param $column
     * @return mixed
     */
    public function upper($column);
}