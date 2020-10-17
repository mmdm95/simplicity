<?php

namespace Sim\Database\Drivers;


interface IWhen
{
    /**
     * @param $condition
     * @param $then
     * @return mixed
     */
    public function when($condition, $then);

    /**
     * @param $value
     * @return void
     */
    public function else($value): void;
}