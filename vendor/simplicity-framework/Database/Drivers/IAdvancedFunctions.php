<?php

namespace Sim\Database\Drivers;


interface IAdvancedFunctions
{
    const DATATYPE_DATE = 'DATE';
    const DATATYPE_DATETIME = 'DATETIME';
    const DATATYPE_TIME = 'TIME';
    const DATATYPE_CHAR = 'CHAR';
    const DATATYPE_SIGNED = 'SIGNED';
    const DATATYPE_UNSIGNED = 'UNSIGNED';
    const DATATYPE_BINARY = 'BINARY';

    /**
     * @param $number
     * @return mixed
     */
    public function bin($number);

    /**
     * @param $value
     * @return mixed
     */
    public function binary($value);

    /**
     * @param $value
     * @param $datatype
     * @return mixed
     */
    public function cast($value, $datatype);

    /**
     * @param mixed ...$_
     * @return mixed
     */
    public function coalesce(...$_);

    /**
     * @return mixed
     */
    public function connectionId();

    /**
     * @param $number
     * @param $from_base
     * @param $to_base
     * @return mixed
     */
    public function conv($number, $from_base, $to_base);

    /**
     * @param $value
     * @param $datatype
     * @return mixed
     */
    public function convert($value, $datatype);

    /**
     * @return mixed
     */
    public function currentUser();

    /**
     * @return mixed
     */
    public function database();

    /**
     * @param $condition
     * @param $value_if_true
     * @param $value_if_false
     * @return mixed
     */
    public function if($condition, $value_if_true, $value_if_false);

    /**
     * @param $expression
     * @param $alt_value
     * @return mixed
     */
    public function ifNull($expression, $alt_value);

    /**
     * @param $expression
     * @return mixed
     */
    public function isNull($expression);

    /**
     * @param $expression
     * @return mixed
     */
    public function lastInsertId($expression);

    /**
     * @param $expr1
     * @param $expr2
     * @return mixed
     */
    public function nullIf($expr1, $expr2);

    /**
     * @return mixed
     */
    public function sessionUser();

    /**
     * @return mixed
     */
    public function systemUser();

    /**
     * @return mixed
     */
    public function user();

    /**
     * @return mixed
     */
    public function version();
}