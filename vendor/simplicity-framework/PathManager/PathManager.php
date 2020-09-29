<?php

namespace Sim\PathManager;


use Sim\Exceptions\FileNotExistsException;
use Sim\Exceptions\InvalidAliasNameException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\PathManager\IPath;

class PathManager implements IPath
{
    /**
     * @var $path array
     */
    protected static $path = [];

    /**
     * @var $check_path_existence bool
     */
    protected $check_path_existence = true;

    /**
     * @var $replace_slashes bool
     */
    protected $replace_slashes = true;

    /**
     * @var bool $trailing_slash
     */
    protected $trailing_slash = true;

    /**
     * Set a path for something
     *
     * @param string $alias
     * @param string $path
     * @return IPath
     * @throws IInvalidVariableNameException
     * @throws FileNotExistsException
     */
    public function set($alias, $path): IPath
    {
        $this->isValidAliasName($alias);
        $this->doCheckExistence($path);
        self::$path[$alias] = $path;
        return $this;
    }

    /**
     * Get a specific path
     *
     * @param $alias
     * @return mixed|null
     * @throws FileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     */
    public function get($alias)
    {
        $this->isValidAliasName($alias);
        if (!$this->has($alias)) {
            throw new PathNotRegisteredException($alias);
        }

        $this->doCheckExistence(self::$path[$alias]);
        $path = self::$path[$alias];

        if ($this->trailing_slash) {
            $path = $this->doAddEndSlash(false === $this->replace_slashes ? $path : $this->doReplaceSlashes($path));
        }
        return $path;
    }

    /**
     * Check if a path is registered to path manager
     *
     * @param string $alias
     * @return bool
     */
    public function has(string $alias): bool
    {
        return isset(self::$path[$alias]);
    }

    /**
     * Check if a path is exists
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Allow to check for path existence
     *
     * @param bool $answer
     * @return IPath
     */
    public function checkPathExistence(bool $answer = true): IPath
    {
        $this->check_path_existence = $answer;
        return $this;
    }

    /**
     * Replace slashes and backslashes with Directory Separator or not
     *
     * @param bool $answer
     * @return IPath
     */
    public function replaceSlashes(bool $answer = true): IPath
    {
        $this->replace_slashes = $answer;
        return $this;
    }

    /**
     * Should add trailing slash or not
     * default is true
     *
     * @param bool $answer
     * @return IPath
     */
    public function addTrailingSlash(bool $answer = true): IPath
    {
        $this->trailing_slash = $answer;
        return $this;
    }

    /**
     * @param string $alias
     * @throws InvalidAliasNameException
     */
    protected function isValidAliasName(string $alias): void
    {
        if (!preg_match('/[a-zA-Z_\/][a-zA-Z0-9_\/]*/', $alias)) {
            throw new InvalidAliasNameException($alias);
        }
    }

    /**
     * @param string $path
     * @throws FileNotExistsException
     */
    protected function doCheckExistence(string $path)
    {
        if ($this->check_path_existence && !$this->exists($path)) {
            throw new FileNotExistsException($path);
        }
    }

    /**
     * Replace slashes and backslashes with Directory Separator
     *
     * @param string $path
     * @return string
     */
    protected function doReplaceSlashes(string $path): string
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $path));
    }

    /**
     * Add a directory separator end of path string
     *
     * @param string $path
     * @return string
     */
    protected function doAddEndSlash(string $path): string
    {
        return trim($path, '/\\') . DIRECTORY_SEPARATOR;
    }
}