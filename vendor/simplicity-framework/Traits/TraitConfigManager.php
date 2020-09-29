<?php

namespace Sim\Traits;

use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\FileNotExistsException;
use Sim\Exceptions\InvalidAliasNameException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\Loader\ILoader;
use Sim\Loader\LoaderSingleton;
use Sim\Utils\ArrayUtil;

trait TraitConfigManager
{
    use TraitGeneral;

    /**
     * @var array $config
     */
    protected static $config = [];

    /**
     * @var array $loaded_configs
     */
    protected static $loaded_configs = [];

    /**
     * @var $check_config_existence bool
     */
    protected $check_config_existence = true;

    /**
     * Store a config path
     *
     * @param $alias
     * @param $path
     * @return static
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function set($alias, $path)
    {
        $this->isValidAliasName($alias);
        $this->doCheckExistence($path);
        if (!isset(self::$config[$alias])) {
            self::$config[$alias] = $path;
        }
        return $this;
    }

    /**
     * Store a specific array as config
     *
     * @param string $alias
     * @param array $value
     * @return static
     * @throws IInvalidVariableNameException
     */
    public function setAsConfig(string $alias, array $value)
    {
        $this->isValidAliasName($alias);
        self::$loaded_configs[$alias] = $value;
        return $this;
    }

    /**
     * Get a stored config string
     *
     * @param $alias
     * @return mixed
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function get($alias)
    {
        $arr = explode('.', $alias);
        $alias = array_shift($arr);
        if (count($arr) >= 2) {
            $keys = implode('.', $arr);
        }

        $this->isValidAliasName($alias);
        if (isset(self::$loaded_configs[$alias])) {
            if (!isset($keys)) return self::$loaded_configs[$alias];
            return ArrayUtil::get(self::$loaded_configs[$alias], $keys);
        }

        if (!isset(self::$config[$alias])) {
            throw new ConfigNotRegisteredException($alias);
        }

        $this->doCheckExistence(self::$config[$alias]);
        $config = $this->getDirectly(self::$config[$alias]);
        self::$loaded_configs[$alias] = $config;

        if (!isset($keys)) return $config;
        return ArrayUtil::get($config, $keys);
    }

    /**
     * Load a config without storing it
     *
     * @param string $path
     * @return array
     * @throws IFileNotExistsException
     */
    public function getDirectly(string $path): array
    {
        return LoaderSingleton::getInstance()->returnLoadedFile(true)->load_include($path, ILoader::EXT_PHP);
    }

    /**
     * Allow to check for config existence
     *
     * @param bool $answer
     * @return static
     */
    public function checkConfigExistence(bool $answer = true)
    {
        $this->check_config_existence = $answer;
        return $this;
    }

    /**
     * Check if an alias is a valid name or not
     *
     * @param string $alias
     * @throws IInvalidVariableNameException
     */
    protected function isValidAliasName(string $alias): void
    {
        if (!$this->isValidName($alias)) {
            throw new InvalidAliasNameException($alias);
        }
    }

    /**
     * Check if a path is exists as file
     *
     * @param string $path
     * @throws IFileNotExistsException
     */
    protected function doCheckExistence(string $path)
    {
        if ($this->check_config_existence && !file_exists($path)) {
            throw new FileNotExistsException($path);
        }
    }
}