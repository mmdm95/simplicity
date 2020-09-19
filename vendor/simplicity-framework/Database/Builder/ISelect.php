<?php

namespace Sim\Database\Builder;


interface ISelect extends IAggregate, IWhere
{
    const ASC = 'ACS';
    const DESC = 'DESC';

    /**
     * @param array|string $columns
     * @return ISelect
     */
    public function columns($columns): ISelect;

    /**
     * @param $column
     * @return ISelect
     */
    public function distinctColumn($column): ISelect;

    /**
     * @param array|string $table
     * @return ISelect
     */
    public function from($table): ISelect;

    /**
     * @param $column
     * @return ISelect
     */
    public function all($column): ISelect;

    /**
     * @param $column
     * @return ISelect
     */
    public function any($column): ISelect;

    /**
     * @param $columns
     * @return ISelect
     */
    public function groupBy($columns): ISelect;

    /**
     * @param $having
     * @return ISelect
     */
    public function having($having): ISelect;

    /**
     * @param $columns
     * @return ISelect
     */
    public function orderBy($columns): ISelect;

    /**
     * @param int $limit
     * @param int $offset
     * @return ISelect
     */
    public function limit($limit, $offset = 0): ISelect;

    /**
     * @param $on
     * @param $condition
     * @return ISelect
     */
    public function join($on, $condition): ISelect;

    /**
     * @param $on
     * @param $condition
     * @return ISelect
     */
    public function leftJoin($on, $condition): ISelect;

    /**
     * @param $on
     * @param $condition
     * @return ISelect
     */
    public function rightJoin($on, $condition): ISelect;

    /**
     * @param $query
     * @return void
     */
    public function query($query): void;

    /**
     * @return string
     */
    public function getQuery(): string;
}