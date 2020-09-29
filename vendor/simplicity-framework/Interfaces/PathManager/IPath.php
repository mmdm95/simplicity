<?php

namespace Sim\Interfaces\PathManager;


use Sim\Interfaces\ISetterGetter;

interface IPath extends ISetterGetter
{
    /**
     * Check if a path is registered to path manager
     *
     * @param string $alias
     * @return bool
     */
    public function has(string $alias): bool;

    /**
     * Check if a path is exists
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Allow to check for path existence
     *
     * @param bool $answer
     * @return IPath
     */
    public function checkPathExistence(bool $answer = true): IPath;

    /**
     * Replace slashes and backslashes with Directory Separator or not
     *
     * @param bool $answer
     * @return IPath
     */
    public function replaceSlashes(bool $answer = true): IPath;

    /**
     * Should add trailing slash or not
     * default is true
     *
     * @param bool $answer
     * @return IPath
     */
    public function addTrailingSlash(bool $answer = true): IPath;
}