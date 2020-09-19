<?php

namespace Sim\Interfaces;


interface ISetter
{
    /**
     * @param $id
     * @param $value
     * @return mixed
     */
    public function set($id, $value);
}