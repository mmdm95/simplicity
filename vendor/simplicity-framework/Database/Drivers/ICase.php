<?php

namespace Sim\Database\Drivers;


interface ICase
{
    /**
     * @param IWhen $when
     * @return mixed
     */
    public function case(IWhen $when);
}