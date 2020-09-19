<?php

namespace Sim\Exceptions\Container;


use Exception;
use Sim\Interfaces\Container\ContainerExceptionInterface;

class ParameterHasNoDefaultValueException extends Exception implements ContainerExceptionInterface
{
    /**
     * ParameterHasNoDefaultValueException constructor.
     * @param $parameter
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($parameter, $code = 0, Exception $previous = null)
    {
        $message = "Service can't be instantiate and {$parameter} has no default value!";
        parent::__construct($message, $code, $previous);
    }
}