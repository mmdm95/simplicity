<?php

namespace Sim\Interfaces\ConfigManager;


use Sim\Interfaces\ISetterGetter;

interface IConfig extends ISetterGetter
{
    /**
     * Store a specific array as config
     *
     * @param string $alias
     * @param array $value
     * @return static
     */
    public function setAsConfig(string $alias, array $value);

    /**
     * Load a config without storing it
     *
     * @param string $path
     * @return array
     */
    public function getDirectly(string $path): array;

    /**
     * Allow to check for config existence
     *
     * @param bool $answer
     * @return static
     */
    public function checkConfigExistence(bool $answer = true);
}