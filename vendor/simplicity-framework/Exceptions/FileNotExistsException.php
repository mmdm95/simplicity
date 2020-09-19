<?php

namespace Sim\Exceptions;


use Exception;
use Sim\Interfaces\IFileNotExistsException;
use Throwable;

class FileNotExistsException extends Exception implements IFileNotExistsException
{
    /**
     * FileNotExistsException constructor.
     * @param string $path
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $path, int $code = 0, Throwable $previous = null)
    {
        $message = "File {$path} not exists!";
        parent::__construct($message, $code, $previous);
    }
}