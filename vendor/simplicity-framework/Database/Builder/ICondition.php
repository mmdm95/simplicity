<?php

namespace Sim\Database\Builder;


interface ICondition extends IConditionGroup
{
    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function equal($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function notEqual($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function greaterThan($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function greaterThanEqual($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function lessThan($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function lessThanEqual($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function like($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function leftLike($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function rightLike($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function between($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function in($first, $second): ICondition;

    /**
     * @param $first
     * @param $second
     * @return ICondition
     */
    public function notIn($first, $second): ICondition;

    /**
     * @param $select
     * @return ICondition
     */
    public function all($select): ICondition;

    /**
     * @param $select
     * @return ICondition
     */
    public function any($select): ICondition;

    /**
     * @param $column
     * @return ICondition
     */
    public function isNull($column): ICondition;

    /**
     * @param $column
     * @return ICondition
     */
    public function isNotNull($column): ICondition;

    /**
     * @return ICondition
     */
    public function andCondition(): ICondition;

    /**
     * @return ICondition
     */
    public function orCondition(): ICondition;

    /**
     * @return string
     */
    public function getCondition(): string;
}