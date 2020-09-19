<?php

namespace Sim\Interfaces\Patterns;


use Sim\Interfaces\IHandler;

interface IChainOfResponsibility extends IHandler
{
    /**
     * This method can be used to build a chain of ChainOfResponsibilityInterface objects.
     *
     * @param IChainOfResponsibility $next
     * @return mixed
     */
    public function linkWith(IChainOfResponsibility $next);
}