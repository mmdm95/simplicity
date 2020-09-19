<?php

namespace Sim\Exceptions\Container;


use Exception;
use Sim\Interfaces\Container\NotFoundExceptionInterface;

class ParameterNotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * ParameterNotFoundException constructor.
     * @param $parameter
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($parameter, $code = 0, Exception $previous = null)
    {
        $message = "Parameter {$parameter} not found!";
        parent::__construct($message, $code, $previous);
    }
}