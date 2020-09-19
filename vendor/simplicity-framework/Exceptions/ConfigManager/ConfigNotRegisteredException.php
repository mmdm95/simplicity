<?php

namespace Sim\Exceptions\ConfigManager;


use Exception;
use Sim\Interfaces\ConfigManager\IConfigException;
use Throwable;

class ConfigNotRegisteredException extends Exception implements IConfigException
{
    /**
     * ConfigNotRegisteredException constructor.
     * @param string $alias
     * @param int|null $code
     * @param Throwable|null $previous
     */
    public function __construct(string $alias, int $code = null, Throwable $previous = null)
    {
        $message = "Config with alias {$alias} is not registered in config manager";
        parent::__construct($message, $code, $previous);
    }
}