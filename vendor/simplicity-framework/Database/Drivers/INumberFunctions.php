<?php

namespace Sim\Database\Drivers;


interface INumberFunctions
{
    /**
     * @param $number
     * @return mixed
     */
    public function abs($number);

    /**
     * @param $number - between -1 and 1
     * @return mixed
     */
    public function acos($number);

    /**
     * @param $number - between -1 and 1
     * @return mixed
     */
    public function asin($number);

    /**
     * @param $number
     * @return mixed
     */
    public function atan($number);

    /**
     * @param $number
     * @param $number2
     * @return mixed
     */
    public function atan2($number, $number2);

    /**
     * @param $number
     * @return mixed
     */
    public function ceil($number);

    /**
     * @param $number
     * @return mixed
     */
    public function ceiling($number);

    /**
     * @param $number
     * @return mixed
     */
    public function cos($number);

    /**
     * @param $number - bigger than 0
     * @return mixed
     */
    public function cot($number);

    /**
     * @param $radian
     * @return mixed
     */
    public function degrees($radian);

    /**
     * @param $number1
     * @param $number2
     * @return mixed
     */
    public function div($number1, $number2);

    /**
     * @param $number
     * @return mixed - return e raised to the power of $number
     */
    public function exp($number);

    /**
     * @param $number
     * @return mixed
     */
    public function floor($number);

    /**
     * @param mixed ...$_
     * @return mixed
     */
    public function greatest(...$_);

    /**
     * @param mixed ...$_
     * @return mixed
     */
    public function least(...$_);

    /**
     * @param $number - must be greater than 0
     * @return mixed
     */
    public function ln($number);

    /**
     * @param $base - must be greater than 1
     * @param $number - must be greater than 0
     * @return mixed
     */
    public function log($base, $number);

    /**
     * @param $number - must be greater than 0
     * @return mixed
     */
    public function log10($number);

    /**
     * @param $number - must be greater than 0
     * @return mixed
     */
    public function log2($number);

    /**
     * @param $value
     * @param $divide_to
     * @return mixed
     */
    public function mod($value, $divide_to);

    /**
     * @return mixed
     */
    public function pi();

    /**
     * @param $base
     * @param $exponent
     * @return mixed
     */
    public function pow($base, $exponent);

    /**
     * @param $base
     * @param $exponent
     * @return mixed
     */
    public function power($base, $exponent);

    /**
     * @param $degree
     * @return mixed
     */
    public function radians($degree);

    /**
     * @param null $seed
     * @return mixed
     */
    public function rand($seed = null);

    /**
     * @param $min
     * @param $max
     * @return mixed - RAND() * ($max - $min) + $min
     */
    public function randBetween($min, $max);

    /**
     * @param $min
     * @param $max
     * @return mixed - RAND() * ($max - $min + 1) + $min
     */
    public function randExactlyBetween($min, $max);

    /**
     * @param $column
     * @param $decimals
     * @return mixed
     */
    public function round($column, $decimals);

    /**
     * @param $number
     * @return mixed
     */
    public function sign($number);

    /**
     * @param $number
     * @return mixed
     */
    public function sin($number);

    /**
     * @param $number
     * @return mixed
     */
    public function sqrt($number);

    /**
     * @param $number
     * @return mixed
     */
    public function tan($number);

    /**
     * @param $number
     * @param $decimal
     * @return mixed
     */
    public function truncate($number, $decimal);
}