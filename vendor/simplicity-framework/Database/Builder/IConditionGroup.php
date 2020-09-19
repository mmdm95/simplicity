<?php

namespace Sim\Database\Builder;


interface IConditionGroup
{
    /**
     * @param $where
     * @return IConditionGroup
     */
    public function group($where): IConditionGroup;

    /**
     * @param $where
     * @return IConditionGroup
     */
    public function andGroup($where): IConditionGroup;

    /**
     * @param $where
     * @return IConditionGroup
     */
    public function orGroup($where): IConditionGroup;

    /**
     * @return string
     */
    public function getConditions(): string;
}