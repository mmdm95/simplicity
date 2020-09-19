<?php

namespace Sim\Database\Builder;


interface IAggregate
{
    /**
     * @param $column
     * @return mixed
     */
    public function avg($column);

    /**
     * @param $column
     * @return mixed
     */
    public function count($column);

    /**
     * @param $column
     * @return mixed
     */
    public function max($column);

    /**
     * @param $column
     * @return mixed
     */
    public function min($column);

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column);
}