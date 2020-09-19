<?php

namespace Sim\Exceptions;


use Exception;

class ClassNotFoundException extends Exception
{
    /**
     * FileNotExistsException constructor.
     * @param string $class
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct(string $class, int $code = 0, \Throwable $previous = null)
    {
        $message = "Class {$class} not found!";
        parent::__construct($message, $code, $previous);
    }
}