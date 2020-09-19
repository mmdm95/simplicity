<?php

namespace Sim\Interfaces\ConfigManager;


use Sim\Interfaces\ISetterGetter;

interface IConfig extends ISetterGetter
{
    /**
     * Store a specific array as config
     *
     * @param $alias
     * @param array $value
     * @return static
     */
    public function setAsConfig($alias, array $value);

    /**
     * Load a config without storing it
     *
     * @param $path
     * @return array
     */
    public function getDirectly($path): array;

    /**
     * Allow to check for config existence
     *
     * @param bool $answer
     * @return static
     */
    public function checkConfigExistence($answer = true);
}