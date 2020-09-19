<?php

namespace Sim\Abstracts\Patterns;


use Sim\Interfaces\Patterns\IChainOfResponsibility;

abstract class AbstractChainOfResponsibility implements IChainOfResponsibility
{
    /**
     * @var $next IChainOfResponsibility
     */
    private $next;

    /**
     * This method can be used to build a chain of ChainOfResponsibilityInterface objects.
     *
     * @param IChainOfResponsibility $next
     * @return mixed
     */
    public function linkWith(IChainOfResponsibility $next)
    {
        $this->next = $next;

        return $next;
    }

    /**
     * Subclasses must override this method to provide their own checks. A
     * subclass can fall back to the parent implementation if it can't process a
     * request.
     *
     * @param mixed ...$_
     * @return bool
     */
    public function handle(...$_): bool
    {
        if (!$this->next) {
            return true;
        }

        return $this->next->handle(...$_);
    }
}