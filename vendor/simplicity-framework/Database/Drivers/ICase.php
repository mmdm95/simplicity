<?php

namespace Sim\Database\Drivers\Mysql;


interface ICase
{
    /**
     * @param IWhen $when
     * @return mixed
     */
    public function case(IWhen $when);
}