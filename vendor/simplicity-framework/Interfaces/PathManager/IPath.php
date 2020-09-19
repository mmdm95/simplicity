<?php

namespace Sim\Interfaces\PathManager;


use Sim\Interfaces\ISetterGetter;

interface IPath extends ISetterGetter
{
    /**
     * Check if a path is registered to path manager
     *
     * @param $alias
     * @return bool
     */
    public function has($alias): bool;

    /**
     * Check if a path is exists
     *
     * @param $path
     * @return bool
     */
    public function exists($path): bool;

    /**
     * Allow to check for path existence
     *
     * @param bool $answer
     * @return IPath
     */
    public function checkPathExistence($answer = true): IPath;

    /**
     * Replace slashes and backslashes with Directory Separator or not
     *
     * @param bool $answer
     * @return IPath
     */
    public function replaceSlashes($answer = true): IPath;

    /**
     * Should add trailing slash or not
     * default is true
     *
     * @param bool $answer
     * @return IPath
     */
    public function addTrailingSlash($answer = true): IPath;
}