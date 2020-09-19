<?php

namespace Sim\Interfaces;


interface IHandler
{
    /**
     * @param mixed ...$_
     * @return mixed
     */
    public function handle(...$_);
}