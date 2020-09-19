<?php

namespace Sim\Abstracts\Patterns;

use Sim\Exceptions\Patterns\SingletonException;

abstract class AbstractSingleton
{
    private static $instances = [];

    /**
     * Singleton's constructor should not be public. However, it can't be
     * private either if we want to allow subclassing.
     */
    protected function __construct()
    {
    }

    /**
     * Cloning and deserialization are not permitted for singletons.
     */
    protected function __clone()
    {
    }

    /**
     * @throws SingletonException
     */
    public function __wakeup()
    {
        throw new SingletonException("Cannot deserialize singleton");
    }

    /**
     * The method you use to get the Singleton's instance.
     *
     * @return static
     */
    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static;
        }
        return self::$instances[$subclass];
    }
}