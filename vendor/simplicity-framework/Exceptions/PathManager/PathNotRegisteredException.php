<?php

namespace Sim\Exceptions\PathManager;


use Exception;
use Sim\Interfaces\PathManager\IPathException;
use Throwable;

class PathNotRegisteredException extends Exception implements IPathException
{
    /**
     * PathNotRegisteredException constructor.
     * @param string $alias
     * @param int|null $code
     * @param Throwable|null $previous
     */
    public function __construct(string $alias, int $code = null, Throwable $previous = null)
    {
        $message = "Path for {$alias} is not registered in path manager";
        parent::__construct($message, $code, $previous);
    }
}