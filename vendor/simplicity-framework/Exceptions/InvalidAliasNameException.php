<?php

namespace Sim\Exceptions;


use Exception;
use Sim\Interfaces\IInvalidVariableNameException;
use Throwable;

class InvalidAliasNameException extends Exception implements IInvalidVariableNameException
{
    /**
     * InvalidAliasNameException constructor.
     * @param string $alias
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $alias, int $code = 0, Throwable $previous = null)
    {
        $message = "Invalid alias name {$alias}!";
        parent::__construct($message, $code, $previous);
    }
}