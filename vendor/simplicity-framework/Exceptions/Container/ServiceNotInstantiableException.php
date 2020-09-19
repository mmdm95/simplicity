<?php

namespace Sim\Exceptions\Container;


use Exception;
use Sim\Interfaces\Container\ContainerExceptionInterface;

class ServiceNotInstantiableException extends Exception implements ContainerExceptionInterface
{
    /**
     * ServiceNotInstantiableException constructor.
     * @param $service
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($service, $code = 0, Exception $previous = null)
    {
        $message = "Service {$service} is not instantiable";
        parent::__construct($message, $code, $previous);
    }
}